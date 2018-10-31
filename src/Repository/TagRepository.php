<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Tag[]
     */
    public function findByParams(ParamFetcherInterface $paramFetcher): array
    {
        $qb = $this->createQueryBuilder('t')
            ->orderBy('t.name', $paramFetcher->get('order'))
            ->setFirstResult(($paramFetcher->get('page') - 1) * $paramFetcher->get('limit'))
            ->setMaxResults($paramFetcher->get('limit'));

        return $qb
            ->getQuery()
            ->getResult();
    }
}
