<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Location;
use App\Enum\ShortWeatherDescription;
use Symfony\Component\Validator\Constraints as Assert;

class ForecastDTO
{
    #[Assert\NotNull()]
    public ?Location $location = null;

    #[Assert\NotNull()]
    public ?\DateTimeImmutable $day = null;

    #[Assert\NotNull()]
    public ?ShortWeatherDescription $shortWeatherDescription = null;

    #[Assert\GreaterThanOrEqual(0)]
    public ?int $windSpeedKmh = null;

    #[Assert\GreaterThanOrEqual(0)]
    #[Assert\LessThanOrEqual(1)]
    public ?string $humidityPercentage = null;

    #[Assert\Valid]
    public ?TemperatureSpanDTO $temperatureSpan;

    public function __construct()
    {
        $this->temperatureSpan = new TemperatureSpanDTO();
    }
}
