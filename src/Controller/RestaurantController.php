<?php

namespace App\Controller;

use DateTimeImmutable;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Schema;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;


#[Route('api/restaurant', name: 'app_api_restaurant_')]
class RestaurantController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private RestaurantRepository $repository,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }


    #[Route(methods: 'POST')]

    #[OA\Post(
        path:"/api/restaurant",
        summary: "Inscription d'un nouveau restaurant",
        requestBody: new RequestBody(
            required: true,
            description: "Données du restaurant à inscrire",
            content: [new MediaType('application/json',
                schema: new schema(type: "object", properties: [new Property(
                    property: "name",
                    type: "string",
                    example: "Les délices d'Ulysse"
                ),
                    new Property(
                        property: "description",
                        type: "string",
                        example: "Un repas prés du soleil!"
                    )
                ]))]
        )
    )]

    #[OA\Response(
        response: 200,
        description: "Restaurant créer avec succès",
        content: [new MediaType('application/json',
            schema: new schema(type: "object", properties: [new Property(
                property: "id",
                type: "int",
                example: "1",
            ),
                new Property(
                    property: "name",
                    type: "string",
                    example: "Les délices d'Ulysse"
                ),
                new Property(
                    property: "description",
                    type: "String",
                    example: "Un repas d'Ulysse"
                ),
                new Property(
                    property: "created_at",
                    type: "string",
                    format: "Y-m-d H:i:s",
                )
            ]))])]

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

    #[Route('/{id}', name: 'show', methods: 'GET')]
    public function show(int $id): JsonResponse
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);
        // $restaurant = Chercher resto ID = 1
        if($restaurant) {$responseData = $this->serializer->serialize($restaurant, 'json');

            return new JsonResponse($responseData, Response::HTTP_OK, [],true);
        }

        return new JsonResponse("no restaurant", Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'edit', methods: 'PUT')]
    public function edit(int $id, Request $request): JsonResponse
    {

        $restaurant = $this->repository->findOneBy(['id' => $id]);

        if($restaurant) {
            $restaurant = $this->serializer->deserialize(
                $request->getContent(),
                Restaurant::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $restaurant]
            );
            $restaurant->setUpdateAt(new DateTimeImmutable());


        $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

         return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }


    #[Route('/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(int $id): JsonResponse


    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);
        // $restaurant = Chercher resto ID = 1
        if ($restaurant) {
            $this->manager->remove($restaurant);
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);

    }
}
