<?php

namespace App\Entity;

use App\Repository\MenuIdRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuIdRepository::class)]
class MenuId
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'menuIds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $categoryId = null;

    #[ORM\ManyToOne(inversedBy: 'menuIds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Menu $menuId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryId(): ?Category
    {
        return $this->categoryId;
    }

    public function setCategoryId(?Category $categoryId): static
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    public function getMenuId(): ?Menu
    {
        return $this->menuId;
    }

    public function setMenuId(?Menu $menuId): static
    {
        $this->menuId = $menuId;

        return $this;
    }
}
