<?php

namespace App\Dto;
use Symfony\Component\Validator\Constraints as Assert;

class CreateOrderDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public readonly string $uuid,

        #[Assert\NotBlank]
        #[Assert\GreaterThanOrEqual(1)]
        #[Assert\LessThanOrEqual(100)]
        public readonly int $qty,
    ) {
    }
}