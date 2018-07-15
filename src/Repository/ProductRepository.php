<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }


    /**
     * @return mixed
     */
    public function findNewProducts($max)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    public function findAllProducts()
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

//    public function searchBy($searchstring)
//    {
//        return $this->createQueryBuilder('a')
//            ->where('a.title  LIKE :param')
//            ->orWhere('a.description  LIKE :param')
//            ->orWhere('a.content  LIKE :param')
//            ->setParameter('param', '%' . $searchstring .'%')
//            ->getQuery()
//            ->getResult();
//    }
//
//    /**
//     * @param $price
//     * @return Product[]
//     */
//    public function findAllGreaterThanPrice($price): array
//    {
//        $qb = $this->createQueryBuilder('p')
//            ->andWhere('p.price > :price')
//            ->setParameter('price', $price)
//            ->orderBy('p.price', 'ASC')
//            ->getQuery();
//
//        return $qb->execute();
//
//        // чтобы получить только один результат:
////         $product = $qb->setMaxResults(1)->getOneOrNullResult();
//    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

}
