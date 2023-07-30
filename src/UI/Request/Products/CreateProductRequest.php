<?php

declare(strict_types=1);

namespace App\UI\Request\Products;

use App\UI\Request\AbstractRequest;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateProductRequest extends AbstractRequest
{
    protected function validationRules(): Assert\Collection
    {
        return new Assert\Collection([
            'name' => new Assert\NotBlank(),
            'price' => [
                new Assert\NotBlank(),
                new Assert\Type('numeric'),
                new Assert\Positive()
            ],
        ]);
    }
}
