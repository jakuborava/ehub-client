<?php

namespace JakubOrava\EhubClient\Requests;

class OutboundClicksListRequest extends BaseRequest
{
    public function from(string $from): self
    {
        return $this->addParam('from', $from);
    }

    public function to(string $to): self
    {
        return $this->addParam('to', $to);
    }

    public function campaignId(string $campaignId): self
    {
        return $this->addParam('campaignId', $campaignId);
    }

    public function clickData1(string $clickData1): self
    {
        return $this->addParam('clickData1', $clickData1);
    }

    public function clickData2(string $clickData2): self
    {
        return $this->addParam('clickData2', $clickData2);
    }

    public function linkId(string $linkId): self
    {
        return $this->addParam('linkId', $linkId);
    }

    public function processed(bool $processed): self
    {
        return $this->addParam('processed', $processed);
    }
}
