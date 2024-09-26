<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class UserTest extends KernelTestCase
{

    public function getEntity(): User
    {
        return (new User())
            ->setEmail('test@mail.com')
            ->setFirstname('Julien')
            ->setLastname('Testeur')
            ->setPassword('password')
            ->setRoles(["ROLE_USER"])
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(null);
    }

    public function assertHasErrors(User $user, int $number = 0)
    {
        self::bootKernel();
        $container = static::getContainer();
        $errors = $container->get('validator')->validate($user);
        $messages = [];

        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }

    public function testValidEntity()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testInvalidPassword()
    {
        $this->assertHasErrors($this->getEntity()->setPassword(''), 1);
    }

    public function testInvalidName()
    {
        $this->assertHasErrors($this->getEntity()->setFirstname('Julien1'), 1);
        $this->assertHasErrors($this->getEntity()->setFirstname('J'), 1);
        $this->assertHasErrors($this->getEntity()->setFirstname('11'), 1);
        $this->assertHasErrors($this->getEntity()->setFirstname(''), 2);
        $this->assertHasErrors($this->getEntity()->setLastname('Testeur1'), 1);
        $this->assertHasErrors($this->getEntity()->setLastname('T'), 1);
        $this->assertHasErrors($this->getEntity()->setLastname('11'), 1);
        $this->assertHasErrors($this->getEntity()->setLastname(''), 2);
    }

    public function testInvalidEmail()
    {
        $this->assertHasErrors($this->getEntity()->setEmail('@test.com'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('julientest.com'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('julien@testcom'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail('julien@.com'), 1);
        $this->assertHasErrors($this->getEntity()->setEmail(''), 1);
    }

    public function testValidEmail()
    {
        $this->assertHasErrors($this->getEntity()->setEmail('julien@test.com'), 0);
        $this->assertHasErrors($this->getEntity()->setEmail('Julien@test.fr'), 0);
        $this->assertHasErrors($this->getEntity()->setEmail('Julien1@mail1.com'), 0);
    }
}
