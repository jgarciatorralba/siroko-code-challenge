<?php

declare(strict_types=1);

use App\Carts\Domain\Cart;
use App\Carts\Domain\CartItem;
use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $testCart = new Cart(
        Uuid::fromString('6fe80664-39e1-4e11-b3e3-b2303fa8868e'),
        null,
        false,
        new DateTimeImmutable(),
        new DateTimeImmutable()
    );

    $this->persist($testCart);
});

afterEach(function () {
    $this->clearDatabase();
});

describe('DeleteCartByIdController', function () {
    it('should delete a cart given its id', function () {
        $id = '6fe80664-39e1-4e11-b3e3-b2303fa8868e';

        $client = $this->getApiClient();
        $response = $client->request('DELETE', "/api/carts/$id");

        expect($response->getStatusCode())->toEqual(Response::HTTP_NO_CONTENT)
            ->and($response->getContent())->toBeEmpty();
    });

    it('should throw an exception if a cart is not found', function () {
        $id = Uuid::random()->value();

        $client = $this->getApiClient();
        $response = $client->request('DELETE', "/api/carts/$id");
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_NOT_FOUND)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toBe([
                'code' => 'cart_not_found',
                'error' => "Cart with id '$id' could not be found."
            ]);
    })->throws(ClientException::class);

    it('should throw an exception if a cart was already confirmed', function () {
        $testCart = $this->repository(Cart::class)
            ->find('6fe80664-39e1-4e11-b3e3-b2303fa8868e');
        $id = $testCart->id()->value();

        $testProduct = new Product(
            Uuid::random(),
            'test-cart-delete',
            3.21,
            new DateTimeImmutable(),
            new DateTimeImmutable()
        );
        $this->persist($testProduct);

        $testCartItem = new CartItem(
            Uuid::random(),
            $testCart,
            $testProduct,
            1,
            new DateTimeImmutable(),
            new DateTimeImmutable()
        );
        $this->persist($testCartItem);

        $testCart->updateSubtotal($testCart->calculateSubtotal());
        $testCart->updateIsConfirmed(true);
        $testCart->addItem($testCartItem);
        $this->persist($testCart);

        $client = $this->getApiClient();
        $response = $client->request('DELETE', "/api/carts/$id");
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toBe([
                'code' => 'cart_already_confirmed',
                'error' => "Cart with id '$id' is already confirmed and cannot be modified."
            ]);
    })->throws(ClientException::class);
});
