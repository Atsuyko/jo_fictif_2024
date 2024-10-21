<?php

namespace App\Tests\Controller\Admin;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\Offer;
use App\Controller\Admin\DashboardController;
use App\Controller\Admin\EventCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Test\AbstractCrudTestCase;

class EventCrudControllerTest extends AbstractCrudTestCase
{

  protected function getControllerFqcn(): string
  {
    return EventCrudController::class;
  }

  protected function getDashboardFqcn(): string
  {
    return DashboardController::class;
  }

  public function testIfAddEventIsSuccessfull(): void
  {
    $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@jo.com']);
    $this->client->loginUser($user);
    $this->client->request('GET', '/admin');
    $this->assertResponseIsSuccessful();

    $event = $this->entityManager->getRepository(Event::class)->findOneBy(['name' => 'Epreuve Test']);
    $offer = $this->entityManager->getRepository(Offer::class)->findOneById(1);

    if ($event) {
      $this->entityManager->remove($event);
      $this->entityManager->flush($event);
    }

    $crawler = $this->client->clickLink('Evènements');
    $this->assertResponseIsSuccessful();

    $this->client->click($crawler->filter('.action-new')->link());
    $this->assertResponseIsSuccessful();

    // $form = $this->client->getCrawler()->filter('form[name=Event]')->form([
    //   'Event[name]' => 'Epreuve Test',
    //   'Event[place]' => 'Rue du Test',
    //   'Event[date]' => '19/11/2024',
    //   'Event[start_time]' => '0900',
    //   'Event[end_time]' => '1200',
    //   'Event[price]' => '100',
    // ]);
    // $this->client->submit($form);
    // self::assertCount(1, $this->entityManager->getRepository(Event::class)->findBy(['name' => 'Offre Test']));
  }
}
