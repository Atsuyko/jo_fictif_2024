<?php

namespace App\Tests\Controller;

use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
  public function testAddAndDeleteToCart(): void
  {
    $client = static::createClient();
    $container = static::getContainer();
    $em = $container->get('doctrine.orm.entity_manager');
    $ticketRepository = $em->getRepository(Ticket::class);

    $crawler = $client->request('GET', '/évènement/186891965236');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', 'Basket');

    /** Click on Add to cart link and access to cart page */
    $crawler = $client->click($crawler->filter('div > div > div > a')->eq(0)->link());
    $client->followRedirect();
    $client->followRedirect();
    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', 'Votre panier');
    $this->assertSelectorTextContains('h5', 'Basket - Solo');
    self::assertCount(1, $ticketRepository->findBy(['is_paid' => 0]));

    /** Delete ticket which create on cart */
    $crawler = $client->clickLink('Supprimer');
    $client->followRedirect();
    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h2', 'Le panier est vide');
    self::assertCount(0, $ticketRepository->findBy(['is_paid' => 0]));
  }
}
