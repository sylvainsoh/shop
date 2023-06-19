<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\SearchProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Product[]
     */
    public function findWithSearch(SearchProduct $search): array
    {
        $query= $this->createQueryBuilder('p');
        if ($search->getMinPrice()){
            $query=$query->andWhere('p.price > '.$search->getMinPrice()*100);
        }
        if ($search->getMaxPrice()){
            $query=$query->andWhere('p.price < '.$search->getMaxPrice()*100);
        }
        if ($search->getTags()){
            $query=$query->andWhere('p.tags like :tags')
                ->setParameter('tags', "%{$search->getTags()}%");
        }
        if ($search->getCategories()){
            $query=$query->join('p.category', 'c')
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->getCategories());
        }

        return $query->getQuery()->getResult();
    }

//    /**
//     * @return Product[] Returns an array of Product objects
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

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
