<?php

namespace App\Repository;

use App\Entity\PaymentMonth;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaymentMonth>
 *
 * @method PaymentMonth|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentMonth|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentMonth[]    findAll()
 * @method PaymentMonth[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentMonthRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentMonth::class);
    }

    public function save(PaymentMonth $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PaymentMonth $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PaymentMonth[] Returns an array of PaymentMonth objects
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

//    public function findOneBySomeField($value): ?PaymentMonth
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
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
}
