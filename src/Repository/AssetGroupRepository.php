<?php

namespace App\Repository;

use App\Entity\AssetGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AssetGroup>
 *
 * @method AssetGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssetGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssetGroup[]    findAll()
 * @method AssetGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssetGroup::class);
    }

    public function save(AssetGroup $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AssetGroup $entity, bool $flush = false): void
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
           
            return 
            $qb->orderBy('s.id', 'ASC')
            ->getQuery()
     
        ;
    }
//    /**
//     * @return AssetGroup[] Returns an array of AssetGroup objects
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

//    public function findOneBySomeField($value): ?AssetGroup
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
