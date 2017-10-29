<?php

namespace Myindexd\CatalogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Myindexd\CatalogBundle\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Faker;

class LoadCategoriesData extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        for($i = 1; $i <= 15; $i++){
            $category = new Category();
            $category->setTitle('Category ' . $faker->name);
            $category->setSlug($faker->word . $i);

            $manager->persist($category);
        }
        $manager->flush();
    }
}