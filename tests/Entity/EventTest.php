<?php

namespace App\Tests;

use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


// class EventTest extends KernelTestCase
// {

//   public function getEntity(): Event
//   {
//     return (new Event())

//       ->setCreatedAt(new \DateTimeImmutable())
//       ->setUpdatedAt(null);
//   }

//   public function assertHasErrors(Event $event, int $number = 0)
//   {
//     self::bootKernel();
//     $container = static::getContainer();
//     $errors = $container->get('validator')->validate($event);
//     $messages = [];

//     foreach ($errors as $error) {
//       $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
//     }
//     $this->assertCount($number, $errors, implode(', ', $messages));
//   }

//   public function testValidEntity()
//   {
//     $this->assertHasErrors($this->getEntity(), 0);
//   }
// }
