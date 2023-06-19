<?php

namespace App\Entity;

class SearchProduct
{


    private ?int $minPrice = null;

    private ?int $maxPrice = null;
    /**
     * @var  Categories[]
     */
    private array $categories = [];

    private ?string $tags = null;


    public function getMinPrice(): ?int
    {
        return $this->minPrice;
    }

    public function setMinPrice(?int $minPrice): static
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(int $maxPrice): static
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(?array $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): static
    {
        $this->tags = $tags;

        return $this;
    }
}
