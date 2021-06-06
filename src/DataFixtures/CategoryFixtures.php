<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
        'Action',
        'Aventure',
        'Animation',
        'Fantastique',
        'Horreur',
    ];

    const PROGRAMS = [
        'Game of Thrones',
        'Breaking Bad',
        'The Walking Dead',
        'Friends',
        'Stranger Things',
        'Sherlock ',
        'Dexter',
        'How I Met Your Mother',
        'Chernobyl',
    ];

    public function load(ObjectManager $manager)
    {

        $episode1Saison1Chernobyl = new Episode();
        $episode1Saison1Chernobyl->setTitle(' 1:23:45')
            ->setNumber(1)
            ->setSynopsis('Le professeur Legasov enregistre ses mémoires sur des cassettes puis se donne la mort deux ans après la catastrophe de Tchernobyl. Le 26 avril 1986, un test de sécurité mené sur le réacteur 4 de la centrale de Tchernobyl conduit à l\'explosion du coeur du réacteur RBMK. Mais Anatoly Dyatlov, qui dirige alors l\'équipe chargée de mener à bien le test, refuse de voir la vérité en face et prétend qu\'il s\'agit seulement de l\'explosion d\'un réservoir de refroidissement ');
        $manager->persist($episode1Saison1Chernobyl);

        $episode1Saison2Chernobyl = new Episode();
        $episode1Saison2Chernobyl->setTitle(' Veuillez garder votre calme')
            ->setNumber(2)
            ->setSynopsis('Ulana Khomyuk, une physicienne nucléaire, détecte une radioactivité importante à Minsk et décide de mener une enquête discrète. Le professeur Legasov et Boris Shcherbina assistent à une réunion présidée par Gorbatchev. Legasov explique que le coeur a forcément explosé puisqu\'on a trouvé des morceaux de graphite autour de la centrale. Gorbatchev décide de l\'envoyer sur place en compagnie de Shcherbina. Là, ils découvrent ensemble l\'ampleur de la catastrophe ');
        $manager->persist($episode1Saison2Chernobyl);

        $episode1Saison3Chernobyl = new Episode();
        $episode1Saison3Chernobyl->setTitle(' Que la terre s\'ouvre !')
            ->setNumber(3)
            ->setSynopsis('Lyudmilla parvient à voir son mari à l\'hôpital. On lui accorde une demi-heure, avec interdiction du moindre contact charnel avec lui. Non seulement elle y passe beaucoup plus de temps, mais elle touche son mari à de nombreuses reprises... Le professeur Legasov se rapproche du vice-président Tcherbina et ils travaillent ensemble à la résolution de l\'après-accident. L\'eau du réacteur est asséchée, mais la dalle de béton va s\'effondrer dans les semaines qui viennent. Il faut trouver une solution ');
        $manager->persist($episode1Saison3Chernobyl);

        $episode1Saison4Chernobyl = new Episode();
        $episode1Saison4Chernobyl->setTitle('Le bonheur de toute l\'humanité')
            ->setNumber(4)
            ->setSynopsis('Quatre mois après l\'explosion, l\'évacuation de la région de Tchernobyl bat son plein. Des gens ont été réquisitionnés pour s\'occuper de la liquidation, abattre les chiens, inhumer la terre contaminée. Tout le monde vit dans des camps de fortune et porte des protections de fortune. L\'enquête avance. De toute évidence, il y avait un défaut dans le déclenchement de l\'arrêt manuel du réacteur, mais qui était au courant ? Comment se fait-il qu\'on n\'ait pas dit aux opérateurs que la procédure normale était dangereuse ? ');
        $manager->persist($episode1Saison4Chernobyl);

        $saison1Chernobyl = new Season();
        $saison1Chernobyl->setNumber(1)
            ->setYear(2008)
            ->setDescription('Le professeur Legasov enregistre ses mémoires sur des cassettes puis se donne la mort deux ans après la catastrophe de Tchernobyl. Le 26 avril 1986, un test de sécurité mené sur le réacteur 4 de la centrale de Tchernobyl conduit à l\'explosion du coeur du réacteur RBMK. Mais Anatoly Dyatlov, qui dirige alors l\'équipe chargée de mener à bien le test, refuse de voir la vérité en face et prétend qu\'il s\'agit seulement de l\'explosion d\'un réservoir de refroidissement ')
            ->addEpisode($episode1Saison1Chernobyl)
            ->addEpisode($episode1Saison2Chernobyl)
            ->addEpisode($episode1Saison3Chernobyl)
            ->addEpisode($episode1Saison4Chernobyl);
        $manager->persist($saison1Chernobyl);

        $categoryName = new Category();
        $categoryName->setName(Category::CATEGORY_AVENTURE);
        $manager->persist($categoryName);

        foreach (self::PROGRAMS as $key => $programName) {
            $program = new Program();
            $program->setTitle($programName)
            ->setCategory($categoryName)
            ->addSeason($saison1Chernobyl);
            $manager->persist($program);
        }
        foreach (self::CATEGORIES as $key => $categoryName) {

        $category = new Category();
        $category->setName($categoryName);
        $manager->persist($category);
        }

        $manager->flush();
    }
}
