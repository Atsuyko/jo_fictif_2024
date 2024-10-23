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
    private $employeeCounter;
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
        $this->createOffer('Solo', 0, 1, 'upload_608_phpC979.tmp.png', $manager);
        $this->createOffer('Duo', 5, 2, 'upload_992_phpFA45.tmp.png', $manager);
        $this->createOffer('Famille', 10, 4, 'upload_496_php35E8.tmp.png', $manager);

        $offerSolo = $this->getReference('offer1');
        $offerDuo = $this->getReference('offer2');
        $offerFam = $this->getReference('offer3');

        $this->createAdmin('admin@jo.com', 'Admin', 'Fictif', $manager);
        $this->createEmployee('employee@jo.com', 'Employee', 'Fictif', $manager);
        $this->createUser('user@jo.com', 'User', 'Fictif', $manager);

        $this->createEvent('Basket ', 'Terrain 1',  '2024-11-01', '10:00:00', '12:00:00', 100, 'upload_203_phpA59.tmp.png', $offerSolo, $offerDuo, $offerFam, $manager);
        $this->createEvent('Athlétisme', 'Stade de France', '2024-11-16', '14:00:00', '18:00:00', 150, 'upload_167_phpA83B.tmp.png', $offerSolo, $offerDuo, $offerFam, $manager);
        $this->createEvent('Natation', 'Aquaboulevard', '2024-12-05', '15:00:00', '19:00:00', 120, 'upload_889_phpA346.tmp.png', $offerSolo, $offerDuo, $offerFam, $manager);
        $this->createEvent('Cyclisme', 'Vélodrome SQY', '2024-12-24', '09:00:00', '11:30:00', 80, 'upload_853_php2E9F.tmp.png', $offerSolo, $offerDuo, $offerFam, $manager);
        $this->createEvent('Boxe', 'Ring 452', '2025-01-09', '12:00:00', '17:00:00', 70, 'upload_984_phpD83E.tmp.png', $offerSolo, $offerDuo, $offerFam, $manager);
        $this->createEvent('Equitation', 'Château de Versailles', '2025-02-05', '13:00:00', '16:00:00', 90, 'upload_599_php7CFA.tmp.png', $offerSolo, $offerDuo, $offerFam, $manager);
        $this->createEvent('Escalade', 'Mur 18', '2025-02-12', '17:00:00', '19:00:00', 50, 'upload_609_php45D9.tmp.png', $offerSolo, $offerDuo, $offerFam, $manager);
        $this->createEvent('Escrime', 'Piste 8', '2024-12-13', '08:00:00', '11:00:00', 70, 'upload_573_phpBF60.tmp.png', $offerSolo, $offerDuo, $offerFam, $manager);
        $this->createEvent('Gymnastique', 'Gymnase 7', '2024-11-02', '09:00:00', '17:00:00', 130, 'upload_727_php3C51.tmp.png', $offerSolo, $offerDuo, $offerFam, $manager);
        $this->createEvent('Tennis de table', 'Gymnase 56', '2024-11-16', '12:00:00', '15:00:00', 40, 'upload_284_phpB4BE.tmp.png', $offerSolo, $offerDuo, $offerFam, $manager);

        $manager->flush();
    }

    /**
     * Create an offer
     *
     * @param string $name
     * @param integer $discount
     * @param integer $nbPeople
     * @param string $picture
     * @param ObjectManager $manager
     * @return void
     */
    public function createOffer(string $name, int $discount, int $nbPeople, string $picture, ObjectManager $manager)
    {
        $offer = new Offer();
        $offer->setName($name)
            ->setDiscount($discount)
            ->setNbPeople($nbPeople)
            ->setPicture($picture);

        /* Create a reference for event to make relation between entity */
        $this->addReference('offer' . $this->offerCounter + 1, $offer);
        $this->offerCounter++;

        $manager->persist($offer);

        return $offer;
    }

    /**
     * Create an admin
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
            ->setRoles(["ROLE_ADMIN"])
            ->setVerified(true);

        /* Create a reference for admin to make relation between entity */
        $this->addReference('admin' . $this->adminCounter + 1, $admin);
        $this->adminCounter++;

        $manager->persist($admin);

        return $admin;
    }

    /**
     * Create an admin
     *
     * @param string $email
     * @param string $firstname
     * @param string $lastname
     * @param ObjectManager $manager
     * @return void
     */
    public function createEmployee(string $email, string $firstname, string $lastname, ObjectManager $manager)
    {
        $employee = new User();
        $employee->setEmail($email)
            ->setFirstname($firstname)
            ->setLastname($lastname)
            ->setPlainPassword('password')
            ->setRoles(["ROLE_EMPLOYEE"])
            ->setVerified(true);

        /* Create a reference for employee to make relation between entity */
        $this->addReference('employee' . $this->employeeCounter + 1, $employee);
        $this->employeeCounter++;

        $manager->persist($employee);

        return $employee;
    }

    /**
     * Create an user
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
            ->setPlainPassword('password')
            ->setVerified(true);

        /* Create a reference for user to make relation between entity */
        $this->addReference('user' . $this->userCounter + 1, $user);
        $this->userCounter++;

        $manager->persist($user);

        return $user;
    }

    /**
     * Create an event
     *
     * @param string $name
     * @param string $place
     * @param string $date
     * @param string $startTime
     * @param string $endTime
     * @param integer $price
     * @param string $picture
     * @param Offer $solo
     * @param Offer $duo
     * @param Offer $fam
     * @param ObjectManager $manager
     * @return void
     */
    public function createEvent(string $name, string $place, string $date, string $startTime, string $endTime, int $price, string $picture, Offer $solo, Offer $duo, Offer $fam, ObjectManager $manager)
    {
        $event = new Event();
        $event->setName($name)
            ->setPlace($place)
            ->setDate(new \DateTime($date))
            ->setStartTime(new \DateTime($startTime))
            ->setEndTime(new \DateTime($endTime))
            ->setPrice($price)
            ->setPicture($picture)
            ->addOffer($solo)
            ->addOffer($duo)
            ->addOffer($fam);

        /* Create a reference for event to make relation between entity */
        $this->addReference('event' . $this->eventCounter + 1, $event);
        $this->eventCounter++;

        $manager->persist($event);

        return $event;
    }
}
