<?php

namespace App\Repository;

use App\Entity\BasePremium;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BasePremium|null find($id, $lockMode = null, $lockVersion = null)
 * @method BasePremium|null findOneBy(array $criteria, array $orderBy = null)
 * @method BasePremium[]    findAll()
 * @method BasePremium[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BasePremiumRepository extends ServiceEntityRepository
{
    /**
     * BasePremiumRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BasePremium::class);
    }

    /**
     * @return mixed
     */
    public function findFirstPremium()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
