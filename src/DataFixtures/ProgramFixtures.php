<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Program;

class ProgramFixtures extends Fixture
{
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
        foreach (self::PROGRAMS as $key => $programName) {
            $program = new Program();
            $program->setTitle($programName);
            $program->setCategory(2);
            $manager->persist($program);
        }
        $manager->flush();
    }
}
