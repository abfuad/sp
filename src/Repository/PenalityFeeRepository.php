<?php

namespace App\Repository;

use App\Entity\PenalityFee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PenalityFee>
 *
 * @method PenalityFee|null find($id, $lockMode = null, $lockVersion = null)
 * @method PenalityFee|null findOneBy(array $criteria, array $orderBy = null)
 * @method PenalityFee[]    findAll()
 * @method PenalityFee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PenalityFeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PenalityFee::class);
    }

    public function save(PenalityFee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PenalityFee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function filter($search = [])
    {
        // dd($search);
        // $em = $this->getEntityManager();
        $qb = $this->createQueryBuilder('c')
        ->join('c.user','us')
        ->join('us.userInfo','u')
        ;
        if (isset($search['name'])) {

            $names = explode(" ", $search['name']);
            if (sizeof($names) == 3) {

                $qb->andWhere('u.firstName = :fname')
                    ->setParameter('fname', $names[0])

                    ->andWhere('u.middleName = :mname')
                    ->setParameter('mname', $names[1])
                    ->andWhere('u.lastName = :lname')
                    ->setParameter('lname', $names[2]);
            } else if (sizeof($names) == 2) {

                $qb->andWhere('u.firstName = :fname')
                    ->setParameter('fname', $names[0])

                    ->andWhere('u.middleName = :mname')
                    ->setParameter('mname', $names[1]);
            } else if (sizeof($names) == 1) {

                $qb->orWhere("u.firstName LIKE '%" . $names[0] . "%' or u.middleName LIKE '%" . $names[0] . "%' or u.lastName LIKE '%" . $names[0] . "%' ");
            }
        }

        if (isset($search['gender'])) {
            $qb->andWhere('u.sex = :gnd')
                ->setParameter('gnd', $search['gender']);
        }
        // if (isset($search['status'])) {
        //     $qb
        //         ->andWhere('c.status = :isA')
        //         ->setParameter('isA', $search['status']);
        // }
      

        return $qb->orderBy('c.id', 'ASC')
            ->getQuery();
    }
//    /**
//     * @return PenalityFee[] Returns an array of PenalityFee objects
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

//    public function findOneBySomeField($value): ?PenalityFee
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
