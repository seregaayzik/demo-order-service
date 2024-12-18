<?php

namespace App\Service;

use App\Service\InventoryServiceInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class InventoryService implements InventoryServiceInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ContainerBagInterface $params
    ) {
    }
    public function decrease(string $uuid, int $qty): bool
    {
        $response = $this->httpClient->request('POST', $this->params->get('product_service_url') . "inventory/$uuid/decrease", [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'qty' => $qty
            ],
        ]);
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new NotFoundHttpException('Status code: ' . $response->getStatusCode());
        }
        $data = $response->toArray(true);
        if(isset($data['success']) && $data['success'] === true){
            return true;
        }
        return false;
    }
}