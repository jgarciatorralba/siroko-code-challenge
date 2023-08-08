<?php

declare(strict_types=1);

namespace App\UI\Controller\Carts;

use App\UI\Controller\BaseController;
use App\Carts\Application\Command\ConfirmCartById\ConfirmCartByIdCommand;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ConfirmCartByIdController extends BaseController
{
    public function __invoke(string $id): Response
    {
        $this->dispatch(new ConfirmCartByIdCommand(id: $id));

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
