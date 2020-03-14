<?php

namespace App\Repository;

use App\Entity\AbiCodeRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AbiCodeRating|null find($abiCode, $lockMode = null, $lockVersion = null)
 * @method AbiCodeRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbiCodeRating[]    findAll()
 * @method AbiCodeRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbiCodeRatingRepository extends ServiceEntityRepository
{
    /**
     * AbiCodeRatingRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbiCodeRating::class);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function findByAbiCode($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.abi_code  = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $value
     * @return AbiCodeRating|null
     */
    public function findOneByAbiCode($value): ?AbiCodeRating
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.abi_code  = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
