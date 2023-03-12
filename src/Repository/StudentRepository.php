<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Student>
 *
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function save(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Student $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function getCount()
    {

        $qb = $this->createQueryBuilder('i');
        $qb
            ->select('count(distinct(i.idNumber) ) as item')
            ;
        return $qb->getQuery()->getSingleScalarResult();
    }
    public function filter($search=null)
    {
        $qb=$this->createQueryBuilder('s')
        ->leftJoin('s.studentRegistrations','r')
        ;
        if (isset($search['name'])) {

            $names = explode(" ", $search['name']);
            if (sizeof($names) == 3) {

                $qb->andWhere('s.firstName = :fname')
                    ->setParameter('fname', $names[0])

                    ->andWhere('s.middleName = :mname')
                    ->setParameter('mname', $names[1])
                    ->andWhere('s.lastName = :lname')
                    ->setParameter('lname', $names[2]);
            } else if (sizeof($names) == 2) {

                $qb->andWhere('s.firstName = :fname')
                    ->setParameter('fname', $names[0])

                    ->andWhere('s.middleName = :mname')
                    ->setParameter('mname', $names[1]);
            } else if (sizeof($names) == 1) {

                $qb->orWhere("s.firstName LIKE '%" . $names[0] . "%' or s.middleName LIKE '%" . $names[0] . "%' or s.lastName LIKE '%" . $names[0] . "%' or s.idNumber LIKE '%" . $names[0] . "%' ");
            }
        }

        if (isset($search['gender'])) {
            $qb->andWhere('s.sex = :gnd')
                ->setParameter('gnd', $search['gender']);
        }
        if (isset($search['not-registered'])) {
            
            $qb
            
            ->andWhere(' r.id  is null ')
                // ->setParameter('cnt', 0)
                ;
        }
        if (isset($search['grade'])) {

            $qb->andWhere('s.class = :grd')
            ->setParameter('grd',$search['grade']);
        }
        if (isset($search['year'])) {

            $qb->andWhere('r.year = :yr')
            ->setParameter('yr',$search['year']);
        }
            return 
            $qb
            // ->orderBy('s.id', 'ASC')
            ->orderBy('s.firstName','ASC')
            ->getQuery()
     
        ;
    }
//    /**
//     * @return Student[] Returns an array of Student objects
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

//    public function findOneBySomeField($value): ?Student
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
