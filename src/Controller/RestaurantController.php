<?php

namespace App\Controller;

use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;


#[Route('api/restaurant', name: 'app_api_restaurant_')]
class RestaurantController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private RestaurantRepository $repository,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator,
    )
    {
    }


    #[Route(methods: 'POST')]
    public function new(Request $request): JsonResponse
    {
        $restaurant = $this->serializer->deserialize($request->getContent(), Restaurant::class, 'json');
        $restaurant->setCreatedAt(new DateTimeImmutable());

        $this->manager->persist($restaurant);
        $this->manager->flush();

        $responseData = $this->serializer->serialize($restaurant, 'json');
        $location = $this->urlGenerator->generate( 'app_api_restaurant_show',['id' => $restaurant->getId()], UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route('/{id}', methods: 'GET')]
    public function show(int $id): JsonResponse
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);
        // $restaurant = Chercher resto ID = 1
        if(!$restaurant){
            $responseData = $this->serializer->serialize($restaurant, 'json');


           return new JsonResponse($responseData, Response::HTTP_OK, [],true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'edit', methods: 'PUT')]
    public function edit(int $id): Response
    {
        // $restaurant = Chercher resto ID = 1
        if (!$restaurant = $this->repository->find($id)) {
            throw new \Exception ("No Restaurant found for {$id} id");
        }

        $restaurant->SetName('Restaurant name updated');

        $this->manager->flush();

        return $this->redirectToRoute('app_api_restaurant_show', ['id' => $restaurant->getId()]);
    }


    #[Route('/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(int $id): Response


    {
        // $restaurant = Chercher resto ID = 1
        if (!$restaurant = $this->repository->find($id)) {
            throw new \Exception ("No Restaurant found for {$id} id");
        }

        return $this->json(['message' => 'Restaurant ressource deleted'], Response:: HTTP_NO_CONTENT);

    }
}
