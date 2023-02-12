<?php

namespace App\Repository;

use App\Entity\BudgetExpensePlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BudgetExpensePlan>
 *
 * @method BudgetExpensePlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method BudgetExpensePlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method BudgetExpensePlan[]    findAll()
 * @method BudgetExpensePlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetExpensePlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudgetExpensePlan::class);
    }

    public function save(BudgetExpensePlan $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BudgetExpensePlan $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return BudgetExpensePlan[] Returns an array of BudgetExpensePlan objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BudgetExpensePlan
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
