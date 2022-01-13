<?php

namespace App\Repository;

use App\Entity\ProductImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductImage[]    findAll()
 * @method ProductImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductImage::class);
    }

    public function getImagesWithProducts(int $page = 1, int $perPage = 10)
    {
        $qb = $this->createQueryBuilder('pi')
            ->addSelect('p')
            ->join('pi.products', 'p')
            ->orderBy('pi.id', 'ASC')
            ->setMaxResults($perPage)
            ->setFirstResult(($page - 1)*$perPage);

        return $qb->getQuery()->getResult();
    }
}
