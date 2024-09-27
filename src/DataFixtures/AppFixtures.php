<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Offer;
use App\Entity\Order;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $faker;
    private $userCounter;
    private $orderCounter;
    private $eventCounter;
    private $offerCounter;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $this->createOffer('Solo', 0, 1, $manager);
        $this->createOffer('Duo', 5, 2, $manager);
        $this->createOffer('Famille', 10, 4, $manager);

        $offerSolo = $this->getReference('offer');
        $offerDuo = $this->getReference('offer1');
        $offerFam = $this->getReference('offer2');

        $this->createUser('admin@test.com', 'maxime', 'admin', $manager);

        /* Create 10 User */
        for ($i = 1; $i <= 10; $i++) {
            $this->createUser($this->faker->email(), $this->faker->firstname(), $this->faker->lastname(), $manager);
        }

        /* Create 10 Event */
        for ($i = 1; $i <= 10; $i++) {
            $this->createEvent('Epreuve ' . $i, 'Lieu de l\'épreuve ' . $i, new \DateTime('2025-' . mt_rand(1, 12) . '-' . mt_rand(1, 30)), new \DateTime(mt_rand(9, 12) . ':00:00'), new \DateTime(mt_rand(13, 17) . ':00:00'), mt_rand(50, 200), $offerSolo, $offerDuo, $offerFam, $manager);
        }

        $manager->flush();
    }

    /**
     * Create an offer with fixtures
     *
     * @param string $name
     * @param integer $discount
     * @param integer $nbPeople
     * @param ObjectManager $manager
     * @return void
     */
    public function createOffer(string $name, int $discount, int $nbPeople, ObjectManager $manager)
    {
        $offer = new Offer();
        $offer->setName($name)
            ->setDiscount($discount)
            ->setNbPeople($nbPeople);

        /* Create a reference for event make relation between entity with fixtures*/
        $this->addReference('offer' . $this->offerCounter, $offer);
        $this->offerCounter++;

        $manager->persist($offer);

        return $offer;
    }

    /**
     * Create an admin with fixtures
     *
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @param ObjectManager $manager
     * @return void
     */
    public function createAdmin(string $email, string $firstname, string $lastname, ObjectManager $manager)
    {
        $admin = new User();
        $admin->setEmail($email)
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setPlainPassword('password')
            ->setRoles(["ROLE_ADMIN"]);

        /* Create a reference for admin make relation between entity with fixtures*/
        $this->addReference($email, $admin);

        $manager->persist($admin);

        return $admin;
    }

    /**
     * Create an user with fixtures
     *
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @param ObjectManager $manager
     * @return void
     */
    public function createUser(string $email, string $firstname, string $lastname, ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail($email)
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setPlainPassword('password');

        /* Create a reference for user make relation between entity with fixtures*/
        $this->addReference('user' . $this->userCounter, $user);
        $this->userCounter++;

        $manager->persist($user);

        return $user;
    }

    /**
     * Create an event with fixtures
     *
     * @param string $name
     * @param string $place
     * @param \DateTime $date
     * @param \DateTime $startTime
     * @param \DateTime $endTime
     * @param integer $price
     * @param Offer $solo
     * @param Offer $duo
     * @param Offer $fam
     * @param ObjectManager $manager
     * @return void
     */
    public function createEvent(string $name, string $place, \DateTime $date, \DateTime $startTime, \DateTime $endTime, int $price, Offer $solo, Offer $duo, Offer $fam, ObjectManager $manager)
    {
        $event = new Event();
        $event->setName($name)
            ->setPlace($place)
            ->setDate($date)
            ->setStartTime($startTime)
            ->setEndTime($endTime)
            ->setPrice($price)
            ->addOffer($solo)
            ->addOffer($duo)
            ->addOffer($fam);

        /* Create a reference for event make relation between entity with fixtures*/
        $this->addReference('event' . $this->eventCounter, $event);
        $this->eventCounter++;

        $manager->persist($event);

        return $event;
    }

    public function createOrder(int $price = 0, User $user, ObjectManager $manager)
    {
        $order = new Order();
        $order->setPrice($price)
            ->setIdUser($user);

        /* Create a reference for event make relation between entity with fixtures*/
        $this->addReference('order' . $this->orderCounter, $order);
        $this->orderCounter++;

        $manager->persist($order);
    }
}
