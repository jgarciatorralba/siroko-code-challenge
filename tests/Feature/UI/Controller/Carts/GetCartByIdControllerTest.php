<?php

declare(strict_types=1);

use App\Carts\Domain\Cart;
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

describe('GetCartByIdController', function () {
    it('should return a serialized cart given its id', function () {
        $id = '6fe80664-39e1-4e11-b3e3-b2303fa8868e';

        $client = $this->getApiClient();
        $response = $client->request('GET', "/api/carts/$id");
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_OK)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toHaveKeys([
                'id',
                'items',
                'product_count',
                'subtotal',
                'is_confirmed',
                'created_at',
                'updated_at'
            ])
            ->and($decodedResponse['id'])->toBeString()
            ->and($decodedResponse['items'])->toBeArray()
            ->and($decodedResponse['product_count'])->toBeInt()
            ->and(floatval($decodedResponse['subtotal']))->toBeFloat()
            ->and($decodedResponse['is_confirmed'])->toBeBool()
            ->and(new DateTimeImmutable($decodedResponse['created_at']))
                ->toBeInstanceOf(DateTimeImmutable::class)
            ->and(new DateTimeImmutable($decodedResponse['updated_at']))
                ->toBeInstanceOf(DateTimeImmutable::class);
    });

    it('should throw an exception if a cart is not found', function () {
        $id = Uuid::random()->value();

        $client = $this->getApiClient();
        $response = $client->request('GET', "/api/carts/$id");
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_NOT_FOUND)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toBe([
                'code' => 'cart_not_found',
                'error' => "Cart with id '$id' could not be found."
            ]);
    })->throws(ClientException::class);
});
