<?php

namespace App\Repository;

use App\Entity\UserCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserCard>
 *
 * @method UserCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCard[]    findAll()
 * @method UserCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCard::class);
    }

    public function save(UserCard $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserCard $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function filter($search=null)
    {
        $qb=$this->createQueryBuilder('u')
        ->join('u.asset','c')
        ;
        if(isset($search['name']))
            $qb->andWhere("c.name  LIKE '%".$search."%' ");
        if(isset($search['category'])){
            $qb->andWhere('u.asset = :gnd')
            ->setParameter('gnd', $search['category']);
        }
        if(isset($search['type'])){
            $qb->andWhere('u.isReturned = :rtn')
            ->setParameter('rtn', $search['type']);
        }
     
           
            return 
            $qb->orderBy('u.id', 'ASC')
            ->getQuery()
     
        ;
    }
    public function getStockOutAsset($category)
    {
        $qb=$this->createQueryBuilder('u')
        
        ->select('sum(u.quantity)')
        ;
      
     
            $qb->andWhere('u.asset = :gnd')
            ->setParameter('gnd', $category);
    if($category->isIsFixed()){

      $qb->andWhere('u.isReturned = 0');
            // ->setParameter('gnd', $category);
    }
     
           
            return 
            $qb
            //->groupBy('u.asset')
            ->getQuery()
            ->getSingleScalarResult()
     
        ;
    }
//    /**
//     * @return UserCard[] Returns an array of UserCard objects
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

//    public function findOneBySomeField($value): ?UserCard
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
