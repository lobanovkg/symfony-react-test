<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Apartment
 *
 * @ORM\Table(name="apartment")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\Apartment")
 */
class Apartment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="bedrooms", type="integer", nullable=false)
     */
    private $bedrooms;

    /**
     * @var integer
     *
     * @ORM\Column(name="bathrooms", type="integer", nullable=false)
     */
    private $bathrooms;

    /**
     * @var integer
     *
     * @ORM\Column(name="storeys", type="integer", nullable=false)
     */
    private $storeys;

    /**
     * @var integer
     *
     * @ORM\Column(name="garages", type="integer", nullable=false)
     */
    private $garages;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(int $bedrooms): self
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    public function getBathrooms(): ?int
    {
        return $this->bathrooms;
    }

    public function setBathrooms(int $bathrooms): self
    {
        $this->bathrooms = $bathrooms;

        return $this;
    }

    public function getStoreys(): ?int
    {
        return $this->storeys;
    }

    public function setStoreys(int $storeys): self
    {
        $this->storeys = $storeys;

        return $this;
    }

    public function getGarages(): ?int
    {
        return $this->garages;
    }

    public function setGarages(int $garages): self
    {
        $this->garages = $garages;

        return $this;
    }
}
