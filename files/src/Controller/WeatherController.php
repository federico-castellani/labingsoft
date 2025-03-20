<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class WeatherController extends AbstractController
{
    #[Route('/weather/{state}/{city}',
        name: 'weather',
        requirements: [
            'state' => '[a-zA-Z]{2}',
            'city' => Requirement::ASCII_SLUG,
        ])]
    public function index(?string $state = null, ?string $city = null): Response
    {
        return $this->render('weather/index.html.twig', [
            'state' => $state,
            'city' => $city,
        ]);
    }
}