<?php

namespace App\EventListener;

use App\Dto\ProductDto;
use App\Event\NewOrderEvent;
use App\Message\UpdateProductIncomeMessage;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

final class UpdateProductIncomeListener
{
    public function __construct(
        private readonly MessageBusInterface $bus
    ){}
    #[AsEventListener(event: 'order.created')]
    public function onOrderCreated(NewOrderEvent $event): void
    {
        $order = $event->getOrderDto();
        /** @var ProductDto $product */
        foreach ($order->product as $product){
            $income = $product->qty * $product->price;
            $message = new UpdateProductIncomeMessage($product->id,$income);
            $this->bus->dispatch($message);
        }
    }
}
