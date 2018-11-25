<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return User[]
     */
    public function findByParams(ParamFetcherInterface $paramFetcher): array
    {
        $qb = $this->createQueryBuilder('u')
            ->orderBy('u.id', $paramFetcher->get('order'))
            ->setFirstResult(($paramFetcher->get('page') - 1) * $paramFetcher->get('limit'))
            ->setMaxResults($paramFetcher->get('limit'));

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * @return User[]
     */
    public function findInactiveUsers(): array
    {
        $qb = $this->createQueryBuilder('u')
            ->where('u.enabled = :status')
            ->setParameter('status', false);

        return $qb
            ->getQuery()
            ->getResult();
    }
}
