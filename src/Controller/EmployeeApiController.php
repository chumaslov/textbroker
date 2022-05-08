<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\EmployeeRequests;
use App\Entity\EmployeeVacations;
use App\Entity\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class EmployeeApiController extends AbstractController
{
    /**
     * @Route("/api/employee/info/{id}/{year}", methods={"GET"})
     */
    public function info(int $id, int $year): JsonResponse
    {
        $employeeRepository = $this->getDoctrine()->getRepository(Employee::class);

        $employee = $employeeRepository->find($id);
        if (empty($employee)) {
            return new JsonResponse(['message' => 'Employee not found'], 400);
        }

        $leftVacationDays = $employeeRepository->getLeftVacationsDays($year, $id);

        //Get total vacations
        $ev = $this->getDoctrine()->getRepository(EmployeeVacations::class);
        $entity = $ev->findOneBy([
            'employee_id' => $id,
            'year' => $year,
        ]);

        return new JsonResponse([
            'Name' => $employee->getName(),
            'VacationsLeft' => $leftVacationDays,
            'VacationsTotal' => (int)$entity->getDays(),
        ]);
    }
}