<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;


class AppFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$faker = Faker\Factory::create('fr_FR');

		for ($i=0; $i<50; $i++){
			$ingretient = new Ingredient();
			$ingretient->setName($faker->word)
				->setPrice(mt_rand(0,100));
			$manager->persist($ingretient);
		}
		// $product = new Product();
		// $manager->persist($product);

		$manager->flush();
	}
}
