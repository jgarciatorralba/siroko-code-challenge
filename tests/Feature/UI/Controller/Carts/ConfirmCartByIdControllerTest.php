<?php

declare(strict_types=1);

use App\Carts\Domain\Cart;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Carts\Domain\CartItemMother;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Products\Domain\ProductMother;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $testCart = CartMother::create(
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

describe('ConfirmCartByIdController', function () {
    it('should confirm a cart given its id', function () {
        $testCart = $this->repository(Cart::class)
            ->find('6fe80664-39e1-4e11-b3e3-b2303fa8868e');
        $id = $testCart->id()->value();

        $testProduct = ProductMother::create();
        $this->persist($testProduct);

        $testCartItem = CartItemMother::create(
            null,
            $testCart,
            $testProduct,
            1,
            null,
            new DateTimeImmutable(),
            new DateTimeImmutable()
        );
        $this->persist($testCartItem);

        $testCart->addItem($testCartItem);
        $this->persist($testCart);

        $client = $this->getApiClient();
        $response = $client->request('PUT', "/api/carts/$id/confirm");
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_OK)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeEmpty();
    });

    it('should throw an exception if a cart is not found', function () {
        $id = Uuid::random()->value();

        $client = $this->getApiClient();
        $response = $client->request('PUT', "/api/carts/$id/confirm");
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

        $testProduct = ProductMother::create();
        $this->persist($testProduct);

        $testCartItem = CartItemMother::create(
            null,
            $testCart,
            $testProduct,
            1,
            null,
            new DateTimeImmutable(),
            new DateTimeImmutable()
        );
        $this->persist($testCartItem);

        $testCart->updateSubtotal($testCart->calculateSubtotal());
        $testCart->updateIsConfirmed(true);
        $testCart->addItem($testCartItem);
        $this->persist($testCart);

        $client = $this->getApiClient();
        $response = $client->request('PUT', "/api/carts/$id/confirm");
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toBe([
                'code' => 'cart_already_confirmed',
                'error' => "Cart with id '$id' is already confirmed and cannot be modified."
            ]);
    })->throws(ClientException::class);

    it('should throw an exception if a cart is empty', function () {
        $testCart = $this->repository(Cart::class)
            ->find('6fe80664-39e1-4e11-b3e3-b2303fa8868e');
        $id = $testCart->id()->value();

        $client = $this->getApiClient();
        $response = $client->request('PUT', "/api/carts/$id/confirm");
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_BAD_REQUEST)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toBe([
                'code' => 'empty_cart',
                'error' => "Cart with id '$id' is empty and cannot be confirmed."
            ]);
    })->throws(ClientException::class);
});
