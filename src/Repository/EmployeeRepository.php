<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\EmployeeVacations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function getLeftVacationsDays($year, $employeeId): int
    {
        $ev = $this->getEntityManager()->getRepository(EmployeeVacations::class);
        $entity = $ev->findOneBy([
            'employee_id' => $employeeId,
            'year' => $year,
        ]);

        $er = $this->getEntityManager()->createQueryBuilder();
        $usedDays = $er->select('SUM(err.vacation_days) as days')
            ->from('App\Entity\EmployeeRequests', 'err')
            ->andWhere('err.employee_id = :id')
            ->andWhere('err.status = 1')
            ->setParameter('id', $employeeId)
            ->getQuery()
            ->getOneOrNullResult();

        return (int)$entity->getDays() - (int)$usedDays['days'];
    }

    // /**
    //  * @return Employee[] Returns an array of Employee objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Employee
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
