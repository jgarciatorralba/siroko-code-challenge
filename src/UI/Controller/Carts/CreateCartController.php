<?php

declare(strict_types=1);

namespace App\UI\Controller\Carts;

use App\Shared\Domain\ValueObject\Uuid;
use App\UI\Controller\BaseController;
use App\UI\Request\Carts\CreateCartRequest;
use App\Carts\Application\Command\CreateCart\CreateCartCommand;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CreateCartController extends BaseController
{
    public function __invoke(CreateCartRequest $request): Response
    {
        $cartId = Uuid::random()->value();

        $data = $request->payload();
        foreach ($data['items'] as &$dataCartItem) {
            $dataCartItem['id'] = Uuid::random()->value();
        }

        $this->dispatch(new CreateCartCommand(
            id: $cartId,
            items: $data['items'],
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        ));

        return new JsonResponse(['id' => $cartId], Response::HTTP_CREATED);
    }
}
