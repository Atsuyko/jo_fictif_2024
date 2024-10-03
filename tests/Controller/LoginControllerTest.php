<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $userRepository = $em->getRepository(User::class);

        // Remove any existing users from the test database
        foreach ($userRepository->findAll() as $user) {
            $em->remove($user);
        }

        $em->flush();

        $user = (new User())
            ->setEmail('email@example.com')
            ->setFirstname('Firtest')
            ->setLastname('Lastest')
            ->setPlainPassword('password');

        $em->persist($user);
        $em->flush();
    }

    public function testLogin(): void
    {
        // Denied - Can't login with invalid email address.
        $this->client->request('GET', '/connexion');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Connexion', [
            '_username' => 'doesNotExist@example.com',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/connexion');
        $this->client->followRedirect();

        // Ensure we do not reveal if the user exists or not.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // Denied - Can't login with invalid password.
        $this->client->request('GET', '/connexion');
        self::assertResponseIsSuccessful();

        $this->client->submitForm('Connexion', [
            '_username' => 'email@example.com',
            '_password' => 'bad-password',
        ]);

        self::assertResponseRedirects('/connexion');
        $this->client->followRedirect();

        // Ensure we do not reveal the user exists but the password is wrong.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // Success - Login with valid credentials is allowed.
        $this->client->submitForm('Connexion', [
            '_username' => 'email@example.com',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/');
        $this->client->followRedirect();

        self::assertSelectorNotExists('.alert-danger');
        self::assertResponseIsSuccessful();
    }
}