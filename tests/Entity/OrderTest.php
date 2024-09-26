<?php

namespace App\Tests;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


// class OrderTest extends KernelTestCase
// {

//   public function getEntity(): Order
//   {
//     return (new Order())

//       ->setCreatedAt(new \DateTimeImmutable())
//       ->setUpdatedAt(null);
//   }

//   public function assertHasErrors(Order $order, int $number = 0)
//   {
//     self::bootKernel();
//     $container = static::getContainer();
//     $errors = $container->get('validator')->validate($order);
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
