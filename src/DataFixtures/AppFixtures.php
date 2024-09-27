<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Offer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $offerSolo = new Offer();
        $offerSolo->setName('Solo')
            ->setDiscount(0)
            ->setNbPeople(1);

        $offerDuo = new Offer();
        $offerDuo->setName('Duo')
            ->setDiscount(5)
            ->setNbPeople(2);

        $offerFam = new Offer();
        $offerFam->setName('Famille')
            ->setDiscount(10)
            ->setNbPeople(4);

        for ($i = 1; $i <= 10; $i++) {
            $event = new Event();
            $event->setName('Epreuve ' . $i)
                ->setPlace('Lieu de l\'épreuve ' . $i)
                ->setDate(new \DateTime('2025-' . mt_rand(1, 12) . '-' . mt_rand(1, 30)))
                ->setStartTime(new \DateTime(mt_rand(9, 12) . ':00:00'))
                ->setEndTime(new \DateTime(mt_rand(13, 17) . ':00:00'))
                ->setPrice(mt_rand(50, 200))
                ->addOffer($offerSolo)
                ->addOffer($offerDuo)
                ->addOffer($offerFam);

            $manager->persist($event);
        }

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email())
                ->setFirstname($this->faker->firstname())
                ->setLastname($this->faker->lastname())
                ->setPlainPassword('password');

            $manager->persist($user);
        }

        $manager->persist($offerSolo);
        $manager->persist($offerDuo);
        $manager->persist($offerFam);


        $manager->flush();
    }
}
