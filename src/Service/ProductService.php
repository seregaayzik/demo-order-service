<?php

namespace App\Service;

use App\Dto\ProductDto;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly ContainerBagInterface $params
    ) {
    }
    public function getProductById($productId): ?ProductDto
    {
        $response = $this->httpClient->request('GET', $this->params->get('product_service_url') . "product/$productId");
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new NotFoundHttpException('Status code: ' . $response->getStatusCode());
        }
        $data = json_decode($response->getContent(true));
        if(!$data?->data?->id){
            throw new NotFoundHttpException('Product is not available!');
        }
        return new ProductDto($data->data->id, $data->data->name,$data->data->qty,$data->data->price);
    }
}