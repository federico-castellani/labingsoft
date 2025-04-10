<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/locations')]
class LocationController extends AbstractController
{
    #[Route('/', name: 'view_all_locations')]
    public function index(
        LocationRepository $locationRepository,
        #[MapQueryParameter] int $page = 1,
    ): Response {
        if ($page <= 0) {
            throw $this->createNotFoundException('Invalid page');
        }
        $pageSize = 5;
        $locations = $locationRepository->findPaginated($page, $pageSize);

        return $this->render(
            'location/index.html.twig',
            [
                'page' => [
                    'number' => $page,
                    'items' => $locations,
                    'size' => $pageSize,
                    'hasNextPage' => count($locations) === $pageSize,
                    'hasPreviousPage' => $page >= 2,
                ],
            ]
        );
    }
}
