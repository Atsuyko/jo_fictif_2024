<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        // Ensure we have a clean database
        $container = static::getContainer();

        /** @var EntityManager $em */
        $em = $container->get('doctrine')->getManager();
        $this->userRepository = $container->get(UserRepository::class);

        $user = $this->userRepository->findOneBy(['email' => 'user@test.fr']);

        if ($user) {
            $em->remove($user);
            $em->flush($user);
        }
    }

    public function testRegister(): void
    {
        // Register a new user
        $this->client->request('GET', '/inscription');
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Inscription');

        $this->client->submitForm('Inscription', [
            'registration_form[firstname]' => 'Usertest',
            'registration_form[lastname]' => 'Test',
            'registration_form[email]' => 'user@test.fr',
            'registration_form[plainPassword][first]' => 'p@sSw0rd',
            'registration_form[plainPassword][second]' => 'p@sSw0rd',
            // 'form[agreeTerms]' => true,
        ]);

        // Ensure the response redirects after submitting the form, the user exists, and is not verified
        self::assertResponseRedirects('/connexion');
        self::assertFalse(($user = $this->userRepository->findOneBy(['email' => 'user@test.fr']))->isVerified());

        // Ensure the verification email was sent
        // Use either assertQueuedEmailCount() || assertEmailCount() depending on your mailer setup
        // self::assertQueuedEmailCount(1);
        self::assertEmailCount(1);

        self::assertCount(2, $messages = $this->getMailerMessages());
        self::assertEmailAddressContains($messages[0], 'from', 'fictifjo@gmail.com');
        self::assertEmailAddressContains($messages[0], 'to', 'user@test.fr');
        self::assertEmailTextBodyContains($messages[0], 'Ce lien expirera dans 1 hour.');

        // Login the new user
        $this->client->followRedirect();
        $this->client->loginUser($user);

        // Get the verification link from the email
        /** @var TemplatedEmail $templatedEmail */
        $templatedEmail = $messages[0];
        $messageBody = $templatedEmail->getHtmlBody();
        self::assertIsString($messageBody);

        preg_match('#(http://localhost/verify/email.+)">#', $messageBody, $resetLink);

        // "Click" the link and see if the user is verified
        $this->client->request('GET', $resetLink[1]);
        $this->client->followRedirect();

        self::assertTrue(static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'user@test.fr'])->isVerified());
    }
}
