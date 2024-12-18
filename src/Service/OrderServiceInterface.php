<?php

namespace App\Service;

use App\Dto\CreateOrderDto;
use App\Dto\GetOrderDto;
interface OrderServiceInterface
{
    public function postOrder(CreateOrderDto $productDto):GetOrderDto;
    public function getOrders():Iterable;
}