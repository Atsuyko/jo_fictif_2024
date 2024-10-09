<?php

namespace App\EntityListener;

use App\Entity\Ticket;

class TicketListener
{
  /**
   * Set total ticket price from event and offer
   *
   * @param Ticket $ticket
   * @return void
   */
  public function getTotalTicketPrice(Ticket $ticket)
  {
    $ticket->setPrice($ticket->getEvent()->getPrice() * $ticket->getOffer()->getNbPeople() * (100 - $ticket->getOffer()->getDiscount()) / 100);
  }

  public function prePersist(Ticket $ticket)
  {
    $this->getTotalTicketPrice($ticket);
  }

  public function preUpdate(Ticket $ticket)
  {
    $this->getTotalTicketPrice($ticket);
  }
}
