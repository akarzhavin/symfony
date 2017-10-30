<?php

namespace Myindexd\CatalogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Myindexd\CatalogBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Faker;

class LoadProductsData extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $categories = $manager->getRepository('MyindexdCatalogBundle:Category')->findAll();

        $faker = Faker\Factory::create();
        for($i = 1; $i <= 100; $i++){
            $product = new Product();
            $product->setTitle('Product ' . $faker->name);
            $product->setSlug($faker->word . $i);
            $product->setPrice($faker->randomFloat($nbMaxDecimals = 2));
            $product->setDescription($faker->text);

            $product->addCategory($categories[mt_rand(0, count($categories) - 1)]);
            $manager->persist($product);
        }
        $manager->flush();
    }
}