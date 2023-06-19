<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    
    /**
     *@var Generator 
    */ 
    private Generator $faker;
    
    public function __contruct(){
        $this->faker = Factory::create('fr FR');
    }
    
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i<=50; $i++){
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                       ->setPrix(mt_rand(0, 100));
            
            $manager->persist($ingredient);
        }


        $manager->flush();
    }
}
