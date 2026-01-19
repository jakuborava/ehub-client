<?php

namespace JakubOrava\EhubClient\Requests;

class CreativesListRequest extends BaseRequest
{
    public function id(string $id): self
    {
        return $this->addParam('id', $id);
    }

    public function campaignId(string $campaignId): self
    {
        return $this->addParam('campaignId', $campaignId);
    }

    public function type(string $type): self
    {
        return $this->addParam('type', $type);
    }

    public function name(string $name): self
    {
        return $this->addParam('name', $name);
    }
}