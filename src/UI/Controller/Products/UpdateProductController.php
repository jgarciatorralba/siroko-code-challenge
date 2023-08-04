<?php

declare(strict_types=1);

namespace App\UI\Controller\Products;

use App\Products\Application\Command\UpdateProduct\UpdateProductCommand;
use App\UI\Controller\BaseController;
use App\UI\Request\Products\UpdateProductRequest;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

final class UpdateProductController extends BaseController
{
    public function __invoke(UpdateProductRequest $request): Response
    {
        $data = $request->payload();

        $this->dispatch(new UpdateProductCommand(
            id: $data['id'],
            name: $data['name'] ?? null,
            price: $data['price'] ?? null,
            updatedAt: new DateTimeImmutable()
        ));

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
