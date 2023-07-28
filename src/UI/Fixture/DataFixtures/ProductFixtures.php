<?php

declare(strict_types=1);

namespace App\UI\Fixture\DataFixtures;

use App\UI\Fixture\Factory\ProductFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class ProductFixtures extends Fixture
{
    private const DATA = [
        'socks' => ['name' => 'Socks', 'price' => 3.49],
        'sport_shoes' => ['name' => 'Sport shoes', 'price' => 75.99],
        't_shirt' => ['name' => 'T-Shirt', 'price' => 14.20],
        'helmet' => ['name' => 'Helmet', 'price' => 46.65],
        'glasses' => ['name' => 'Sun glasses', 'price' => 29.15],
        'watch' => ['name' => 'Watch', 'price' => 82.50],
        'maillot' => ['name' => 'Maillot', 'price' => 49.00],
        'leggings' => ['name' => 'Sport leggings', 'price' => 20.19],
        'coat' => ['name' => 'Rain coat', 'price' => 189.10],
        'hoodie' => ['name' => 'Hoodie', 'price' => 31.79]
    ];

    public function load(ObjectManager $manager): void
    {
        $productFactory = ProductFactory::new();

        foreach (self::DATA as $key => $productData) {
            $product = $productFactory->createOne($productData);
            $this->addReference('product:' . $key, $product);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
