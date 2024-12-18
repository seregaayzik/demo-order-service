<?php

namespace App\Service;

use App\Dto\CreateOrderDto;
use App\Dto\GetOrderDto;
use App\Dto\ProductDto;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Event\NewOrderEvent;
use App\Repository\OrderRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Uid\Uuid;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        private readonly ProductServiceInterface $productService,
        private readonly InventoryServiceInterface $inventoryService,
        private readonly EntityManagerInterface $entityManager,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly EventDispatcherInterface $eventDispatcher,
    ){}
    public function postOrder(CreateOrderDto $orderDto): GetOrderDto
    {
        $arrayOfProducts = [];
        $this->entityManager->beginTransaction();
        try{
            $isProductQtyReserved = $this->inventoryService->decrease($orderDto->uuid,$orderDto->qty); // We will return the QTA later , by direct HTTP or by Queues.
            if (!$isProductQtyReserved) {
                throw new \RuntimeException('Failed to reserve product quantity');
            }
            $product = $this->productService->getProductById($orderDto->uuid);
            $product->qty = $orderDto->qty;
            $amount = $product->price * $orderDto->qty;
            $order = new Order();
            $order->setAmount($amount);
            $order->setQty($orderDto->qty);
            $this->entityManager->persist($order);
            $orderItem = new OrderItem();
            $orderItem->setQty($orderDto->qty);
            $orderItem->setName($product->name);
            $orderItem->setUuid(Uuid::fromString($product->id));
            $orderItem->setPrice($product->price);
            $orderItem->setOrderEntity($order);
            $this->entityManager->persist($orderItem);
            $this->entityManager->flush();
            $this->entityManager->commit();
            $arrayOfProducts[] = $product;
            $orderDto = new GetOrderDto($order->getUuid(),$arrayOfProducts,$order->getQty(),$amount);
            $event = new NewOrderEvent($orderDto);
            $this->eventDispatcher->dispatch($event, NewOrderEvent::NAME);
            return $orderDto;
        } catch (Exception $e) {
            throw new \RuntimeException('Failed to create the order');
            $this->entityManager->rollback();
        }
    }
    public function getOrders(): iterable
    {
        $result = [];
        $orders = $this->orderRepository->getOrders();
        /** @var Order $order */
        foreach ($orders as $order){
            $arrayOfProducts = [];
            foreach($order->getOrderItems() as $orderItem){
                $arrayOfProducts[] = new ProductDto($orderItem->getUuid(), $orderItem->getName(),$orderItem->getQty(),$orderItem->getPrice());
            }
            $result[] = new GetOrderDto($order->getUuid(),$arrayOfProducts,$order->getQty(),$order->getAmount());
        }
        return $result;
    }
}