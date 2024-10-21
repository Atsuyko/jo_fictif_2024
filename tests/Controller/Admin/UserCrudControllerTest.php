<?php

namespace App\Tests\Controller\Admin;

use App\Entity\User;
use App\Controller\Admin\UserCrudController;
use App\Controller\Admin\DashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Test\AbstractCrudTestCase;

class UserCrudControllerTest extends AbstractCrudTestCase
{

  protected function getControllerFqcn(): string
  {
    return UserCrudController::class;
  }

  protected function getDashboardFqcn(): string
  {
    return DashboardController::class;
  }

  public function testIfAddUserIsSuccessfull(): void
  {
    $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'admin@jo.com']);
    $this->client->loginUser($user);
    $this->client->request('GET', '/admin');
    $this->assertResponseIsSuccessful();

    $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'test@test.com']);

    if ($user) {
      $this->entityManager->remove($user);
      $this->entityManager->flush($user);
    }

    $crawler = $this->client->clickLink('Utilisateurs');
    $this->assertResponseIsSuccessful();

    $this->client->click($crawler->filter('.action-new')->link());
    $this->assertResponseIsSuccessful();

    $form = $this->client->getCrawler()->filter('form[name=User]')->form([
      'User[email]' => 'test@test.com',
      'User[firstname]' => 'Testeur',
      'User[lastname]' => 'Tester',
      'User[plainPassword]' => 'password',
      'User[roles][0]' => 'ROLE_EMPLOYEE',
      'User[Verified]' => true
    ]);
    $this->client->submit($form);
    self::assertCount(1, $this->entityManager->getRepository(User::class)->findBy(['email' => 'test@test.com']));
  }
}
