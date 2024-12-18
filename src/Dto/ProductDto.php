<?php

namespace App\Dto;

class ProductDto
{
    public function __construct(
        public string $id,
        public string $name,
        public int $qty,
        public float $price,
    ) {
    }
}