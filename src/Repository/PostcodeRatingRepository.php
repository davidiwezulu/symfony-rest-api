<?php

namespace App\Repository;

use App\Entity\PostcodeRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AbiCodeRating|null find($postcodeArea, $lockMode = null, $lockVersion = null)
 * @method AbiCodeRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbiCodeRating[]    findAll()
 * @method AbiCodeRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostcodeRatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostcodeRating::class);
    }

    /**
     * @param $value
     * @return mixed
     */
    public function findByPostcodeArea($value)
    {
        $value  = $this->fetchPostcodeZone($value);
        return $this->createQueryBuilder('a')
            ->andWhere('a.postcodeArea  = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $value
     * @return PostcodeRating|null
     */
    public function findOneByRatingFactor($value): ?PostcodeRating
    {
        $value  = $this->fetchPostcodeZone($value);
        return $this->createQueryBuilder('a')
            ->andWhere('a.postcodeArea  LIKE :val')
            ->setParameter('val', "$value%")
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param $postcode
     * @return bool|string
     */
    public function fetchPostcodeZone($postcode) {
        if (($posOfSpace = stripos($postcode," ")) !== false) return substr($postcode,0,$posOfSpace);

        // Deal with the format BS000
        if (strlen($postcode) < 5) return $postcode;

        $shortened = substr($postcode,0,5);
        if ((string)(int)substr($shortened,4,1) === (string)substr($shortened,4,1)) {
            // BS000. Strip one and return
            return substr($shortened,0,4);
        }
        else {
            if ((string)(int)substr($shortened,3,1) === (string)substr($shortened,3,1)) {
                return substr($shortened,0,3);
            }
            else return substr($shortened,0,2);
        }
    }

}
