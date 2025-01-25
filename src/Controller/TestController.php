<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{

    #[Route('/test', name: 'test.index',)]
    public function index(Request $request): Response
    {
        return $this->render('test/index.html.twig');
    }

    #[Route('/test/{slug}-{id}', name: 'test.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id): Response
    {
        return $this->render('test/show.html.twig',[
            'slug' => $slug,
            'id' => $id
        ]);
    }
}