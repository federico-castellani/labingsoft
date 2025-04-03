<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Location> */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Location::class);
    }

    public function findByCountryAndName(string $country, string $name): ?Location
    {
        $result = $this->createQueryBuilder('l')
            ->where('l.country = :country')
            ->andWhere('l.name = :name')
            ->setParameter('country', mb_strtolower($country))
            ->setParameter('name', mb_strtolower($name))
            ->getQuery()
            ->getResult();

        return reset($result) ?: null;
    }
}
