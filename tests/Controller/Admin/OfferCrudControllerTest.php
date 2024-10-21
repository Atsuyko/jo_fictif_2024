<?php

namespace App\Tests\Controller\Admin;

use App\Entity\User;
use App\Entity\Offer;
use App\Controller\Admin\DashboardController;
use App\Controller\Admin\OfferCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Test\AbstractCrudTestCase;

class OfferCrudControllerTest extends AbstractCrudTestCase
{

  protected function getControllerFqcn(): string
  {
    return OfferCrudController::class;
  }

  protected function getDashboardFqcn(): string
  {
    return DashboardController::class;
  }

  public function testIfAddOfferIsSuccessfull(): void
  {
    $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@jo.com']);
    $this->client->loginUser($user);
    $this->client->request('GET', '/admin');
    $this->assertResponseIsSuccessful();

    $offer = $this->entityManager->getRepository(Offer::class)->findOneBy(['name' => 'Offre Test']);

    if ($offer) {
      $this->entityManager->remove($offer);
      $this->entityManager->flush($offer);
    }

    $crawler = $this->client->clickLink('Offres');
    $this->assertResponseIsSuccessful();

    $this->client->click($crawler->filter('.action-new')->link());
    $this->assertResponseIsSuccessful();

    $form = $this->client->getCrawler()->filter('form[name=Offer]')->form([
      'Offer[name]' => 'Offre Test',
      'Offer[discount]' => '50',
      'Offer[nb_people]' => '10',
    ]);
    $this->client->submit($form);
    self::assertCount(1, $this->entityManager->getRepository(Offer::class)->findBy(['name' => 'Offre Test']));
  }

  public function testIfDeleteOfferIsSuccessfull(): void
  {
    $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@jo.com']);
    $this->client->loginUser($user);
    $this->client->request('GET', '/admin');
    $this->assertResponseIsSuccessful();

    $crawler = $this->client->clickLink('Offres');
    $this->assertResponseIsSuccessful();

    $this->client->click($crawler->filter('.action-delete')->eq(3)->link());
    $this->client->followRedirect();
    $this->assertResponseIsSuccessful();

    $deleteButton = $crawler->selectButton('Delete');
    $form = $deleteButton->form();
    $this->client->submit($form);
    $this->assertResponseIsSuccessful();
  }
}
