<?php

namespace App\Repository;

use App\Entity\SpecialIncome;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpecialIncome>
 *
 * @method SpecialIncome|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpecialIncome|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpecialIncome[]    findAll()
 * @method SpecialIncome[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialIncomeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpecialIncome::class);
    }

    public function save(SpecialIncome $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SpecialIncome $entity, bool $flush = false): void
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
            $qb->orderBy('s.id', 'DESC')
            ->getQuery()
     
        ;
    }

//    /**
//     * @return SpecialIncome[] Returns an array of SpecialIncome objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SpecialIncome
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
