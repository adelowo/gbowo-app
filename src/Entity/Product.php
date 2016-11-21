<?php

namespace App\Entity;

class Product
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var string
     */
    protected $moniker;

    public function __construct()
    {
        $this->createdAt = \App\formatDateTime(new \DateTime());
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Product
     */
    public function setName(string $name): Product
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Product
     */
    public function setPrice(float $price): Product
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Product
     */
    public function setDescription(string $description): Product
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     * @return Product
     */
    public function setImage(string $image): Product
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|string $createdAt
     * @return Product
     */
    public function setCreatedAt($createdAt): Product
    {
        $this->createdAt = ($createdAt instanceof \DateTime) ? \App\formatDateTime($createdAt) : $createdAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getMoniker(): string
    {
        return $this->moniker;
    }

    /**
     * @param string $moniker
     * @return Product
     */
    public function setMoniker(string $moniker): Product
    {
        $this->moniker = $moniker;

        return $this;
    }
}
