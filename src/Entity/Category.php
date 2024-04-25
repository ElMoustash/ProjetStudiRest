<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 36)]
    private ?string $tittle = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updateAt = null;

    /**
     * @var Collection<int, CategoryFood>
     */
    #[ORM\OneToMany(targetEntity: CategoryFood::class, mappedBy: 'categoryId', orphanRemoval: true)]
    private Collection $categoryFood;

    /**
     * @var Collection<int, MenuId>
     */
    #[ORM\OneToMany(targetEntity: MenuId::class, mappedBy: 'categoryId', orphanRemoval: true)]
    private Collection $menuIds;

    public function __construct()
    {
        $this->categoryFood = new ArrayCollection();
        $this->menuIds = new ArrayCollection();
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
            $categoryFood->setCategoryId($this);
        }

        return $this;
    }

    public function removeCategoryFood(CategoryFood $categoryFood): static
    {
        if ($this->categoryFood->removeElement($categoryFood)) {
            // set the owning side to null (unless already changed)
            if ($categoryFood->getCategoryId() === $this) {
                $categoryFood->setCategoryId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MenuId>
     */
    public function getMenuIds(): Collection
    {
        return $this->menuIds;
    }

    public function addMenuId(MenuId $menuId): static
    {
        if (!$this->menuIds->contains($menuId)) {
            $this->menuIds->add($menuId);
            $menuId->setCategoryId($this);
        }

        return $this;
    }

    public function removeMenuId(MenuId $menuId): static
    {
        if ($this->menuIds->removeElement($menuId)) {
            // set the owning side to null (unless already changed)
            if ($menuId->getCategoryId() === $this) {
                $menuId->setCategoryId(null);
            }
        }

        return $this;
    }
}
