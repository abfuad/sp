<?php

namespace App\Repository;

use App\Entity\Expense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Expense>
 *
 * @method Expense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expense[]    findAll()
 * @method Expense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expense::class);
    }

    public function save(Expense $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Expense $entity, bool $flush = false): void
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
    public function expenseReport($search=[])
    {
        $qb=$this->createQueryBuilder('s')
        ->join('s.expensePlan','p')
        ->join('p.budget','b')
        ->select('sum(s.amount) as total')
        ;

        if(isset($search['expensePlan'])){
            $qb->andWhere("s.expensePlan = :plan")
            ->setParameter('plan',$search['expensePlan'])
            ;

        }
        if(isset($search['year'])){
            $qb->andWhere("b.year = :yr")
            ->setParameter('yr',$search['year'])
            ;

        }
        if(isset($search['type'])){
            $qb->andWhere("p.type = :typ")
            ->setParameter('typ',$search['type'])
            ;

        }
    
            return 
            $qb
            ->getQuery()->getSingleScalarResult();
     
        ;
    }
//    /**
//     * @return Expense[] Returns an array of Expense objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Expense
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
