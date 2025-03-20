<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/weather')]
class WeatherController extends AbstractController
{
    #[Route(
        '/{countryCode}/{cityName}',
        name: 'view_weather_forecasts_by_city',
        requirements: [
            'countryCode' => '[a-zA-Z]{2}',
            'cityName' => Requirement::ASCII_SLUG,
        ],
    )]
    public function index(string $countryCode, string $cityName): Response
    {
        return $this->render(
            'weather/index.html.twig',
            [
                'countryCode' => $countryCode,
                'cityName' => $cityName,
                'forecast' => [
                    'day' => new \DateTimeImmutable('today'),
                    'location' => [
                        'name' => 'Perugia',
                        'country' => 'IT',
                    ],
                    'shortDescription' => 'SUNNY',
                    'temperature' => 20,
                    'wind' => 2,
                    'humidity' => 0.30,
                ],
            ]
        );
    }
}
