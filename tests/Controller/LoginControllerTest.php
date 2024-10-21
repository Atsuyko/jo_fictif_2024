<?php

namespace App\Tests\Controller;

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

        $user = $userRepository->findOneBy(['email' => 'admin@test.fr']);

        if ($user) {
            $em->remove($user);
            $em->flush($user);
        }

        $user = (new User())
            ->setEmail('admin@test.fr')
            ->setFirstname('Admintest')
            ->setLastname('Test')
            ->setPlainPassword('password')
            ->setVerified(true)
            ->setRoles(['ROLE_ADMIN']);

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
            '_username' => 'admin@test.fr',
            '_password' => 'bad-password',
        ]);

        self::assertResponseRedirects('/connexion');
        $this->client->followRedirect();

        // Ensure we do not reveal the user exists but the password is wrong.
        self::assertSelectorTextContains('.alert-danger', 'Invalid credentials.');

        // Success - Login with valid credentials is allowed.
        $this->client->submitForm('Connexion', [
            '_username' => 'admin@test.fr',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/');
        $this->client->followRedirect();

        self::assertSelectorNotExists('.alert-danger');
        self::assertResponseIsSuccessful();
    }
    public function testLogout(): void
    {
        $this->client->request('GET', '/deconnexion');
        self::assertResponseRedirects('/connexion');
        $this->client->followRedirect();
    }
}
