<?php

namespace App\Event;

use App\Dto\GetOrderDto;
use Symfony\Contracts\EventDispatcher\Event;

class NewOrderEvent extends Event
{
    public const NAME = 'order.created';
    public function __construct(
        private GetOrderDto $orderDto
    ) {}

    public function getOrderDto(): GetOrderDto
    {
        return $this->orderDto;
    }
}