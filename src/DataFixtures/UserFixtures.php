<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public const USER_NB_TUPLES =20;
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {

    }
    /** @throws Exception */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for($i = 1; $i <= self::USER_NB_TUPLES; $i++) {
            $user = (new User())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail($faker->email())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setGuestNumber(random_int(1, 10));

            $user->setPassword($this->passwordHasher->hashPassword($user, "password$i"));


            $manager->persist($user);
        }



        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['independent', 'user'];
    }
}
