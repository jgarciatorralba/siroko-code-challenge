<?php

declare(strict_types=1);

use App\Shared\Domain\ValueObject\Uuid;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;

it('should return a serialized product given its id', function () {
    $id = 'df7e424d-26e8-46c9-8289-c75d7da1900c';

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
    $id = Uuid::random();

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
