<?php

namespace App\Controller;

use App\Entity\EmployeeRequests;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class VacationRequestsApiController extends AbstractController
{
    /**
     * @Route("/api/vacation_requests/list/{status}", methods={"GET"})
     */
    public function list(ManagerRegistry $doctrine, string $status): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $employeeRequests = $entityManager->getRepository(EmployeeRequests::class);

        if($status == 'all') {
            $list = $employeeRequests->findAll();
        } else {
            $list = $employeeRequests->findBy(['status' => $status]);
        }

        print_r($list);

        return new JsonResponse($list);
    }

    /**
     * @Route("/api/vacation_requests/change_status/{id}/{status}", methods={"UPDATE"})
     */
    public function changeStatus(int $id, string $status): JsonResponse
    {

    }

    /**
     * @Route("/api/vacation_requests/new/{id}/{status}", methods={"PUT"})
     */
    public function new(): JsonResponse
    {

    }
}