<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i <=4; $i++){
            $episode = new Episode();
            $episode->setTitle('Titre' . $i)
                    ->setNumber($i)
                    ->setSynopsis('Synopsis ' . $i);
                $episode->setSeason($this->getReference('season_1'));

            $manager->persist($episode);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SeasonFixtures::class
        ];
    }

}
