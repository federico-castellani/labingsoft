<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/weather')]
class WeatherController extends AbstractController
{
    #[Route(
        '/{countryCode}/{cityName}',
        name: 'view_weather_forecasts_by_city',
    )]
    public function index(string $countryCode, string $cityName): Response
    {
        return $this->render(
            'weather/index.html.twig',
            [
                'countryCode' => $countryCode,
                'cityName' => $cityName
            ]
        );
    }
}
