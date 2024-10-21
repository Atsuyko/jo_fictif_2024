<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testAccessToOffersEventPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue');

        $crawler = $client->click($crawler->filter('div > div > div > a')->eq(0)->link());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Basket');
    }
}
