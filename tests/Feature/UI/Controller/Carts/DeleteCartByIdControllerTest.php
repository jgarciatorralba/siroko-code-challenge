<?php

declare(strict_types=1);

use App\Carts\Domain\Cart;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Carts\Domain\CartItemMother;
use App\Tests\Unit\Carts\Domain\CartMother;
use App\Tests\Unit\Products\Domain\ProductMother;
use App\Tests\Unit\Shared\Domain\FakeValueGenerator;
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

describe('DeleteCartByIdController', function () {
    it('should delete a cart given its id', function () {
        $id = '6fe80664-39e1-4e11-b3e3-b2303fa8868e';

        $client = $this->getApiClient();
        $response = $client->request('DELETE', "/api/carts/$id");

        expect($response->getStatusCode())->toEqual(Response::HTTP_NO_CONTENT)
            ->and($response->getContent())->toBeEmpty();
    });

    it('should throw an exception if a cart is not found', function () {
        $id = FakeValueGenerator::uuid()->value();

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

        $testProduct = ProductMother::create();
        $this->persist($testProduct);

        $testCartItem = CartItemMother::create(null, $testCart, $testProduct, null, null, null, null);
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
