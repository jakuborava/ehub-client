<?php

namespace JakubOrava\EhubClient\Requests;

class VouchersListRequest extends BaseRequest
{
    public function type(string $type): self
    {
        return $this->addParam('type', $type);
    }

    public function isValid(bool $isValid): self
    {
        return $this->addParam('isValid', $isValid);
    }
}