<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Category[]
     */
    public function findByParams(ParamFetcherInterface $paramFetcher): array
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.name', $paramFetcher->get('order'))
            ->setFirstResult(($paramFetcher->get('page') - 1) * $paramFetcher->get('limit'))
            ->setMaxResults($paramFetcher->get('limit'));

        return $qb
            ->getQuery()
            ->getResult();
    }
}
