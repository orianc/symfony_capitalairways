<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Flight::class, mappedBy="departure")
     */
    private $departure;

    /**
     * @ORM\OneToMany(targetEntity=Flight::class, mappedBy="arrival")
     */
    private $arrival;

    public function __construct()
    {
        $this->departure = new ArrayCollection();
        $this->arrival = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Flight[]
     */
    public function getDeparture(): Collection
    {
        return $this->departure;
    }

    public function addDeparture(Flight $departure): self
    {
        if (!$this->departure->contains($departure)) {
            $this->departure[] = $departure;
            $departure->setDeparture($this);
        }

        return $this;
    }

    public function removeDeparture(Flight $departure): self
    {
        if ($this->departure->removeElement($departure)) {
            // set the owning side to null (unless already changed)
            if ($departure->getDeparture() === $this) {
                $departure->setDeparture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Flight[]
     */
    public function getArrival(): Collection
    {
        return $this->arrival;
    }

    public function addArrival(Flight $arrival): self
    {
        if (!$this->arrival->contains($arrival)) {
            $this->arrival[] = $arrival;
            $arrival->setArrival($this);
        }

        return $this;
    }

    public function removeArrival(Flight $arrival): self
    {
        if ($this->arrival->removeElement($arrival)) {
            // set the owning side to null (unless already changed)
            if ($arrival->getArrival() === $this) {
                $arrival->setArrival(null);
            }
        }

        return $this;
    }
}
