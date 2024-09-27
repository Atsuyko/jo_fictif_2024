<?php

namespace App\Tests;

use App\Entity\Offer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OfferTest extends KernelTestCase
{

  public function getEntity(): Offer
  {
    return (new Offer())
      ->setName('Offre')
      ->setDiscount(10)
      ->setNbPeople(4);
  }

  public function assertHasErrors(Offer $offer, int $number = 0)
  {
    self::bootKernel();
    $container = static::getContainer();
    $errors = $container->get('validator')->validate($offer);
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

  public function testInvalidDiscount()
  {
    $this->assertHasErrors($this->getEntity()->setDiscount(-4), 1);
    $this->assertHasErrors($this->getEntity()->setDiscount(100), 1);
  }

  public function testInvalidNbPeople()
  {
    $this->assertHasErrors($this->getEntity()->setDiscount(-4), 1);
  }
}
