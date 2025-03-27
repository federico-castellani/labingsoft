<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'forecasts')]
class Forecast
{
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    #[ORM\Column(name: 'id', type: 'integer')]
    /** @phpstan-ignore property.onlyRead */
    private int $id;
    #[ORM\Column(name: 'name', type: 'datetimetz_immutable')]
    private \DateTimeImmutable $date;
    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'forecasts')]
    #[ORM\JoinColumn(name: 'location_id', referencedColumnName: 'id', nullable: false,onDelete: 'CASCADE')]
    private Location $location;
    #[ORM\Column(name: 'short_description', type: 'string')]
    private string $shortDescription;
    #[ORM\Column(name: 'wind_speed_kmh', type: 'integer', nullable: true)]
    private ?int $windSpeedKmh = null;
    #[ORM\Column(name: 'min_temp', type: 'integer', nullable: true)]
    private ?int $minTemp = null;
    #[ORM\Column(name: 'max_temp', type: 'integer', nullable: true)]
    private ?int $maxTemp = null;
    #[ORM\Column(name: 'humidity_percentage', type: 'integer', nullable: true)]
    private ?int $humidityPercentage = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }

    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    public function getWindSpeedKmh(): ?int
    {
        return $this->windSpeedKmh;
    }

    public function setWindSpeedKmh(?int $windSpeedKmh): void
    {
        $this->windSpeedKmh = $windSpeedKmh;
    }

    public function getMinTemp(): ?int
    {
        return $this->minTemp;
    }

    public function setMinTemp(?int $minTemp): void
    {
        $this->minTemp = $minTemp;
    }

    public function getMaxTemp(): ?int
    {
        return $this->maxTemp;
    }

    public function setMaxTemp(?int $maxTemp): void
    {
        $this->maxTemp = $maxTemp;
    }

    public function getHumidityPercentage(): ?int
    {
        return $this->humidityPercentage;
    }

    public function setHumidityPercentage(?int $humidityPercentage): void
    {
        $this->humidityPercentage = $humidityPercentage;
    }

    public function __construct(
        \DateTimeImmutable $date,
        Location $location,
        string $shortDescription,
    ) {
        $this->date = $date;
        $this->location = $location;
        $this->shortDescription = $shortDescription;
    }

}