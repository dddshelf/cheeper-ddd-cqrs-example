<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DefaultController extends AbstractController
{
    /** @Route("/") */
    public function __invoke(): Response
    {
        return $this->render('spa.html.twig', [
            'entrypoint' => 'app'
        ]);
    }
}
