<?php

declare(strict_types=1);

namespace App\UI\Fixture\DataFixtures;

use App\Shared\Domain\ValueObject\Uuid;
use App\UI\Fixture\Factory\CartFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class CartFixtures extends Fixture
{
    private const DATA = [
        1 => ['isConfirmed' => false],
        2 => ['isConfirmed' => false],
        3 => ['isConfirmed' => true],
        4 => ['isConfirmed' => false],
        5 => ['isConfirmed' => true]
    ];

    public function load(ObjectManager $manager): void
    {
        $cartFactory = CartFactory::new();

        foreach (self::DATA as $key => $cartData) {
            if ($key === 1) {
                $cartData['id'] = Uuid::fromString('e5bdb851-d631-4dcb-bb76-d0f4bac1207f');
            }
            $cart = $cartFactory->createOne($cartData);
            $this->addReference('cart:' . $key, $cart);
            $manager->persist($cart);
        }

        $manager->flush();
    }
}
