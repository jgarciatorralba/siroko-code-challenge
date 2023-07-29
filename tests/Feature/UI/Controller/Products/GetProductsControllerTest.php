<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;

it('should return a list of serialized products', function () {
    $client = $this->getApiClient();
    $response = $client->request('GET', '/api/products');
    $content = $response->toArray();

    expect($response->getStatusCode())->toEqual(Response::HTTP_OK)
        ->and($content)->toBeArray()
        ->and($content)->toHaveCount(10)
        ->and($content[0])->toHaveKeys(['id', 'name', 'price', 'created_at', 'updated_at'])
        ->and($content[0]['id'])->toBeString()
        ->and($content[0]['name'])->toBeString()
        ->and($content[0]['price'])->toBeFloat()
        ->and(new DateTimeImmutable($content[0]['created_at']))->toBeInstanceOf(DateTimeImmutable::class)
        ->and(new DateTimeImmutable($content[0]['updated_at']))->toBeInstanceOf(DateTimeImmutable::class);
});
