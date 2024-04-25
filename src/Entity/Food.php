<?php

namespace App\Entity;

use App\Repository\FoodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FoodRepository::class)]
class Food
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 36)]
    private ?string $tittle = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $price = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updateAt = null;

    /**
     * @var Collection<int, CategoryFood>
     */
    #[ORM\OneToMany(targetEntity: CategoryFood::class, mappedBy: 'foodId', orphanRemoval: true)]
    private Collection $categoryFood;

    public function __construct()
    {
        $this->categoryFood = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTittle(): ?string
    {
        return $this->tittle;
    }

    public function setTittle(string $tittle): static
    {
        $this->tittle = $tittle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * @return Collection<int, CategoryFood>
     */
    public function getCategoryFood(): Collection
    {
        return $this->categoryFood;
    }

    public function addCategoryFood(CategoryFood $categoryFood): static
    {
        if (!$this->categoryFood->contains($categoryFood)) {
            $this->categoryFood->add($categoryFood);
            $categoryFood->setFoodId($this);
        }

        return $this;
    }

    public function removeCategoryFood(CategoryFood $categoryFood): static
    {
        if ($this->categoryFood->removeElement($categoryFood)) {
            // set the owning side to null (unless already changed)
            if ($categoryFood->getFoodId() === $this) {
                $categoryFood->setFoodId(null);
            }
        }

        return $this;
    }
}
