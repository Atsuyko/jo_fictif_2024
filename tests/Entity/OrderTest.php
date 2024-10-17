<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class OrderTest extends KernelTestCase
{

  public function getEntity(): Order
  {
    return (new Order())
      ->setPrice(100)
      ->setPaid(true)
      ->setUser(new User());
  }

  public function assertHasErrors(Order $order, int $number = 0)
  {
    self::bootKernel();
    $container = static::getContainer();
    $errors = $container->get('validator')->validate($order);
    $messages = [];

    foreach ($errors as $error) {
      $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
    }
    $this->assertCount($number, $errors, implode(', ', $messages));
  }

  public function testInvalidPrice()
  {
    $this->assertHasErrors($this->getEntity()->setPrice(-1), 1);
    $this->assertHasErrors($this->getEntity()->setPrice(0), 1);
  }

  public function testInvalidPaid()
  {
    $this->assertHasErrors($this->getEntity()->setPaid(''), 1);
  }
}
