<?php

namespace App\DataFixtures;

use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Program;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
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

    private $slug;

    public function __construct(Slugify $slug)
    {
        $this->slug = $slug;
    }

    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAMS as $key => $programName){

            $program = new Program();
            $program
                    ->setTitle($programName)
                    ->setSynopsis('bliblibli')
                    ->setSummary('blobloblo')
                    ->setSlug($this->slug->generate($program->getTitle()));
            for($i=0; $i < count(CategoryFixtures::CATEGORIES); $i++){
                $program->setCategory($this->getReference('category_' . $i));
            }
            for ($i=0; $i < count(ActorFixtures::ACTORS); $i++){
                $program->addActor($this->getReference('actor_' . $i));
            }
            $this->addReference('program_' . $key, $program);
            $manager->persist($program);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ActorFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
