<?php

namespace App\Repository;

use App\Entity\UserInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserInfo>
 *
 * @method UserInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserInfo[]    findAll()
 * @method UserInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserInfo::class);
    }

    public function save(UserInfo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserInfo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findUser($search = [])
    {
        // dd($search);
        // $em = $this->getEntityManager();
        $qb = $this->createQueryBuilder('u');
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
            $qb->join('u.user', 'us')
                ->andWhere('us.isActive = :isA')
                ->setParameter('isA', $search['status']);
        }
        if (isset($search['passwordReset'])) {
            $qb->join('u.user', 'us')
                ->andWhere("us.confirmToken = '9ARySUtDRRko0x7aMxrO9qM'");
        }

        return $qb->orderBy('u.id', 'ASC')
            ->getQuery();
    }

//    /**
//     * @return UserInfo[] Returns an array of UserInfo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserInfo
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
