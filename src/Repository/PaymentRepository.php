<?php

namespace App\Repository;

use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payment>
 *
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    public function save(Payment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Payment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function filter($search=[],$year=null)
    {
        $qb=$this->createQueryBuilder('s')
        ->join('s.registration','r')
        ->join('s.student','st')
        ;
        if (isset($search['name'])) {

     
            $names =  $search['name'];
            
                $qb->orWhere("st.firstName LIKE '%" . $names. "%' or st.idNumber LIKE '%" . $names . "%' or st.middleName LIKE '%" . $names . "%' or st.lastName LIKE '%" . $names . "%' ");
            
        }
        if (isset($search['grade'])) {

            $qb->andWhere('r.grade = :grd')
            ->setParameter('grd',$search['grade']);
        }
        if (isset($search['year'])) {
    
            $qb->andWhere('r.year = :yr')
            ->setParameter('yr',$search['year']);
        }
        elseif(isset($search['year'])==false && $year!=null){

            $qb->andWhere('r.year = :yr1')
            ->setParameter('yr1',$year);
        }
        if (isset($search['student'])) {
    
            $qb->andWhere('r.student = :st')
            ->setParameter('st',$search['student']);
        }
        if (isset($search['month'])) {
    
            $qb->andWhere('s.month = :mon')
            ->setParameter('mon',$search['month']);
        }
        if (isset($search['status'])) {
    
            $qb->andWhere('s.isPaid = :pad')
            ->setParameter('pad',$search['status']);
        }
            return 
            $qb->orderBy('s.id', 'ASC')
            ->getQuery()
     
        ;
    }

//    /**
//     * @return Payment[] Returns an array of Payment objects
//     */
   public function getLatest(): array
   {
       return $this->createQueryBuilder('p')
           ->andWhere('p.isPaid = 1')
        //    ->setParameter('val', $value)
           ->orderBy('p.id', 'DESC')
           ->setMaxResults(5)
           ->getQuery()
           ->getResult()
       ;
   }

//    public function findOneBySomeField($value): ?Payment
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
