<?php

namespace App\Controller;

use App\Repository\FoodRepository;
use DateTimeImmutable;
use App\Entity\Food;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/food', name: 'app_api_food_')]
class FoodController extends AbstractController
{
    public function __construct (private EntityManagerInterface $manager, private FoodRepository $repository)
    {
    }

    #[Route('/{id}', name: 'new', methods: 'POST')]
    public function new(): Response
    {
        $food = new Food();
        $food->setTittle("Croziflette");
        $food->setDescription("Tartiflette à base de Crozet");
        $food->setPrice(15);
        $food->setCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($food);
        $this->manager->flush();

        return $this->json(
            ['message' => "Food ressource created with {$food->getId()} id"],
            Response:: HTTP_CREATED,
        );
    }

    #[Route('/{id}', name: 'show', methods: 'GET')]
    public function show(int $id): Response
    {
        $food = $this->repository->findOneBy(['id' => $id]);
        // $ food = chercher food id = 1
        if(!$food = $this->repository->find($id)) {
            throw new BadRequestException("No Food found for [$id} id");
        }

        return $this->json(['message' => "A Food was found : {$food->getTittle()} for {$food->getId()} id"]);
    }

    #[Route('/{id}', name: 'edit', methods: 'PUT')]
    public function edit(int $id): Response
    {
        // $restaurant = Chercher resto ID = 1
        if (!$food = $this->repository->find($id)) {
            throw new \Exception ("No Food found for {$id} id");
        }

       $food->setTittle('Food name updated');

        $this->manager->flush();

        return $this->redirectToRoute('app_api_food_show', ['id' => $food->getId()]);
    }

    #[Route('/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(int $id): Response
    {
        // $food = Chercher food ID = 1
        if (!$food = $this->repository->find($id)) {
            throw new \Exception ("No food found for {$id} id");
        }

        return $this->json(['message' => 'Food ressource deleted'], Response:: HTTP_NO_CONTENT);

    }


}
