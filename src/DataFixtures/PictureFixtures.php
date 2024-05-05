<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;


class PictureFixtures extends Fixture implements DependentFixtureInterface
{

    /** @throws Exception */
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 20; $i++) {
            $picture = (new Picture())
                ->setTittle("Image N°$i")
                ->setSlug("slug-article-title")
                ->setRestaurant($this->getReference(RestaurantFixtures::RESTAURANT_REFERENCE . random_int(1, 20)))
                ->setCreateAt(new \DateTimeImmutable());


            $manager->persist($picture);

        }



        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [RestaurantFixtures::class];
    }
}
