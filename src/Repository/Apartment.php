<?php

namespace App\Repository;

use App\Entity\Apartment as ApartmentEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ApartmentEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApartmentEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApartmentEntity[]    findAll()
 * @method ApartmentEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Apartment extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ApartmentEntity::class);
    }

    /**
     * @return array
     */
    public function getAllApartments()
    {
        return $this->createQueryBuilder('a')
            ->getQuery()
            ->getArrayResult()
        ;
    }
}
