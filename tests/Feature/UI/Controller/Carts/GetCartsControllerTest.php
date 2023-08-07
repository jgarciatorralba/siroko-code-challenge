<?php

declare(strict_types=1);

use App\Carts\Domain\Cart;
use App\Shared\Domain\ValueObject\Uuid;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $testCart = Cart::create(
        Uuid::random(),
        new DateTimeImmutable(),
        new DateTimeImmutable()
    );

    $this->persist($testCart);
});

afterEach(function () {
    $this->clearDatabase();
});

describe('GetCartsController', function () {
    it('should return a list of serialized carts', function () {
        $client = $this->getApiClient();
        $response = $client->request('GET', '/api/carts');
        $decodedResponse = $response->toArray();
        $length = count($decodedResponse);

        expect($response->getStatusCode())->toEqual(Response::HTTP_OK)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse[rand(0, $length - 1)])
                ->toHaveKeys([
                    'id',
                    'items',
                    'product_count',
                    'subtotal',
                    'is_confirmed',
                    'created_at',
                    'updated_at'
                ])
            ->and($decodedResponse[rand(0, $length - 1)]['id'])->toBeString()
            ->and($decodedResponse[rand(0, $length - 1)]['items'])->toBeArray()
            ->and($decodedResponse[rand(0, $length - 1)]['product_count'])->toBeInt()
            ->when(
                $subtotal = $decodedResponse[rand(0, $length - 1)]['subtotal'] !== null,
                fn ($subtotal) => $subtotal->toBeNumeric()
            )
            ->and($decodedResponse[rand(0, $length - 1)]['is_confirmed'])->toBeBool()
            ->and(new DateTimeImmutable($decodedResponse[rand(0, $length - 1)]['created_at']))
                ->toBeInstanceOf(DateTimeImmutable::class)
            ->and(new DateTimeImmutable($decodedResponse[rand(0, $length - 1)]['updated_at']))
                ->toBeInstanceOf(DateTimeImmutable::class);
    });
});
