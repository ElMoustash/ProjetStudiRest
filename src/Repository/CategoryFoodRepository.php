<?php

namespace App\Repository;

use App\Entity\CategoryFood;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryFood>
 *
 * @method CategoryFood|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryFood|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryFood[]    findAll()
 * @method CategoryFood[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryFoodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryFood::class);
    }

    //    /**
    //     * @return CategoryFood[] Returns an array of CategoryFood objects
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

    //    public function findOneBySomeField($value): ?CategoryFood
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
