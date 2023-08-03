<?php

declare(strict_types=1);

use App\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Uuid;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $testProduct = new Product(
        Uuid::random(),
        'test-product-delete',
        3.21,
        new DateTimeImmutable(),
        new DateTimeImmutable()
    );

    $this->persist($testProduct);
});

afterEach(function () {
    $this->clearDatabase();
});

describe('DeleteProductByIdController', function () {
    it('should delete a product given its id', function () {
        $testProduct = $this->repository(Product::class)
            ->findOneBy(['name' => 'test-product-delete']);
        $id = $testProduct->id()->value();

        $client = $this->getApiClient();
        $response = $client->request('DELETE', "/api/products/$id");

        expect($response->getStatusCode())->toEqual(Response::HTTP_NO_CONTENT)
            ->and($response->getContent())->toBeEmpty();
    });

    it('should throw an exception if a product is not found', function () {
        $id = Uuid::random()->value();

        $client = $this->getApiClient();
        $response = $client->request('DELETE', "/api/products/$id");
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
