<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    const SEASON_NUMBER = [
        'Saison 1',
        'Saison 2',
        'Saison 3',
        'Saison 4',
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::SEASON_NUMBER as $key => $seasonNumber){

        $season = new Season();
        $season->setProgram($this->getReference('program_' . $key));
                        $season->setNumber(($key +1))
                        ->setDescription(self::SEASON_NUMBER[$key])
                        ->setYear(2000 + $key);
            $this->addReference('season_' . ($key +1), $season);
            $manager->persist($season);
            }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProgramFixtures::class,
        ];
    }


}
