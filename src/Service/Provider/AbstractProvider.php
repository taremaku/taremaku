<?php

declare(strict_types=1);

namespace App\Service\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractProvider
{
    protected string $providerName = '';

    protected string $providerApiUrl = '';

    protected ?string $providerApiKey = '';

    protected ?string $providerApiSecret = '';

    public function __construct(
        protected HttpClientInterface $httpClient,
        protected EntityManagerInterface $em
    ) {
    }

    protected function doRequest(string $method, string $route): ResponseInterface
    {
        return $this->httpClient->request(
            $method,
            $this->providerApiUrl . $route,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->providerApiKey
                ]
            ]
        );
    }
}
