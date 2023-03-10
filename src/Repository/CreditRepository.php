<?php

namespace App\Repository;

use App\Entity\Credit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Credit>
 *
 * @method Credit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Credit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Credit[]    findAll()
 * @method Credit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreditRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Credit::class);
    }

    public function save(Credit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Credit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findCredit($search = [])
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
        if (isset($search['status'])) {
            $qb
                ->andWhere('c.status = :isA')
                ->setParameter('isA', $search['status']);
        }
      

        return $qb->orderBy('c.id', 'ASC')
            ->getQuery();
    }
//    /**
//     * @return Credit[] Returns an array of Credit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Credit
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
