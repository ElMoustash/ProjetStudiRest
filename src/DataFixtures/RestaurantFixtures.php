<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker;


class RestaurantFixtures extends Fixture
{
    public const RESTAURANT_REFERENCE = 'restaurant';
    public const RESTAURANT_NB_TUPLES =20;
    /** @throws Exception */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for($i = 1; $i <= self::RESTAURANT_NB_TUPLES; $i++) {
            $restaurant = (new Restaurant())
                ->setName($faker->company())
                ->setDescription($faker->realText())
                ->setAmOpening([])
                ->setPmOpening([])
                ->setMaxGuest(random_int(10, 50))
                ->setCreatedAt(new \DateTimeImmutable());


            $manager->persist($restaurant);
            $this->addReference(self::RESTAURANT_REFERENCE.$i, $restaurant);
        }



        $manager->flush();
    }

}
