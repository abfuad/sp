<?php

namespace App\Repository;

use App\Entity\PaymentYear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaymentYear>
 *
 * @method PaymentYear|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentYear|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentYear[]    findAll()
 * @method PaymentYear[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentYearRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentYear::class);
    }

    public function save(PaymentYear $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PaymentYear $entity, bool $flush = false): void
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
//     * @return PaymentYear[] Returns an array of PaymentYear objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PaymentYear
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
