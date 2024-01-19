<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker\Factory;


class AAUsersFixtures extends Fixture
{

    private $counter = 1;
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private SluggerInterface $slugger
){}
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@demo.fr');
        $admin->setUsername('admin');
        $admin->setSolde(0);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'admin')
        );
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $faker = Factory::create('fr_FR');

        for($usr = 1; $usr <= 5; $usr++){
            $user = new User();
            $user->setEmail($faker->email);
            $user->setUsername($faker->username);
            $user->setSolde($faker->numberBetween($min = 0, $max = 2000));
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'secret')
            );
            $manager->persist($user);
            $this->addReference('user-' . $this->counter, $user);
            $this->counter++;
        }



        $manager->flush();
    }
}
