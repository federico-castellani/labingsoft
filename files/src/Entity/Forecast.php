<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\ShortWeatherDescription;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'forecasts')]
#[ORM\UniqueConstraint('unique_forecast_by_location_and_day', columns: ['location_id', 'day'])]
class Forecast
{
    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(name: 'id', type: 'integer')]
    /** @phpstan-ignore property.onlyRead */
    private int $id;

    #[ORM\Column(name: 'day', type: 'datetimetz_immutable', nullable: false)]
    private \DateTimeImmutable $day;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: 'forecasts')]
    #[ORM\JoinColumn(name: 'location_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Location $location;

    #[ORM\Column(name: 'short_description', type: 'string', nullable: false, enumType: ShortWeatherDescription::class)]
    private ShortWeatherDescription $shortDescription;

    #[ORM\Column(name: 'minimum_celsius_temperature', type: 'integer', nullable: true)]
    private ?int $minimumCelsiusTemperature = null;

    #[ORM\Column(name: 'maximum_celsius_temperature', type: 'integer', nullable: true)]
    private ?int $maximumCelsiusTemperature = null;

    #[ORM\Column(name: 'wind_speed_kmh', type: 'integer', nullable: true)]
    private ?int $windSpeedKmh = null;

    #[ORM\Column(name: 'humidity_percentage', type: 'decimal', precision: 3, scale: 2, nullable: true)]
    private ?string $humidityPercentage;

    public function __construct(Location $location, \DateTimeImmutable $day, ShortWeatherDescription $shortDescription)
    {
        $this->location = $location;
        $this->day = $day;
        $this->shortDescription = $shortDescription;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDay(): \DateTimeImmutable
    {
        return $this->day;
    }

    public function setDay(\DateTimeImmutable $day): void
    {
        $this->day = $day;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): void
    {
        $this->location = $location;
    }

    public function getShortDescription(): ShortWeatherDescription
    {
        return $this->shortDescription;
    }

    public function setShortDescription(ShortWeatherDescription $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    public function getMinimumCelsiusTemperature(): ?int
    {
        return $this->minimumCelsiusTemperature;
    }

    public function setMinimumCelsiusTemperature(?int $minimumCelsiusTemperature): void
    {
        $this->minimumCelsiusTemperature = $minimumCelsiusTemperature;
    }

    public function getMaximumCelsiusTemperature(): ?int
    {
        return $this->maximumCelsiusTemperature;
    }

    public function setMaximumCelsiusTemperature(?int $maximumCelsiusTemperature): void
    {
        $this->maximumCelsiusTemperature = $maximumCelsiusTemperature;
    }

    public function getWindSpeedKmh(): ?int
    {
        return $this->windSpeedKmh;
    }

    public function setWindSpeedKmh(?int $windSpeedKmh): void
    {
        $this->windSpeedKmh = $windSpeedKmh;
    }

    public function getHumidityPercentage(): ?string
    {
        return $this->humidityPercentage;
    }

    public function setHumidityPercentage(?string $humidityPercentage): void
    {
        $this->humidityPercentage = $humidityPercentage;
    }
}
