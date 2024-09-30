<?php

namespace App\Tests;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EventTest extends KernelTestCase
{
  public function getEntity(): Event
  {
    return (new Event())

      ->setName('Epreuve X')
      ->setPlace('Lieu de l\'épreuve X')
      ->setDate(new \DateTime('2024-11-19'))
      ->setStartTime(new \DateTime('10:00:00'))
      ->setEndTime(new \DateTime('12:00:00'))
      ->setPrice(100)
      ->setUpdatedAt(null);
  }

  public function assertHasErrors(Event $event, int $number = 0)
  {
    self::bootKernel();
    $container = static::getContainer();
    $errors = $container->get('validator')->validate($event);
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

  public function testInvalidName()
  {
    $this->assertHasErrors($this->getEntity()->setName(''), 2);
  }

  public function testInvalidPlace()
  {
    $this->assertHasErrors($this->getEntity()->setPlace(''), 2);
  }

  public function testInvalidPrice()
  {
    $this->assertHasErrors($this->getEntity()->setPrice(-1), 1);
  }

  public function testValidDate()
  {
    $this->assertHasErrors($this->getEntity()->setDate(new \DateTime()), 0);
  }

  public function testValidStartTime()
  {
    $this->assertHasErrors($this->getEntity()->setStartTime(new \DateTime()), 0);
  }

  public function testValidEndTime()
  {
    $this->assertHasErrors($this->getEntity()->setEndTime(new \DateTime()), 0);
  }
}
