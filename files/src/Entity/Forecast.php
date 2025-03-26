<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\ShortWeatherDescription;
use App\ValueObject\TemperatureSpan;
use Assert\Assertion;
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
        // We do not want two forecasts for the same day but different time and neither PHP nor Doctrine nor postgres
        // have such a type. The simple solution is to zero-out the time. We could also create our own value object for
        // date-without-time.
        $this->day = $day->setTime(0, 0, 0);
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
        // We do not want two forecasts for the same day but different time and neither PHP nor Doctrine nor postgres
        // have such a type. The simple solution is to zero-out the time. We could also create our own value object for
        // date-without-time.
        $this->day = $day->setTime(0, 0, 0);
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

    /**
     * @deprecated This method is here just for illustration purposes
     */
    public function setCelsiusTemperature(
        ?int $minimumCelsiusTemperature = null,
        ?int $maximumCelsiusTemperature = null
    ): void {
        Assertion::true(
            !($minimumCelsiusTemperature === null xor $minimumCelsiusTemperature === null),
            'Both temperature must be specified or null at the same time'
        );
        Assertion::nullOrLessOrEqualThan(
            $minimumCelsiusTemperature,
            $maximumCelsiusTemperature,
            'Minimum celsius temperature must be not greater than maximum celsius temperature'
        );
        $this->minimumCelsiusTemperature = $minimumCelsiusTemperature;
        $this->maximumCelsiusTemperature = $maximumCelsiusTemperature;
    }

    public function setTemperatureSpan(?TemperatureSpan $temperatureSpan): void
    {
        if (null === $temperatureSpan) {
            $this->minimumCelsiusTemperature = null;
            $this->maximumCelsiusTemperature = null;
            return;
        }
        $this->minimumCelsiusTemperature = $temperatureSpan->getMinimumCelsiusTemperature();
        $this->maximumCelsiusTemperature = $temperatureSpan->getMaximumCelsiusTemperature();
    }

    public function getTemperatureSpan(): ?TemperatureSpan
    {
        if (null === $this->minimumCelsiusTemperature && null === $this->maximumCelsiusTemperature) {
            return null;
        }
        return new TemperatureSpan($this->minimumCelsiusTemperature, $this->maximumCelsiusTemperature);
    }

    /**
     * @deprecated This method is here just for illustration purposes
     */
    public function getMinimumCelsiusTemperature(): ?int
    {
        return $this->minimumCelsiusTemperature;
    }

    /**
     * @deprecated This method is here just for illustration purposes
     */
    public function getMaximumCelsiusTemperature(): ?int
    {
        return $this->maximumCelsiusTemperature;
    }

    public function getWindSpeedKmh(): ?int
    {
        return $this->windSpeedKmh;
    }

    public function setWindSpeedKmh(?int $windSpeedKmh): void
    {
        Assertion::nullOrGreaterOrEqualThan($windSpeedKmh, 0);
        $this->windSpeedKmh = $windSpeedKmh;
    }

    public function getHumidityPercentage(): ?string
    {
        return $this->humidityPercentage;
    }

    public function setHumidityPercentage(?string $humidityPercentage): void
    {
        Assertion::nullOrBetween($humidityPercentage, 0, 1, 'Humidity percentage should be between 0 and 100');
        $this->humidityPercentage = $humidityPercentage;
    }
}
