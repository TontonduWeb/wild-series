<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Actor;

class ActorFixtures extends Fixture
{
    const ACTORS = [
        'Al Pacino',
        'Robert De Niro',
        'Leonardo DiCaprio',
        'Kevin Spacey',
        'Humphrey Bogart',
        'Toshirō Mifune',
        'Clint Eastwood',
        'Morgan Freeman',
        'Johnny Depp',
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::ACTORS as $key => $actorName){
            $actor = new Actor();
            $actor->setName($actorName);
            $manager->persist($actor);
            $this->addReference('actor_' . $key, $actor);
        }
        $manager->flush();
    }
}
