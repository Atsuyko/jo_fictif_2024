<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OfferRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
#[HasLifecycleCallbacks]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    #[Assert\NotBlank()]
    #[Assert\Length(
        min: 2,
        max: 100,
    )]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    #[Assert\LessThan(99)]
    private ?int $discount = null;

    #[ORM\Column]
    #[Assert\Positive()]
    #[Assert\NotBlank()]
    private ?int $nb_people = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 2,
        max: 255,
    )]
    private ?string $picture = null;

    #[ORM\Column]
    #[Assert\NotBlank()]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'offers')]
    private Collection $events;

    /**
     * @var Collection<int, Ticket>
     */
    #[ORM\OneToMany(targetEntity: Ticket::class, mappedBy: 'offer', orphanRemoval: true)]
    private Collection $tickets;

    /**
     * Automatic created_at when instance of class is create
     */
    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->events = new ArrayCollection();
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    public function getNbPeople(): ?int
    {
        return $this->nb_people;
    }

    public function setNbPeople(int $nb_people): static
    {
        $this->nb_people = $nb_people;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->addOffer($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            $event->removeOffer($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): static
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setOffer($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): static
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getOffer() === $this) {
                $ticket->setOffer(null);
            }
        }

        return $this;
    }
}
