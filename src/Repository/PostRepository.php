<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
     * @param array $params
     *
     * @return array
     */
    public function findByParams(array $params): array
    {
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.id', $params['order'])
            ->setFirstResult($params['offset'])
            ->setMaxResults($params['limit']);


        if (false !== array_key_exists('query', $params)) {
            $expr = $qb->expr()->orX(
                'LOWER(p.title) LIKE :query',
                'LOWER(p.article) LIKE :query'
            );
            $qb
                ->andWhere($expr)
                ->setParameter('query', sprintf("%%%s%%", mb_strtolower($params['query'])));
        }

        if (false !== array_key_exists('user', $params)) {
            $qb
                ->andWhere('p.user in (:user)')
                ->setParameter('user', $params['user']);
        }

        if (false !== array_key_exists('category', $params)) {
            $qb
                ->andWhere('p.category in (:category)')
                ->setParameter('category', $params['category']);
        }

        if (false !== array_key_exists('tags', $params)) {
            $qb
                ->join('p.tags', 't')
                ->andWhere('t.id in (:tags)')
                ->setParameter('tags', $params['tags']);
        }

        return $qb
            ->getQuery()
            ->getResult();
    }
}
