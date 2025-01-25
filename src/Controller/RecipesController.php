<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecipesController extends AbstractController
{

    #[Route('/recipes', name: 'recipes.index',)]
    public function index(Request $request): Response
    {
        return $this->render('recipes/index.html.twig');
    }

    #[Route('/recipes/{slug}-{id}', name: 'recipes.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id): Response
    {
        return $this->render('recipes/show.html.twig',[
            'slug' => $slug,
            'id' => $id
        ]);
    }
}