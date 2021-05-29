<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/categories", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{name}", name="show")
     */
    public function show(string $name, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {
        $categoryName = $categoryRepository
            ->findBy(['name' => $name]);

        if (!$categoryName) {
            throw $this->createNotFoundException(
                'No category with this name : ' . $name . ' found in category\'s table.'
            );
        }else{
            $myLimit = 3;
        $categoryProgram = $programRepository
            ->findBy(['category' => $categoryName],
                    ['id' => 'DESC'],
                    $myLimit);
        }
        return $this->render('category/show.html.twig', [
            'programs' => $categoryProgram
            ]);
    }

//    /**
//     * @Route("/create", name="create")
//     */
//    public function addCategory(EntityManagerInterface $entityManager): Response
//    {
//        $categoryHumour = new Category();
//        $categoryHumour->setName('Humour');
//        $entityManager->persist($categoryHumour);
//        $entityManager->flush();
//    }
}
