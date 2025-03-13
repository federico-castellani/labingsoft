<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AboutController
{
    #[Route('/about/{subject}', name: 'about')]
    public function index(?string $subject = null): Response
    {
        if ($subject !== null) {
            return new Response("<html><body>Ciao $subject!</body></html>");
        }
        return new Response('<html><body>Questa è la nostra applicazione</body></html>');
    }
}
