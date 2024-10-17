<?php

namespace App\Tests;

use App\Entity\Event;
use App\Entity\Offer;
use App\Entity\Order;
use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


class TicketTest extends KernelTestCase
{

  public function getEntity(): Ticket
  {
    return (new Ticket())
      ->setEvent(new Event())
      ->setOffer(new Offer())
      ->setOrder(new Order())
      ->setPrice(100)
      ->setQrkey('451ergaqe651ehtr0516')
      ->setPaid(true);
  }

  public function assertHasErrors(Ticket $ticket, int $number = 0)
  {
    self::bootKernel();
    $container = static::getContainer();
    $errors = $container->get('validator')->validate($ticket);
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

  public function testInvalidPrice()
  {
    $this->assertHasErrors($this->getEntity()->setPrice(-5), 1);
    $this->assertHasErrors($this->getEntity()->setPrice(0), 1);
  }

  public function testInvalidPaid()
  {
    $this->assertHasErrors($this->getEntity()->setPaid(''), 1);
  }
}
