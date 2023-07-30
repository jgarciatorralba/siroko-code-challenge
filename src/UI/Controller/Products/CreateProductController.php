<?php

declare(strict_types=1);

namespace App\UI\Controller\Products;

use App\Shared\Domain\ValueObject\Uuid;
use App\UI\Controller\BaseController;
use App\UI\Request\Products\CreateProductRequest;
use App\Products\Application\Command\CreateProduct\CreateProductCommand;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CreateProductController extends BaseController
{
    public function __invoke(CreateProductRequest $request): Response
    {
        $data = $request->payload();

        $id = Uuid::random()->value();

        $this->dispatch(new CreateProductCommand(
            id: $id,
            name: $data['name'],
            price: $data['price']
        ));

        return new JsonResponse(['id' => $id], Response::HTTP_CREATED);
    }
}
