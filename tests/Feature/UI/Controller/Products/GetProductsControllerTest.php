<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;

it('should return a list of serialized products', function () {
    $client = $this->getApiClient();
    $response = $client->request('GET', '/api/products');
    $decodedResponse = $response->toArray();

    expect($response->getStatusCode())->toEqual(Response::HTTP_OK)
        ->and($response->getContent())->toBeJson()
        ->and($decodedResponse)->toBeArray()
        ->and($decodedResponse)->toHaveCount(10)
        ->and($decodedResponse[rand(0, 9)])->toHaveKeys(['id', 'name', 'price', 'created_at', 'updated_at'])
        ->and($decodedResponse[rand(0, 9)]['id'])->toBeString()
        ->and($decodedResponse[rand(0, 9)]['name'])->toBeString()
        ->and(floatval($decodedResponse[rand(0, 9)]['price']))->toBeFloat()
        ->and(new DateTimeImmutable($decodedResponse[0]['created_at']))
            ->toBeInstanceOf(DateTimeImmutable::class)
        ->and(new DateTimeImmutable($decodedResponse[0]['updated_at']))
            ->toBeInstanceOf(DateTimeImmutable::class);
});
