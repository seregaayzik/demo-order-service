<?php

namespace App\Message;

final class UpdateProductIncomeMessage
{
     public function __construct(
         public string $uuid,
         public float $income
     ) {
     }
}
