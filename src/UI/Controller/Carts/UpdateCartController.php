<?php

declare(strict_types=1);

namespace App\UI\Controller\Carts;

use App\Carts\Application\Command\UpdateCart\UpdateCartCommand;
use App\UI\Controller\BaseController;
use App\UI\Request\Carts\UpdateCartRequest;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

final class UpdateCartController extends BaseController
{
    public function __invoke(UpdateCartRequest $request): Response
    {
        $data = $request->payload();

        $this->dispatch(new UpdateCartCommand(
            id: $data['id'],
            operations: $data['operations'],
            updatedAt: new DateTimeImmutable()
        ));

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
