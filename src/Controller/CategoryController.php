<?php

namespace App\Controller;

use App\Entity\Category;
use DateTimeImmutable;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/category', name: 'app_api_category_')]
class CategoryController extends AbstractController
{
    public function __construct (private EntityManagerInterface $manager, private CategoryRepository $repository)
    {
    }

    #[Route(name: 'new', methods: 'POST')]
    public function new(): Response
    {
        $category = new Category();
        $category->setTittle("Plats Savoyard");
        $category->setCreatedAt(new \DateTimeImmutable());


        $this->manager->persist($category);
        $this->manager->flush();

        return $this->json(
            ['message' => "Category ressource created with {$category->getId()} id"],
            Response:: HTTP_CREATED,
        );
    }

    #[Route('/{id}', name: 'show', methods: 'GET')]
    public function show(int $id): Response
    {
        $category = $this->repository->findOneBy(['id' => $id]);
        // $category = Chercher category ID = 1
        if(!$category = $this->repository->find($id)) {
            throw new BadRequestException ("No Category found for {$id} id");
        }

        return $this->json(['message' => "A category was found : {$category->getTittle()} for {$category->getId()} id"]);
    }

    #[Route('/{id}', name: 'edit', methods: 'PUT')]
    public function edit(int $id): Response
    {
        // $Category = Chercher category ID = 1
        if (!$category = $this->repository->find($id)) {
            throw new \Exception ("No category found for {$id} id");
        }

        $category->setTittle('category name updated');

        $this->manager->flush();

        return $this->redirectToRoute('app_api_category_show', ['id' => $category->getId()]);
    }

    #[Route('/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(int $id): Response


    {
        // $category = Chercher category ID = 1
        if (!$category = $this->repository->find($id)) {
            throw new \Exception ("No category found for {$id} id");
        }

        return $this->json(['message' => 'Category ressource deleted'], Response:: HTTP_NO_CONTENT);

    }

}
