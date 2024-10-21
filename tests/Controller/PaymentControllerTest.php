<?php

namespace App\Tests\Controller;

use App\Entity\Order;
use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaymentControllerTest extends WebTestCase
{
  public function testPaymentCartWhenNotConnectedRedirectToConnexion(): void
  {
    $client = static::createClient();
    $container = static::getContainer();
    $em = $container->get('doctrine.orm.entity_manager');

    $orderRepository = $em->getRepository(Order::class);
    $ticketRepository = $em->getRepository(Ticket::class);
    $tickets = $ticketRepository->findAll();
    foreach ($tickets as $ticket) {
      $em->remove($ticket);
    }
    $orders = $orderRepository->findAll();
    foreach ($orders as $order) {
      $em->remove($order);
    }

    $em->flush();

    $crawler = $client->request('GET', '/');
    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', 'Bienvenue');

    $crawler = $client->click($crawler->filter('div > div > div > a')->eq(1)->link());
    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', 'Athlétisme');

    $crawler = $client->click($crawler->filter('div > div > div > a')->eq(1)->link());
    $client->followRedirect();
    $client->followRedirect();
    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', 'Votre panier');
    $this->assertSelectorTextContains('h5', 'Athlétisme - Duo');

    $crawler = $client->clickLink('Valider');
    self::assertResponseRedirects('/connexion');
    $client->followRedirect();

    $client->submitForm('Connexion', [
      '_username' => 'user@jo.com',
      '_password' => 'password',
    ]);
    $client->followRedirect();
    self::assertCount(1, $orderRepository->findBy(['is_paid' => 0]));

    $order = $orderRepository->findOneBy(['is_paid' => 0]);
    $id = $order->getId()->toString();

    $crawler = $client->request('GET', '/commande/succes/' . $id);
    $client->followRedirect();
    $this->assertResponseIsSuccessful();

    $crawler = $client->request('GET', '/panier/');
    $this->assertSelectorTextContains('h1', 'Votre panier');
    $this->assertSelectorTextContains('h2', 'Le panier est vide');
  }
}
