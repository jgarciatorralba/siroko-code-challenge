<?php

declare(strict_types=1);

namespace App\UI\Controller\Carts;

use App\UI\Controller\BaseController;
use App\Carts\Application\Command\DeleteCartById\DeleteCartByIdCommand;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

final class DeleteCartByIdController extends BaseController
{
    public function __invoke(string $id): Response
    {
        $this->dispatch(new DeleteCartByIdCommand(id: $id));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
