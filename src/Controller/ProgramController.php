<?php


namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Entity\Season;
use App\Repository\CategoryRepository;
use App\Repository\EpisodeRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("/programs", name="program_")
     */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository
            ->findAll();

//        dd($programs);

        return $this->render(
            'program/index.html.twig',
            ['programs' => $programs]);
    }

    /**
     * Getting a program by id
     *
     * @Route("/show/{id<^[0-9]+$>}", name="show")
     * @return Response
     */
    public function show(Category $category, Program $program, CategoryRepository $categoryRepository, SeasonRepository $seasonRepository): Response
    {
        $serie = $categoryRepository
            ->findOneBy(['id' => $category]);

        if(!$serie) {
            throw $this->createNotFoundException(
                'No program with id : '. $serie . ' found in program\'s table.'
            );
        }
        $seasons = $seasonRepository
            ->findBy(['program'=> $program]);

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
            ]);
    }

    /**
     * @Route("/{programId<^[0-9]+$>}/season/{seasonId<^[0-9]+$>}"), name="season_show"
     */
    public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository)
    {
        $program = $programRepository
            ->findOneBy(['id' => $programId]);

        if(!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$programId.' found in program\'s table.'
            );
        }

        $season = $seasonRepository
            ->findOneBy(['id' => $seasonId]);

        if(!$season) {
            throw $this->createNotFoundException(
                'No season with id : '.$seasonId.' found in season\'s table.'
            );
        }
        $episodes = $episodeRepository
            ->findBy(['season' => $season]);

        return $this->render('program/season_show.html.twig', [
            'programs' => $program,
            'seasons' => $season,
            'episodes' => $episodes,
        ]);
    }
}