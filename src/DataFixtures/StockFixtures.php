<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\Stock;

class StockFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($prod = 1; $prod <= 10; $prod++) {
            $stock = new Stock();
            $stock->setNbArticle($faker->numberBetween(0, 5));

            $articleReference = 'art-' . $prod;
            $article = $this->getReference($articleReference);
            $stock->setArticle($article);

            $manager->persist($stock);
        }

        $manager->flush();
    }
}
