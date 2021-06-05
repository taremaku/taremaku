<?php

declare(strict_types=1);

namespace App\Service\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProviderService
{
    public function __construct(
        private string $providerApi,
        private string $providerApiKey,
        private string $providerApiSecret,
        private HttpClientInterface $httpClient,
        private EntityManagerInterface $em
    ) {
    }

    public function getProvider(): AbstractProvider
    {
        $providerClass = 'App\\Service\\Provider\\' . $this->providerApi . 'Client';
        $apiProvider = new $providerClass($this->providerApiKey, $this->providerApiSecret, $this->httpClient, $this->em);

        return $apiProvider;
    }
}
