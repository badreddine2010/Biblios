<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        for ($i = 1; $i <11; $i++) {
            $book = new Book();
            $book->setTitle($faker->sentence(3));
            $book->setAuthor($faker->name());
            $book->setSynopsis($faker->paragraph(2,true));
            $book->setStock(10);
            $book->setImage('livre'.$i.'.jpg');
            $book->setAvailable(10);
            $manager->persist($book);
        }

        $manager->flush();
    }
}
