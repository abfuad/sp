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
public function filter($search=null,$year=null)
{
    $qb=$this->createQueryBuilder('s')
    ->join('s.payments','p')
    ->join('p.priceSetting','pr')
    ->join('s.student','st')
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

        $qb->andWhere('pr.year = :yr')
        ->setParameter('yr',$search['year']);
    }
    else{
        $qb->andWhere('pr.year = :yr')
        ->setParameter('yr',$year);
    }
    if (isset($search['student'])) {

        $qb->andWhere('s.student = :st')
        ->setParameter('st',$search['student']);
    }
    if (isset($search['month'])) {

        $qb->andWhere('p.month = :mon')
        ->setParameter('mon',$search['month']);
    }
    if (isset($search['status'])) {

        $qb->andWhere('p.isPaid = :pad')
        ->setParameter('pad',$search['status']);
    }
        return 
        $qb->orderBy('s.id', 'ASC')
        ->getQuery()
 
    ;
}
}
