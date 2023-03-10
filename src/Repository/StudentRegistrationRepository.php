<?php

namespace App\Repository;

use App\Entity\StudentRegistration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StudentRegistration>
 *
 * @method StudentRegistration|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentRegistration|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentRegistration[]    findAll()
 * @method StudentRegistration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentRegistration::class);
    }

    public function save(StudentRegistration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(StudentRegistration $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return StudentRegistration[] Returns an array of StudentRegistration objects
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

//    public function findOneBySomeField($value): ?StudentRegistration
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function filter($search=[],$year=null)
{
   // $search['year']=    $search['year']?:$year;
    $qb=$this->createQueryBuilder('s')
   
    ->join('s.student','st')
    ->join('s.grade','g')
    ;
    if (isset($search['name'])) {

     
        $names =  $search['name'];
        
            $qb->orWhere("st.firstName LIKE '%" . $names. "%' or st.idNumber LIKE '%" . $names . "%' or st.middleName LIKE '%" . $names . "%' or st.lastName LIKE '%" . $names . "%' ");
        
    }
    
    if (isset($search['grade'])) {

        $qb->andWhere('s.grade = :grd')
        ->setParameter('grd',$search['grade']);
    }
    if (isset($search['year'])) {

        $qb->andWhere('s.year = :yr')
        ->setParameter('yr',$search['year']);
    }
    
    if (isset($search['student'])) {

        $qb->andWhere('s.student = :st')
        ->setParameter('st',$search['student']);
    }
    if (isset($search['isfree'])) {

        $qb->andWhere('s.isFree = :fre')
        ->setParameter('fre',$search['isfree']);
    }
    if (isset($search['status'])) {

        $qb->andWhere('s.isCompleted = :pad')
        ->setParameter('pad',$search['status']);
    }
    else{
        $qb->andWhere('s.isCompleted = :fas')
        ->setParameter('fas',false);
    }

        return 
        $qb->orderBy('g.code')
        ->addOrderBy('st.firstName','ASC')
        ->addOrderBy('st.middleName','ASC')
        ->addOrderBy('st.lastName','ASC')


        ->getQuery()
 
    ;
}
}
