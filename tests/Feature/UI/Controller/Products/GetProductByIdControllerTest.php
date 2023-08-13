<?php

declare(strict_types=1);

use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use App\Tests\Unit\Products\Domain\ProductMother;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $testProduct = ProductMother::create(null, 'test-product-get-by-id', null, null, null);

    $this->persist($testProduct);
});

afterEach(function () {
    $this->clearDatabase();
});

describe('GetProductByIdController', function () {
    it('should return a serialized product given its id', function () {
        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-product-get-by-id']);
        $id = $testProduct->id()->value();

        $client = $this->getApiClient();
        $response = $client->request('GET', "/api/products/$id");
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_OK)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toHaveKeys(['id', 'name', 'price', 'created_at', 'updated_at'])
            ->and($decodedResponse['id'])->toBeString()
            ->and($decodedResponse['name'])->toBeString()
            ->and(floatval($decodedResponse['price']))->toBeFloat()
            ->and(new DateTimeImmutable($decodedResponse['created_at']))
                ->toBeInstanceOf(DateTimeImmutable::class)
            ->and(new DateTimeImmutable($decodedResponse['updated_at']))
                ->toBeInstanceOf(DateTimeImmutable::class);
    });

    it('should throw an exception if a product is not found', function () {
        $id = Uuid::random()->value();

        $client = $this->getApiClient();
        $response = $client->request('GET', "/api/products/$id");
        $decodedResponse = $response->toArray();

        expect($response->getStatusCode())->toEqual(Response::HTTP_NOT_FOUND)
            ->and($response->getContent())->toBeJson()
            ->and($decodedResponse)->toBeArray()
            ->and($decodedResponse)->toBe([
                'code' => 'product_not_found',
                'error' => "Product with id '$id' could not be found."
            ]);
    })->throws(ClientException::class);
});
