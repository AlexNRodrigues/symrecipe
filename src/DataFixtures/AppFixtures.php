<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('pt_BR');
    }

    public function load(ObjectManager $manager): void
    {
        // Users
        $users = [];
        for ($i=0; $i < 10; $i++) {
            $user = new User();
            $user->setFullName($this->faker->name)
                ->setNickname($this->faker->firstName())
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $users[] = $user;
            $manager->persist($user);
        }


        // Ingredients
        $ingredients = [];
        for ($i=0; $i < 50; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                ->setPrice(mt_rand(0, 100))
                ->setUser($users[mt_rand(0, count($users) -1 )]);

            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        // Recipes
        for ($j=0; $j < 25; $j++) {
            $recipe = new Recipe();
            $recipe->setName($this->faker->word())
                ->setTime(mt_rand(0,1) == 1 ? mt_rand(1, 1440) : null)
                ->setNbPeople(mt_rand(0,1) == 1 ? mt_rand(1, 100) : null)
                ->setDifficulty(mt_rand(0,1) == 1 ? mt_rand(1, 5) : 6)
                ->setDescription($this->faker->text(300))
                ->setPrice(mt_rand(0,1) == 1 ? mt_rand(1, 100000) : null)
                ->setIsFavorite(mt_rand(0,1) == 1 ? true : false)
                ->setIsPublic(mt_rand(0,1) == 1 ? true : false)
                ->setUser($users[mt_rand(0, count($users) -1 )]);

            for ($k=0; $k < mt_rand(5, 15) ; $k++) {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }

            $manager->persist($recipe);
        }


        $manager->flush();
    }
}
