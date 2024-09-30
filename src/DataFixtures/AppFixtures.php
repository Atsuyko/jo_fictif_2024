<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Offer;
use App\Entity\Order;
use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $faker;
    private $adminCounter;
    private $userCounter;
    private $orderCounter;
    private $eventCounter;
    private $offerCounter;
    private $ticketCounter;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $this->createOffer('Solo', 0, 1, $manager);
        $this->createOffer('Duo', 5, 2, $manager);
        $this->createOffer('Famille', 10, 4, $manager);

        $offerSolo = $this->getReference('offer1');
        $offerDuo = $this->getReference('offer2');
        $offerFam = $this->getReference('offer3');

        $this->createAdmin('admin@test.com', 'Maxime', 'Admin', $manager);

        /* Create 10 Users */
        for ($i = 1; $i <= 10; $i++) {
            $this->createUser($this->faker->email(), $this->faker->firstname(), $this->faker->lastname(), $manager);
        }

        /* Create 1 Order for each User (without admin) */
        for ($i = 1; $i <= 10; $i++) {
            $this->createOrder($this->getReference('user' . $i), $manager);
        }

        /* Create 10 Events */
        for ($i = 1; $i <= 10; $i++) {
            $this->createEvent('Epreuve ' . $i, 'Lieu de l\'épreuve ' . $i, new \DateTime('2025-' . mt_rand(1, 12) . '-' . mt_rand(1, 30)), new \DateTime(mt_rand(9, 12) . ':00:00'), new \DateTime(mt_rand(13, 17) . ':00:00'), mt_rand(50, 200), $offerSolo, $offerDuo, $offerFam, $manager);
        }

        /* Create 25 Tickets */
        for ($i = 1; $i <= 25; $i++) {
            $this->createTicket($this->getReference('event' . mt_rand(1, 10)), $this->getReference('offer' . mt_rand(1, 3)), $this->getReference('order' . mt_rand(1, 10)), $manager);
        }

        /* Update order's prices */
        for ($i = 1; $i <= 10; $i++) {
            $order = $this->getReference('order' . $i);
            $totalOrderPrice = $order->getPrice();

            for ($j = 1; $j <= 25; $j++) {
                $ticket = $this->getReference('ticket' . $j);

                if ($ticket->getOrder()->getId() === $order->getId()) {
                    $totalOrderPrice += $ticket->getPrice();
                }
            }

            $order->setPrice($totalOrderPrice)
                ->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($order);
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

        /* Create a reference for event to make relation between entity with fixtures*/
        $this->addReference('offer' . $this->offerCounter + 1, $offer);
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

        /* Create a reference for admin to make relation between entity with fixtures*/
        $this->addReference('admin' . $this->adminCounter + 1, $admin);
        $this->adminCounter++;

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

        /* Create a reference for user to make relation between entity with fixtures*/
        $this->addReference('user' . $this->userCounter + 1, $user);
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

        /* Create a reference for event to make relation between entity with fixtures*/
        $this->addReference('event' . $this->eventCounter + 1, $event);
        $this->eventCounter++;

        $manager->persist($event);

        return $event;
    }

    /**
     * Create an order with fixtures
     *
     * @param User $user
     * @param ObjectManager $manager
     * @return void
     */
    public function createOrder(User $user, ObjectManager $manager)
    {
        $order = new Order();
        $order->setPrice(0)
            ->setUser($user);

        /* Create a reference for order to make relation between entity with fixtures*/
        $this->addReference('order' . $this->orderCounter + 1, $order);
        $this->orderCounter++;

        $manager->persist($order);
    }

    /**
     * Create a ticket with fixtures
     *
     * @param Event $event
     * @param Offer $offer
     * @param Order $order
     * @param integer $price
     * @param ObjectManager $manager
     * @return void
     */
    public function createTicket(Event $event, Offer $offer, Order $order, ObjectManager $manager)
    {
        $ticket = new Ticket();
        $ticket->setEvent($event)
            ->setOffer($offer)
            ->setOrder($order)
            ->setPaid(0);


        /* Create a reference for ticket to make relation between entity with fixtures*/
        $this->addReference('ticket' . $this->ticketCounter + 1, $ticket);
        $this->ticketCounter++;

        $manager->persist($ticket);
    }
}
