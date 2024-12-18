<?php

namespace App\Repository;

use App\Entity\Order;

interface OrderRepositoryInterface
{
    public function getOrders():Iterable;
}