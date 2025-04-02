<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Forecast;
use App\Enum\ShortWeatherDescription;
use App\Repository\LocationRepository;
use App\ValueObject\TemperatureSpan;
use Assert\Assertion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:forecast:create', description: 'Creates a new forecast')]
class CreateForecastCommand extends Command
{
    private const ARG_LOCATION_ID = 'locationId';
    private const ARG_DAY = 'day';
    private const ARG_SHORT_WEATHER_DESCRIPTION = 'weatherDescription';
    private const OPT_WIND_SPEED = 'wind-speed';
    private const OPT_HUMIDITY_PERCENTAGE = 'humidity-percentage';
    private const OPT_TEMPERATURE = 'temperature';

    private EntityManagerInterface $entityManager;

    private LocationRepository $locationRepository;

    public function __construct(EntityManagerInterface $entityManager, LocationRepository $locationRepository)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->locationRepository = $locationRepository;
    }

    public function configure(): void
    {
        $this
            ->addArgument(self::ARG_LOCATION_ID, InputArgument::REQUIRED, 'Id of the location this forecast refers to')
            ->addArgument(self::ARG_DAY, InputArgument::REQUIRED, 'Day the forecast refers to in DD-MM-YYYY format')
            ->addArgument(
                self::ARG_SHORT_WEATHER_DESCRIPTION,
                InputArgument::REQUIRED,
                'Short weather description ('
                .join(
                    ', ',
                    array_map(
                        fn (ShortWeatherDescription $shortWeatherDescription) => $shortWeatherDescription->value,
                        ShortWeatherDescription::cases()
                    )
                )
                .')'
            )->addOption(
                self::OPT_TEMPERATURE,
                't',
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Minimum and maximum temperature'
            )->addOption(self::OPT_HUMIDITY_PERCENTAGE, 'm', InputOption::VALUE_REQUIRED, 'Humidity percentage')
            ->addOption(self::OPT_WIND_SPEED, 'w', InputOption::VALUE_REQUIRED, 'Wind speed in kmh (integer)');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $rawLocationId = $input->getArgument(self::ARG_LOCATION_ID);
        $rawDay = $input->getArgument(self::ARG_DAY);
        $rawShortWeatherDescription = $input->getArgument(self::ARG_SHORT_WEATHER_DESCRIPTION);

        Assertion::integerish($rawLocationId, 'Location id must be an integer');
        $locationId = (int) $rawLocationId;
        Assertion::regex($rawDay, '{(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}}', 'Day format is not valid');
        $day = \DateTimeImmutable::createFromFormat('d-m-Y', $rawDay);

        $shortWeatherDescription = ShortWeatherDescription::tryFrom($rawShortWeatherDescription);
        Assertion::notNull($shortWeatherDescription, 'Invalid short weather description');

        $temperature = null;
        if (null !== $input->getOption(self::OPT_TEMPERATURE)) {
            $rawTemperature = $input->getOption(self::OPT_TEMPERATURE);
            Assertion::count($rawTemperature, 2, 'Exactly two temperatures are expected');
            Assertion::allIntegerish($rawTemperature);
            sort($rawTemperature);
            $temperature = new TemperatureSpan(...array_map(intval(...), $rawTemperature));
        }

        $humidityPercentage = null;
        if ($input->getOption(self::OPT_HUMIDITY_PERCENTAGE)) {
            $rawHumidityPercentage = $input->getOption(self::OPT_HUMIDITY_PERCENTAGE);
            Assertion::numeric($rawHumidityPercentage, 'Humidty percentage is not numeric');
            Assertion::between($rawHumidityPercentage, 0, 1, 'Humidity percentage must be between 0 and 1');
            $humidityPercentage = $rawHumidityPercentage;
        }

        $windSpeedKmh = null;
        if ($input->getOption(self::OPT_WIND_SPEED)) {
            $rawWindSpeedKmh = $input->getOption(self::OPT_WIND_SPEED);
            Assertion::integerish($rawWindSpeedKmh);
            $windSpeedKmh = (int) $rawWindSpeedKmh;
        }

        $location = $this->locationRepository->find($locationId);

        Assertion::notNull($location, 'Location with provided id was not found');

        $forecast = new Forecast($location, $day, $shortWeatherDescription);

        $forecast->setTemperatureSpan($temperature);
        $forecast->setHumidityPercentage($humidityPercentage);
        $forecast->setWindSpeedKmh($windSpeedKmh);

        $this->entityManager->persist($forecast);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
