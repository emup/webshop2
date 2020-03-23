<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FactuurregelRepository")
 */
class Factuurregel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Factuur", inversedBy="factuurregels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $factuurnummer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="factuurregels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productcode;

    /**
     * @ORM\Column(type="integer")
     */
    private $productaantal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFactuurnummer(): ?Factuur
    {
        return $this->factuurnummer;
    }

    public function setFactuurnummer(?Factuur $factuurnummer): self
    {
        $this->factuurnummer = $factuurnummer;

        return $this;
    }

    public function getProductcode(): ?Product
    {
        return $this->productcode;
    }

    public function setProductcode(?Product $productcode): self
    {
        $this->productcode = $productcode;

        return $this;
    }

    public function getProductaantal(): ?int
    {
        return $this->productaantal;
    }

    public function setProductaantal(int $productaantal): self
    {
        $this->productaantal = $productaantal;

        return $this;
    }
}
