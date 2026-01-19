<?php

namespace JakubOrava\EhubClient\Endpoints;

use JakubOrava\EhubClient\BaseEhubClient;
use JakubOrava\EhubClient\Endpoints\Publishers\Campaigns;
use JakubOrava\EhubClient\Endpoints\Publishers\Creatives;
use JakubOrava\EhubClient\Endpoints\Publishers\OutboundClicks;
use JakubOrava\EhubClient\Endpoints\Publishers\Transactions;
use JakubOrava\EhubClient\Endpoints\Publishers\Vouchers;

class PublisherEndpoints
{
    public function __construct(
        private readonly BaseEhubClient $client
    ) {
    }

    public function campaigns(): Campaigns
    {
        return new Campaigns($this->client);
    }

    public function creatives(): Creatives
    {
        return new Creatives($this->client);
    }

    public function outboundClicks(): OutboundClicks
    {
        return new OutboundClicks($this->client);
    }

    public function transactions(): Transactions
    {
        return new Transactions($this->client);
    }

    public function vouchers(): Vouchers
    {
        return new Vouchers($this->client);
    }
}


