<?php

namespace App\Repository;

use App\Entity\AgeRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AbiCodeRating|null find($age, $lockMode = null, $lockVersion = null)
 * @method AbiCodeRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbiCodeRating[]    findAll()
 * @method AbiCodeRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgeRatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgeRating::class);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function findByAbiCode($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.age  = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $value
     * @return AgeRating|null
     */
    public function findOneByRatingFactor($value): ?AgeRating
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.age  = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
