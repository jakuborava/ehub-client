<?php

namespace JakubOrava\EhubClient;

use JakubOrava\EhubClient\Endpoints\PublisherEndpoints;

class EhubClient
{
    private BaseEhubClient $client;

    public function __construct()
    {
        $this->client = new BaseEhubClient;
    }

    public function publishers(): PublisherEndpoints
    {
        return new PublisherEndpoints($this->client);
    }
}
