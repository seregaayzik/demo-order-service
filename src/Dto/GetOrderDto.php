<?php

namespace App\Dto;
class GetOrderDto
{
    public function __construct(
        public string $id,
        /**
         * @param ProductDto[] $products
         */
        public array $product,
        public int $qty,
        public float $amount,
    ) {
    }
}