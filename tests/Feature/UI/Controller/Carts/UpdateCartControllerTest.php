<?php

declare(strict_types=1);

use App\Carts\Domain\Cart;
use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Carts\Domain\CartItemMother;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $testProduct = ProductMother::create(null, 'test-cart-update', null, null, null);
    $this->persist($testProduct);

    $testCart = CartMother::create(
        Uuid::fromString('6fe80664-39e1-4e11-b3e3-b2303fa8868e'),
        null,
        false,
        null,
        null
    );
    $this->persist($testCart);

    $testCartItem = CartItemMother::create(null, $testCart, $testProduct, null, null, null, null);
    $this->persist($testCartItem);
});

afterEach(function () {
    $this->clearDatabase();
});

describe('UpdateCartController', function () {
    it('should add a new item to a cart', function () {
        $newTestProduct = ProductMother::create();
        $this->persist($newTestProduct);

        $testCart = $this->repository(Cart::class)
            ->find('6fe80664-39e1-4e11-b3e3-b2303fa8868e');

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/carts', [
            'body' => json_encode([
                'id' => $testCart->id()->value(),
                'operations' => [
                    [
                        'operation' => 'add',
                        'productId' => $newTestProduct->id()->value(),
                        'quantity' => FakeValueGenerator::integer(1)
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_OK)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeEmpty();
    });

    it('should update an item of a cart', function () {
        $testCart = $this->repository(Cart::class)
            ->find('6fe80664-39e1-4e11-b3e3-b2303fa8868e');

        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-cart-update']);

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/carts', [
            'body' => json_encode([
                'id' => $testCart->id()->value(),
                'operations' => [
                    [
                        'operation' => 'update',
                        'productId' => $testProduct->id()->value(),
                        'quantity' => FakeValueGenerator::integer(1)
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_OK)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeEmpty();
    });

    it('should remove an item from a cart', function () {
        $testCart = $this->repository(Cart::class)
            ->find('6fe80664-39e1-4e11-b3e3-b2303fa8868e');

        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-cart-update']);

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/carts', [
            'body' => json_encode([
                'id' => $testCart->id()->value(),
                'operations' => [
                    [
                        'operation' => 'remove',
                        'productId' => $testProduct->id()->value()
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_OK)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeEmpty();
    });

    it('should throw an exception if a cart is already confirmed', function () {
        $testCart = $this->repository(Cart::class)
            ->find('6fe80664-39e1-4e11-b3e3-b2303fa8868e');
        $testCartId = $testCart->id()->value();

        $testCart->updateSubtotal($testCart->calculateSubtotal());
        $testCart->updateIsConfirmed(true);

        foreach ($testCart->items() as $testCartItem) {
            $testCartItem->updateSubtotal($testCartItem->calculateSubtotal());
        }
        $this->persist($testCart);

        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-cart-update']);

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/carts', [
            'body' => json_encode([
                'id' => $testCartId,
                'operations' => [
                    [
                        'operation' => FakeValueGenerator::randomElement(['add', 'update', 'remove']),
                        'productId' => $testProduct->id()->value(),
                        'quantity' => FakeValueGenerator::integer()
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toBe([
                'code' => 'cart_already_confirmed',
                'error' => "Cart with id '$testCartId' is already confirmed and cannot be modified."
            ]);
    })->throws(ClientException::class);

    it('should throw an exception if a cart is not found', function () {
        $id = Uuid::random()->value();

        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-cart-update']);

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/carts', [
            'body' => json_encode([
                'id' => $id,
                'operations' => [
                    [
                        'operation' => FakeValueGenerator::randomElement(['add', 'update', 'remove']),
                        'productId' => $testProduct->id()->value(),
                        'quantity' => FakeValueGenerator::integer()
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_NOT_FOUND)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toBe([
                'code' => 'cart_not_found',
                'error' => "Cart with id '$id' could not be found."
            ]);
    })->throws(ClientException::class);

    it('should throw an exception if a product is not found', function () {
        $id = Uuid::random()->value();

        $testCart = $this->repository(Cart::class)
            ->find('6fe80664-39e1-4e11-b3e3-b2303fa8868e');

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/carts', [
            'body' => json_encode([
                'id' => $testCart->id()->value(),
                'operations' => [
                    [
                        'operation' => FakeValueGenerator::randomElement(['add', 'update', 'remove']),
                        'productId' => $id,
                        'quantity' => FakeValueGenerator::integer()
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_NOT_FOUND)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toBe([
                'code' => 'product_not_found',
                'error' => "Product with id '$id' could not be found."
            ]);
    })->throws(ClientException::class);

    it('should throw an exception when adding an item which is already in a cart', function () {
        $testCart = $this->repository(Cart::class)
            ->find('6fe80664-39e1-4e11-b3e3-b2303fa8868e');
        $testCartId = $testCart->id()->value();

        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-cart-update']);
        $testProductId = $testProduct->id()->value();

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/carts', [
            'body' => json_encode([
                'id' => $testCartId,
                'operations' => [
                    [
                        'operation' => 'add',
                        'productId' => $testProductId,
                        'quantity' => FakeValueGenerator::integer()
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_CONFLICT)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toBe([
                'code' => 'cart_item_already_existing',
                'error' => "Cart item for product with id '"
                    . $testProductId
                    . "' already exists in cart with id '$testCartId'."
            ]);
    })->throws(ClientException::class);

    it('should throw an exception when updating an item which is not in a cart', function () {
        $testCart = $this->repository(Cart::class)
            ->find('6fe80664-39e1-4e11-b3e3-b2303fa8868e');
        $testCartId = $testCart->id()->value();

        $newTestProduct = ProductMother::create();
        $newTestProductId = $newTestProduct->id()->value();

        $this->persist($newTestProduct);

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/carts', [
            'body' => json_encode([
                'id' => $testCartId,
                'operations' => [
                    [
                        'operation' => 'update',
                        'productId' => $newTestProductId,
                        'quantity' => FakeValueGenerator::integer(1)
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_NOT_FOUND)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toBe([
                'code' => 'cart_item_not_found',
                'error' => "Cart item for product with id '"
                    . $newTestProductId
                    . "' could not be found in cart with id '$testCartId'."
            ]);
    })->throws(ClientException::class);

    it('should throw an exception when deleting an item which is not in a cart', function () {
        $testCart = $this->repository(Cart::class)
            ->find('6fe80664-39e1-4e11-b3e3-b2303fa8868e');
        $testCartId = $testCart->id()->value();

        $newTestProduct = ProductMother::create();
        $newTestProductId = $newTestProduct->id()->value();

        $this->persist($newTestProduct);

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/carts', [
            'body' => json_encode([
                'id' => $testCartId,
                'operations' => [
                    [
                        'operation' => 'remove',
                        'productId' => $newTestProductId
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_NOT_FOUND)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toBe([
                'code' => 'cart_item_not_found',
                'error' => "Cart item for product with id '"
                    . $newTestProductId
                    . "' could not be found in cart with id '$testCartId'."
            ]);
    })->throws(ClientException::class);

    it('should throw a validation error when a product is repeated', function () {
        $testCart = $this->repository(Cart::class)
            ->find('6fe80664-39e1-4e11-b3e3-b2303fa8868e');

        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-cart-update']);

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/carts', [
            'body' => json_encode([
                'id' => $testCart->id()->value(),
                'operations' => [
                    [
                        'operation' => FakeValueGenerator::randomElement(['add', 'update']),
                        'productId' => $testProduct->id()->value(),
                        'quantity' => FakeValueGenerator::integer(1)
                    ],
                    [
                        'operation' => 'remove',
                        'productId' => $testProduct->id()->value()
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_PRECONDITION_FAILED)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toHaveCount(1)
            ->and($decodedResponse)->toBe([
                'code' => 'validation_exception',
                'error' => 'Invalid request data.',
                'errors' => [
                    'productId' => 'This value cannot be used twice.'
                ]
            ]);
    })->throws(ClientException::class);

    it("should throw a validation error when a quantity is missing on an 'add' or 'update' operation", function () {
        $testCart = $this->repository(Cart::class)
            ->find('6fe80664-39e1-4e11-b3e3-b2303fa8868e');

        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-cart-update']);

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/carts', [
            'body' => json_encode([
                'id' => $testCart->id()->value(),
                'operations' => [
                    [
                        'operation' => FakeValueGenerator::randomElement(['add', 'update']),
                        'productId' => $testProduct->id()->value()
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_PRECONDITION_FAILED)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toHaveCount(1)
            ->and($decodedResponse)->toBe([
                'code' => 'validation_exception',
                'error' => 'Invalid request data.',
                'errors' => [
                    'operations' => "Quantity is required for 'add' or 'update' operations."
                ]
            ]);
    })->throws(ClientException::class);

    it('should throw a validation error when a field is missing', function () {
        $testCart = $this->repository(Cart::class)
            ->find('6fe80664-39e1-4e11-b3e3-b2303fa8868e');

        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-cart-update']);

        $client = $this->getApiClient();
        $response = $client->request('PUT', '/api/carts', [
            'body' => json_encode([
                'id' => $testCart->id()->value(),
                'operations' => [
                    [
                        'productId' => $testProduct->id()->value()
                    ]
                ]
            ])
        ]);
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_PRECONDITION_FAILED)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toHaveCount(1)
            ->and($decodedResponse)->toBe([
                'code' => 'validation_exception',
                'error' => 'Invalid request data.',
                'errors' => [
                    'operation' => 'This field is missing.'
                ]
            ]);
    })->throws(ClientException::class);
});
