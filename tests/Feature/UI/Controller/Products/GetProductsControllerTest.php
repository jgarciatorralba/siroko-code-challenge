<?php

declare(strict_types=1);

use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $testProduct = new Product(
        Uuid::random(),
        'test-product-create',
        3.21,
        new DateTimeImmutable(),
        new DateTimeImmutable()
    );

    $this->persist($testProduct);
});

afterEach(function () {
    $testProduct = $this->repository(Product::class)
        ->findOneBy(['name' => 'test-product-create']);

    if ($testProduct) {
        $this->remove($testProduct);
    }
});

describe('GetProductsController', function () {
    it('should return a list of serialized products', function () {
        $client = $this->getApiClient();
        $response = $client->request('GET', '/api/products');
        $decodedResponse = $response->toArray();
        $length = count($decodedResponse);

        expect($response->getStatusCode())->toEqual(Response::HTTP_OK)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse[rand(0, $length - 1)])
                ->toHaveKeys(['id', 'name', 'price', 'created_at', 'updated_at'])
            ->and($decodedResponse[rand(0, $length - 1)]['id'])->toBeString()
            ->and($decodedResponse[rand(0, $length - 1)]['name'])->toBeString()
            ->and(floatval($decodedResponse[rand(0, $length - 1)]['price']))->toBeFloat()
            ->and(new DateTimeImmutable($decodedResponse[rand(0, $length - 1)]['created_at']))
                ->toBeInstanceOf(DateTimeImmutable::class)
            ->and(new DateTimeImmutable($decodedResponse[rand(0, $length - 1)]['updated_at']))
                ->toBeInstanceOf(DateTimeImmutable::class);
    });
});
