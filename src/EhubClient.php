<?php

namespace JakubOrava\EhubClient;

use JakubOrava\EhubClient\Endpoints\PublisherEndpoints;

class EhubClient
{
    private BaseEhubClient $client;

    public function __construct(?string $apiKey = null)
    {
        $this->client = new BaseEhubClient($apiKey);
    }

    public function publishers(): PublisherEndpoints
    {
        return new PublisherEndpoints($this->client);
    }
}
