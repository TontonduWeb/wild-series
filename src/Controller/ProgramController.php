<?php


namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Repository\CategoryRepository;
use App\Repository\EpisodeRepository;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @return Response
     */
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();

        return $this->render('program/index.html.twig', ['programs' => $programs]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, ProgramRepository $program): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();
            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/new.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * Getting a program by id
     *
     * @Route("/show/{id<^[0-9]+$>}", name="show")
     * @param Program $program
     * @param CategoryRepository $categoryRepository
     * @param SeasonRepository $seasonRepository
     * @return Response
     */
    public function show(Program $program, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {
        $program = $programRepository
            ->findOneBy(['id' => $program]);

        if(!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$program.' found in program\'s table.'
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
     * @Route("/{program_id}/seasons/{season_id}", name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season_id": "id"}})
     */
    public function showSeason(Program $program, Season $season, ProgramRepository $programRepository, SeasonRepository $seasonRepository, EpisodeRepository $episodeRepository)
    {
        $program = $programRepository
            ->findOneBy(['id' => $program]);

        if(!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$program.' found in program\'s table.'
            );
        }

        $season = $seasonRepository
            ->findOneBy(['id' => $season]);

        if(!$season) {
            throw $this->createNotFoundException(
                'No season with id : '.$season.' found in season\'s table.'
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

    /**
     * @Route("/{program}/seasons/{season}/episodes/{episode}", name="episode_show")
     * @param Program $program
     * @param Season $season
     * @param Episode $episode
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render("program/episode_show.html.twig", [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}