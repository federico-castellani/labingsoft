<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
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
    public function index(
        LocationRepository $repository,
        string $countryCode,
        string $cityName,
    ): Response {
        $location = $repository->findByCountryAndName($countryCode, $cityName);
        if ($location === null) {
            throw $this->createNotFoundException();
        }
        /** @var Location $location */
        $forecasts = $location->getForecasts();
        $forecast = reset($forecasts) ?: null;

        return $this->render(
            'weather/index.html.twig',
            [
                'countryCode' => $countryCode,
                'cityName' => $cityName,
                'forecast' => $forecast,
            ]
        );
    }
}
