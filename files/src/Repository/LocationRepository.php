<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Location;
use Assert\Assertion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Location> */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Location::class);
    }

    /**
     * @deprecated
     *
     * @return Location[]
     */
    public function findAllWithForecasts(): array
    {
        return $this->createQueryBuilder('l')
            ->leftJoin('l.forecasts', 'f')
            ->addSelect('f')
            ->getQuery()
            ->getResult();
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

    /**
     * @return Location[]
     */
    public function findPaginated(int $page, int $pageSize): array
    {
        Assertion::greaterOrEqualThan($page, 1);
        Assertion::greaterOrEqualThan($pageSize, 1);

        return $this->createQueryBuilder('l')
            ->setMaxResults($pageSize)
            ->setFirstResult(($page - 1) * $pageSize) // Convert from 1-based page number, to 0-based page number
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Location[] $locations
     */
    public function prefetchForecasts(array $locations): void
    {
        $this->createQueryBuilder('l')
            ->select('partial l.{id}')
            ->leftJoin('l.forecasts', 'f')
            ->addSelect('f')
            ->where('l.id in (:locations)')
            ->setParameter('locations', $locations)
            ->getQuery()
            ->getResult();
    }
}
