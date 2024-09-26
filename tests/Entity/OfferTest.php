<?php

namespace App\Tests;

use App\Entity\Offer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


// class OfferTest extends KernelTestCase
// {

//   public function getEntity(): Offer
//   {
//     return (new Offer())

//       ->setCreatedAt(new \DateTimeImmutable())
//       ->setUpdatedAt(null);
//   }

//   public function assertHasErrors(Offer $offer, int $number = 0)
//   {
//     self::bootKernel();
//     $container = static::getContainer();
//     $errors = $container->get('validator')->validate($offer);
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
