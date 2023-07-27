<?php

declare(strict_types=1);

namespace App\UI\Fixture\DataFixtures;

use App\UI\Fixture\Factory\ProductFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class ProductFixtures extends Fixture
{
    private const NUM_PRODUCTS = 10;

    private const DATA = [
        'socks' => ['name' => 'Socks', 'price' => 3.49],
        'sport_shoes' => ['name' => 'Sport shoes', 'price' => 75.99],
        't_shirt' => ['name' => 'T-Shirt', 'price' => 10.20]
    ];

    public function load(ObjectManager $manager): void
    {
        // Add products with defined data
        $productFactory = ProductFactory::new();

        foreach (self::DATA as $key => $productData) {
            $product = $productFactory->createOne($productData);
            $this->addReference('product:' . $key, $product);
            $manager->persist($product);
        }

        // Add random users
        $products = $productFactory->createMany(number: self::NUM_PRODUCTS);

        foreach ($products as $key => $product) {
            $this->addReference('product:' . $key, $product);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
