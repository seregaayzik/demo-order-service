<?php

namespace App\Service;

interface InventoryServiceInterface
{
    public function decrease(string $uuid,int $qty):bool;
}