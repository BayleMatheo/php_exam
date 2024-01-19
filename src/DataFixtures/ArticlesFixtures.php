<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

;

class ArticlesFixtures extends Fixture
{
    private $counter = 1;
    public function __construct(
        private SluggerInterface $slugger
    ){}
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for($prod = 1 ; $prod <= 10; $prod++){
            $article = new Article();
            $article->setNom($faker->text(5));
            $article->setDescription($faker->text());
            $article->setPrix($faker->numberBetween(10000,100000));
            $article->setLienDeImage($faker->imageUrl(640, 400));

            //ref à user
            $userReference = 'user-' . rand(1, 5);
            $user = $this->getReference($userReference);
            $article->setIdUser($user);


            $manager->persist($article);
            $this->addReference('art-'.$this->counter, $article);
            $this->counter++;

        }
        $manager->flush();
    }
}
