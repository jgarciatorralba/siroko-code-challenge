<?php

declare(strict_types=1);

namespace App\UI\Fixture\DataFixtures;

use App\Carts\Domain\Cart;
use App\UI\Fixture\Factory\CartItemFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class CartItemFixtures extends Fixture implements DependentFixtureInterface
{
    private const DATA = [
        [
            'cart' => 'cart:1',
            'product' => 'product:socks'
        ],
        [
            'cart' => 'cart:1',
            'product' => 'product:sport_shoes'
        ],
        [
            'cart' => 'cart:1',
            'product' => 'product:t_shirt'
        ],
        [
            'cart' => 'cart:3',
            'product' => 'product:helmet'
        ],
        [
            'cart' => 'cart:3',
            'product' => 'product:glasses'
        ],
        [
            'cart' => 'cart:3',
            'product' => 'product:watch'
        ],
        [
            'cart' => 'cart:4',
            'product' => 'product:maillot'
        ],
        [
            'cart' => 'cart:4',
            'product' => 'product:leggings'
        ],
        [
            'cart' => 'cart:5',
            'product' => 'product:coat'
        ],
        [
            'cart' => 'cart:5',
            'product' => 'product:hoodie'
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        $cartItemFactory = CartItemFactory::new();

        /** @var Cart[] $carts */
        $carts = [];

        foreach (self::DATA as $key => $item) {
            $cartItemAttributes = $this->buildAttributesFromItemData($item);
            $cartItem = $cartItemFactory->createOne($cartItemAttributes);
            $this->addReference('cartItem:' . $key, $cartItem);
            $manager->persist($cartItem);

            $cart = $cartItem->cart();
            $cart->addItem($cartItem);
            if (
                !in_array(
                    $cart->id()->value(),
                    array_map(fn (Cart $cart) => $cart->id()->value(), $carts)
                )
            ) {
                $carts[] = $cartItem->cart();
            }
        }

        foreach ($carts as $cart) {
            if ($cart->isConfirmed()) {
                $cart->updateSubtotal($cart->calculateSubtotal());
            }
        }

        $manager->flush();
    }

    /**
     * @return class-string[]
     */
    public function getDependencies(): array
    {
        return [
            ProductFixtures::class,
            CartFixtures::class
        ];
    }

    /**
     * @param array<string, mixed> $item
     * @return array<string, mixed>
     */
    private function buildAttributesFromItemData(array $item): array
    {
        if (isset($item['product'])) {
            $item['product'] = $this->getReference($item['product']);
        }
        if (isset($item['cart'])) {
            $item['cart'] = $this->getReference($item['cart']);
        }

        return $item;
    }
}
