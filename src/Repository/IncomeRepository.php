<?php

namespace App\Repository;

use App\Entity\Income;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Income>
 *
 * @method Income|null find($id, $lockMode = null, $lockVersion = null)
 * @method Income|null findOneBy(array $criteria, array $orderBy = null)
 * @method Income[]    findAll()
 * @method Income[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncomeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Income::class);
    }

    public function save(Income $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Income $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
 
//    /**
//     * @return Income[] Returns an array of Income objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Income
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

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
    if (isset($search['status'])) {
         $status=$search['status'];
        
         if($status==0)
            $qb->andWhere('s.receiptNumber is null');
            else
            $qb->andWhere('s.receiptNumber is not NULL');

         
       
    }
    if (isset($search['type'])) {

        $qb->andWhere('s.type = :mon')
        ->setParameter('mon',$search['type']);
    }
    if (isset($search['isfree'])) {

        $qb->andWhere('r.isFree = :pad')
        ->setParameter('pad',$search['isfree']);
    }
        return 
        $qb->orderBy('s.id', 'ASC')
        ->getQuery()
 
    ;
}
}
