<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CategoryType;

/**
 * @Route("/categories", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository
            ->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @ROUTE("/new", name="new")
     */
    public function new(Request $request, CategoryRepository $category): Response
    {
        // Create a new Category Object
        $category = new Category();
        // Create the associated Form, la methode a deux paramètres le nom de la classe de formulaire à créer et l'objet à hydrater
        $form = $this->createForm(CategoryType::class, $category);
        // Get data from HTTP request
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Was the form submitted ?
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            // Persist Category Objet
            $entityManager->flush();
            // Fulsh the persisted object
            return $this->redirectToRoute('category_index');
        }
        //Render the form
        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }
    /**
     * @Route("/{name}", name="show")
     */
    public function show(string $name, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $name]);

        if (!$category) {
            throw $this->createNotFoundException(
                'No category with this name : ' . $name . ' found in category\'s table.'
            );
        }else{
            $myLimit = 3;
            $programs = $programRepository->findBy(['category' => $category],
                    ['id' => 'DESC'],
                    $myLimit);
        }

        return $this->render('category/show.html.twig', [
            'programs' => $programs
            ]);
    }
}
