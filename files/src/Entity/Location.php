<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'locations')]
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

    public function __construct(string $name, string $country)
    {
        $this->name = $name;
        $this->country = $country;
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
        $this->name = $name;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): void
    {
        $this->longitude = $longitude;
    }
}
