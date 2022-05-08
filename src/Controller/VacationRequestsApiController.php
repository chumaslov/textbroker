<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\EmployeeRequests;
use App\Entity\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;

class VacationRequestsApiController extends AbstractController
{
    /**
     * @Route("/api/vacation_requests/list/?{status}", methods={"GET"})
     */
    public function list(string $status): JsonResponse
    {
        if (empty($status)) {
            $status = 'all';
        }

        $employeeRequests = $this->getDoctrine()->getRepository(EmployeeRequests::class);
        if ($status == 'all') {
            $entity = $employeeRequests->findAll();
        } else {
            $entity = $employeeRequests->findBy(['status' => $status]);
        }

        return new JsonResponse(
            $this->container->get('serializer')->serialize($entity, 'json'),
            200,
            [],
            true
        );
    }

    /**
     * @Route("/api/vacation_requests/approve_request/{id}", methods={"PATCH"})
     */
    public function approveRequest(int $id, Request $request): JsonResponse
    {
        $managerId = (int)$request->get('manager_id');

        $managerRepository = $this->getDoctrine()->getRepository(Manager::class);
        $manager = $managerRepository->find($managerId);
        if (empty($manager)) {
            return new JsonResponse(['message' => 'Manager not found'], 400);
        }

        $employeeRequests = $this->getDoctrine()->getRepository(EmployeeRequests::class);
        $entity = $employeeRequests->findOneBy(['id' => $id, 'status' => 0]);
        if (empty($entity)) {
            return new JsonResponse(['message' => 'Not found vacation request for approve'], 400);
        }

        $leftVacationDays = $this->getDoctrine()->getRepository(Employee::class)->getLeftVacationsDays(
            date('Y'),
            $entity->getEmployeeId()
        );

        if ($leftVacationDays <= 0) {
            return new JsonResponse(['message' => 'You used all vacation days'], 400);
        }

        if ($entity->getBookedVacationDays() > $leftVacationDays) {
            return new JsonResponse([
                'message' => sprintf('You have only %s days for vacation', $leftVacationDays)
            ], 400);
        }

        $entity->setStatus(1);
        $entity->setManagerId($managerId);

        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();

        return new JsonResponse();
    }

    /**
     * @Route("/api/vacation_requests/new", methods={"PUT"})
     */
    public function new(Request $request): JsonResponse
    {
        $employeeId = (int)$request->get('employee_id');
        $startDate = new \DateTime($request->get('start_date'));
        $endDate = new \DateTime($request->get('end_date'));
        $year = $request->get('year', date('Y'));

        $employeeRepository = $this->getDoctrine()->getRepository(Employee::class);
        $employee = $employeeRepository->find($employeeId);
        if (empty($employee)) {
            return new JsonResponse(['message' => 'Employee not found'], 400);
        }

        $leftVacationDays = $employeeRepository->getLeftVacationsDays($year, $employeeId);
        if ($leftVacationDays <= 0) {
            return new JsonResponse(['message' => 'You used all vacation days'], 400);
        }

        $entity = new EmployeeRequests();
        $entity->setEmployeeId($employeeId);
        $entity->setVacationStartDate($startDate);
        $entity->setVacationEndDate($endDate);

        //TODO: Need add checking for New Year period. But, not this time :)
        $bookedVacationDays = $entity->getBookedVacationDays();
        if ($bookedVacationDays > $leftVacationDays) {
            return new JsonResponse([
                'message' => sprintf('You have only %s days for vacation', $leftVacationDays)
            ], 400);
        }
        $entity->setVacationDays($bookedVacationDays);

        //TODO: Add checking for duplicate period
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();
        $em->refresh($entity);

        return new JsonResponse(['id' => $entity->getId()]);
    }
}