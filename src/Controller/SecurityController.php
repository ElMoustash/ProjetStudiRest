<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\RepositoryException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use DateTimeImmutable;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\JsonContent,
    OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\Items;

#[Route('/api', name: 'app_api_')]
class SecurityController extends AbstractController
{
   public function __construct(
       private EntityManagerInterface $manager,
       private SerializerInterface $serializer,
        private UserPasswordHasherInterface $passwordHasher
   )
   {
   }

    #[Route('/registration', name: 'registration', methods: 'POST')]
    #[OA\Post(
        path:"/api/registration",
        summary: "Inscription d'un nouvel utilisateur",
        requestBody: new RequestBody(
            required: true,
            description: "Données de l'utilisateur à inscrire",
            content: [new MediaType('application/json',
                schema: new schema(type: "object", properties: [new Property(
                    property: "email",
                    type: "string",
                    example: "adresse@email.com"
                ),
                    new Property(
                        property: "password",
                        type: "string",
                        example: "Mot de Passe"
                    ),
                   ]))]
        )
)]

#[OA\Response(
    response: 201,
        description: "Utilisateur inscrit avec succès",
        content: [new MediaType('application/json',
        schema: new schema(type: "object", properties: [new Property(
            property: "user",
                type: "string",
                example: "Nom d'Utilisateur",
            ),
                new Property(
                    property: "apiToken",
                    type: "string",
                    example: " 2d15f2re15621dfr5g1g51h215h147f45h12"
                ),
                new Property(
                    property: "roles",
                    type: "array",
                    items: new Items(type: "string",example: "ROLE_USER"))
                ]))])]








    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($user);
        $this->manager->flush();

        return new JsonResponse(
            ['user' => $user->getUserIdentifier(), 'apiToken' => $user->getApiToken(), 'roles' => $user->getRoles()],
            Response::HTTP_CREATED
        );
    }


    #[Route('/login', name: 'login', methods: 'POST')]
    #[OA\Post(
        path:"/api/login",
        summary: "Inscription d'un nouvel utilisateur",
        requestBody: new RequestBody(
            required: true,
            description: "Données de l'utilisateur à inscrire",
            content: [new MediaType('application/json',
                schema: new schema(type: "object", properties: [new Property(
                    property: "username",
                    type: "string",
                    example: "adresse@email.com"
                ),
                    new Property(
                        property: "password",
                        type: "string",
                        example: "Mot de Passe"
                    )
                ]))]
        )
    )]

    #[OA\Response(
        response: 200,
        description: "Utilisateur connecté avec succès",
        content: [new MediaType('application/json',
            schema: new schema(type: "object", properties: [new Property(
                property: "user",
                type: "string",
                example: "Nom d'Utilisateur",
            ),
                new Property(
                    property: "apiToken",
                    type: "string",
                    example: "2d15f2re15621dfr5g1g51h215h147f45h12"
                ),
                new Property(
                    property: "roles",
                    type: "array",
                    items: new Items(type: "string",example: "ROLE_USER"))
            ]))])]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse(['message' => 'Missing credentials'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'user' => $user->getUserIdentifier(),
            'apiToken' => $user->getApiToken(),
            'roles' => $user->getRoles()
        ]);
    }

#[Route('/me', name: 'me', methods: 'GET')]
public function me(#[CurrentUser] ?User $user): JsonResponse
{
    if (null === $user) {
        return new JsonResponse(['message' => 'Missing User'], Response::HTTP_NO_CONTENT);
    }

    return new JsonResponse([
        'id' => $user->getUserIdentifier(),
        'user' => $user->getUserIdentifier(),
        'firstName' => $user->getFirstName(),
        'lastName' => $user->getLastName(),
        'guestNumber' => $user->getGuestNumber(),
        'email' => $user->getEmail(),
        'allergy' => $user->getAllergy(),
        'apiToken' => $user->getApiToken(),
        "userIdentifier" => $user->getUserIdentifier(),
        "roles" => $user->getRoles(),
        "createdAt" => $user->getCreatedAt(),
    "updatedAt" => $user->getUpdatedAt(),
    ]);
}


    #[Route('/account/edit/{id}', name: 'edit', methods: 'PUT')]

    public function edit(Request $request): JsonResponse
    {
        // Désérialiser les données JSON pour mettre à jour l'utilisateur
        $user = $this->serializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $this->getUser()],
        );
        // Mise à jour de la date de mise à jour
        $user->setUpdatedAt(new DateTimeImmutable());

        // Vérifier si un nouveau mot de passe est fourni et le hasher
        if (isset($request->toArray()['password'])) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        }
        // Flush pour sauvegarder les modifications
        $this->manager->flush();
        // Réponse si l'utilisateur n'est pas trouvé
        return new JsonResponse("User not found", Response::HTTP_NO_CONTENT);
    }

}
