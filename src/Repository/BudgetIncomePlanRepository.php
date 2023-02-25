<?php

namespace App\Repository;

use App\Entity\BudgetIncomePlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BudgetIncomePlan>
 *
 * @method BudgetIncomePlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method BudgetIncomePlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method BudgetIncomePlan[]    findAll()
 * @method BudgetIncomePlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetIncomePlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudgetIncomePlan::class);
    }

    public function save(BudgetIncomePlan $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BudgetIncomePlan $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function filter($search=null)
    {
        $qb=$this->createQueryBuilder('s');
        if($search)
            $qb->andWhere("s.name  LIKE '%".$search."%'");
    
            return 
            $qb->orderBy('s.id', 'ASC')
            ->getQuery()
     
        ;
    }
//    /**
//     * @return BudgetIncomePlan[] Returns an array of BudgetIncomePlan objects
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

//    public function findOneBySomeField($value): ?BudgetIncomePlan
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
