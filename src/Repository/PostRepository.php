<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Post[]
     */
    public function findByParams(ParamFetcherInterface $paramFetcher): array
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.id', $paramFetcher->get('order'))
            ->setFirstResult(($paramFetcher->get('page') - 1) * $paramFetcher->get('limit'))
            ->setMaxResults($paramFetcher->get('limit'));

        if (false === empty($paramFetcher->get('query'))) {
            $expr = $qb->expr()->orX(
                'LOWER(p.title) LIKE :query',
                'LOWER(p.article) LIKE :query'
            );
            $qb
                ->andWhere($expr)
                ->setParameter('query', sprintf("%%%s%%", mb_strtolower($paramFetcher->get('query'))));
        }

        if (null !== $paramFetcher->get('user')) {
            $qb
                ->andWhere('p.user in (:user)')
                ->setParameter('user', $paramFetcher->get('user'));
        }

        if (null !== $paramFetcher->get('category')) {
            $qb
                ->andWhere('p.category in (:category)')
                ->setParameter('category', $paramFetcher->get('category'));
        }

        if (null !== $paramFetcher->get('tags')) {
            $qb
                ->leftJoin('p.tags', 't')
                ->andWhere('t.id in (:tags)')
                ->setParameter('tags', $paramFetcher->get('tags'));
        }

        return $qb
            ->getQuery()
            ->getResult();
    }
}
