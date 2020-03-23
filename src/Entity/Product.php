<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $productomschrijving;

    /**
     * @ORM\Column(type="float")
     */
    private $productbtw;

    /**
     * @ORM\Column(type="float")
     */
    private $productprijs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Factuurregel", mappedBy="productcode")
     */
    private $factuurregels;

    public function __construct()
    {
        $this->factuurregels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductomschrijving(): ?string
    {
        return $this->productomschrijving;
    }

    public function setProductomschrijving(string $productomschrijving): self
    {
        $this->productomschrijving = $productomschrijving;

        return $this;
    }

    public function getProductbtw(): ?float
    {
        return $this->productbtw;
    }

    public function setProductbtw(float $productbtw): self
    {
        $this->productbtw = $productbtw;

        return $this;
    }

    public function getProductprijs(): ?float
    {
        return $this->productprijs;
    }

    public function setProductprijs(float $productprijs): self
    {
        $this->productprijs = $productprijs;

        return $this;
    }

    /**
     * @return Collection|Factuurregel[]
     */
    public function getFactuurregels(): Collection
    {
        return $this->factuurregels;
    }

    public function addFactuurregel(Factuurregel $factuurregel): self
    {
        if (!$this->factuurregels->contains($factuurregel)) {
            $this->factuurregels[] = $factuurregel;
            $factuurregel->setProductcode($this);
        }

        return $this;
    }

    public function removeFactuurregel(Factuurregel $factuurregel): self
    {
        if ($this->factuurregels->contains($factuurregel)) {
            $this->factuurregels->removeElement($factuurregel);
            // set the owning side to null (unless already changed)
            if ($factuurregel->getProductcode() === $this) {
                $factuurregel->setProductcode(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getId()."";
    }
}
