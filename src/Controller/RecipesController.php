<?php
namespace App\Controller;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
final class RecipesController extends AbstractController
{
    #[Route('/recipes', name: 'recipes.index',)]
    public function index(Request $request, RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository -> findWithDurationLowerThan(30);
        return $this->render('recipes/index.html.twig',[
             'recipes' => $recipes]
        );
    }
    #[Route('/recipes/{slug}-{id}', name: 'recipes.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(Request $request, string $slug, int $id, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository -> find($id);
        if (!$recipe) {
            return $this->redirectToRoute('recipes.index');
        }
        if ($recipe->getSlug() !== $slug) {
            return $this -> redirectToRoute('recipes.show',['slug' => $recipe -> getSlug(), 'id' => $recipe -> getId()]);
        }
        return $this->render('recipes/show.html.twig',[
            'recipe' => $recipe,
        ]);
    }
}