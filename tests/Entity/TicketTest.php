<?php

namespace App\Tests;

use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


// class TicketTest extends KernelTestCase
// {

//   public function getEntity(): Ticket
//   {
//     return (new Ticket())

//       ->setCreatedAt(new \DateTimeImmutable())
//       ->setUpdatedAt(null);
//   }

//   public function assertHasErrors(Ticket $ticket, int $number = 0)
//   {
//     self::bootKernel();
//     $container = static::getContainer();
//     $errors = $container->get('validator')->validate($ticket);
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
