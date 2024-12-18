<?php

namespace App\Service;

use App\Dto\ProductDto;

interface ProductServiceInterface
{
    public function getProductById($productId):?ProductDto;
}