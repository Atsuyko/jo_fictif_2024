<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
  public function testTicketPaidIsInTheUserSpace(): void
  {
    $client = static::createClient();
    $container = static::getContainer();
    $em = $container->get('doctrine.orm.entity_manager');

    $crawler = $client->request('GET', '/connexion');
    $this::assertResponseIsSuccessful();

    $client->submitForm('Connexion', [
      '_username' => 'user@jo.com',
      '_password' => 'password',
    ]);

    $this::assertResponseRedirects('/');
    $client->followRedirect();

    $crawler = $client->clickLink('Espace utilisateur');
    $this::assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', 'Bonjour ');
    $this->assertSelectorTextContains('h5', 'Athlétisme - Duo');
    $this->assertSelectorExists('img', 'qr-code');
  }
}
