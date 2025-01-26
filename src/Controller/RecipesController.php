<?php
namespace App\Controller;
use App\Repository\RecipeRepository;
use App\Entity\Recipe;
use App\Form\RecipeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

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

    #[Route('/recipes/{id}/edit', name: 'recipes.edit', methods:['GET','POST'])]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $em)
    {

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();
            $this->addFlash('success', 'Edited successfully!');
            return $this->redirectToRoute('recipes.index');
        }
        else {
            $this->addFlash('danger', 'Failed to edit!');
        }   


        return $this->render('recipes/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    #[Route('/recipes/create', name: 'recipes.create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setCreatedAt(new \DateTimeImmutable());
            $recipe->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'Created successfully!');
            return $this->redirectToRoute('recipes.index');
        }
        else {
            $this->addFlash('danger', 'Failed to create!');
        }   

        return $this->render('recipes/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/recipes/{id}/delete', name: 'recipes.remove', methods: ['DELETE'])]
    public function remove(Request $request, Recipe $recipe, EntityManagerInterface $em)
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'Deleted successfully!');
        return $this->redirectToRoute('recipes.index');
    }
    
}