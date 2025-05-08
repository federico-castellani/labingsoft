<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LocationRepository;
use Assert\Assertion;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ORM\Table(name: 'locations')]
#[ORM\UniqueConstraint('unique_location_by_country_and_name', columns: ['country', 'name'])]
class Location
{
    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(name: 'id', type: 'integer')]
    private int $id;

    #[ORM\Column(name: 'name', type: 'string')]
    private string $name;

    #[ORM\Column(name: 'country', type: 'string', length: 2)]
    private string $country;

    #[ORM\Column(name: 'latitude', type: 'decimal', precision: 10, scale: 8, nullable: true)]
    private ?string $latitude = null;

    #[ORM\Column(name: 'longitude', type: 'decimal', precision: 11, scale: 8, nullable: true)]
    private ?string $longitude = null;

    /**
     * @var Collection<int, Forecast>
     */
    #[ORM\OneToMany(targetEntity: Forecast::class, mappedBy: 'location')]
    private Collection $forecasts;

    public function __construct(string $name, string $country)
    {
        $this->name = mb_strtolower($name);
        $this->country = mb_strtolower($country);
        Assertion::length($country, 2, 'Country must be two characters long');
        $this->forecasts = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = mb_strtolower($name);
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        Assertion::length($country, 2, 'Country must be two characters long');
        $this->country = mb_strtolower($country);
    }

    // Here we can also return a collection, but it should not be THE SAME collection, or we might end up modifying the
    // contents of the collection from outside the object, which is something that usually you don't want to do because
    // it breaks encapsulation and can lead to unintended changes.
    /**
     * @return Forecast[]
     */
    public function getForecasts(): array
    {
        return $this->forecasts->toArray();
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): void
    {
        Assertion::nullOrBetween($latitude, -90, 90, 'Latitude must be between -90 and +90');
        $this->latitude = $latitude;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): void
    {
        Assertion::nullOrBetween($longitude, -180, 180, 'Longitude must be between -180 and +180');
        $this->longitude = $longitude;
    }
}
