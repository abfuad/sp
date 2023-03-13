<?php

namespace App\Repository;

use App\Entity\Asset;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Asset>
 *
 * @method Asset|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asset|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asset[]    findAll()
 * @method Asset[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asset::class);
    }

    public function save(Asset $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Asset $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function filter($search=null)
    {
        $qb=$this->createQueryBuilder('s')
        ->join('s.category','c')
        ;
        if(isset($search['name']))
            $qb->andWhere("c.name  LIKE '%".$search."%' ");
        if(isset($search['category'])){
            $qb->andWhere('s.category = :gnd')
            ->setParameter('gnd', $search['category']);
        }
     
           
            return 
            $qb->orderBy('s.id', 'ASC')
            ->getQuery()
     
        ;
    }
    public function getTotalAsset($category)
    {
        $qb=$this->createQueryBuilder('s')
        ->select('sum(s.quantity)')
        ;
      
     
            $qb->andWhere('s.category = :gnd')
            ->setParameter('gnd', $category);
    
     
           
            return 
            $qb
            // ->groupBy('s.category')
            ->getQuery()
            ->getSingleScalarResult()
     
        ;
    }

//    /**
//     * @return Asset[] Returns an array of Asset objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Asset
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
