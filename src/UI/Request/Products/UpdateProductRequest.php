<?php

declare(strict_types=1);

namespace App\UI\Request\Products;

use App\UI\Request\AbstractRequest;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateProductRequest extends AbstractRequest
{
    protected function validationRules(): Assert\Collection
    {
        return new Assert\Collection([
            'id' => new Assert\Required([
                new Assert\NotBlank(),
                new Assert\Type('string'),
                new Assert\Length(36)
            ]),
            'name' => new Assert\Optional([
                new Assert\NotBlank(),
                new Assert\Type('string')
            ]),
            'price' => new Assert\Optional([
                new Assert\NotBlank(),
                new Assert\Positive(),
                new Assert\Type('numeric')
            ])
        ]);
    }
}
