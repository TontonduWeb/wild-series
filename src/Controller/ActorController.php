<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Program;
use App\Repository\ActorRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ActorController
 * @package App\Controller
 * @Route("/actor", name="actor_")
 */
class ActorController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('actor/index.html.twig', [
            'controller_name' => 'ActorController',
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     * @param Actor $actor
     * @param ActorRepository $actorRepository
     * @return Response
     */
    public function show(Actor $actor, ActorRepository $actorRepository): Response
    {
        $actorName = $actorRepository->findOneBy(['id' => $actor]);

        return $this->render('actor/show.html.twig', [
            'actor' => $actorName,
        ]);
    }
}
