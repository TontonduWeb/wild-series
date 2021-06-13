<?php


namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;
use App\Service\Slugify;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Repository\CategoryRepository;
use App\Repository\EpisodeRepository;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
     * @Route("/new", name="new", methods={"GET","POST"})
     * @param Request $request
     * @param Slugify $slugify
     * @return Response
     */
    public function new(Request $request, Slugify $slugify, MailerInterface $mailer): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();
//        dd($program);

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('matthieudejean@hotmail.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));
            $mailer->send($email);
            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/new.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * Getting a program by slug
     *
     * @Route("/show/{slug}", name="show")
     * @param Program $program
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
     * @Route("/{slug}/seasons/{season}", name="season_show")
     * @ParamConverter("program", class="App\Entity\Program")
     * @ParamConverter("season", class="App\Entity\Season")
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
     * @Route("/{slug}/seasons/{season}/episodes/{episode_slug}", name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"slug": "slug"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episode_slug": "slug"}})
     * @param Episode $episode
     * @param Program $program
     * @param Season $season
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