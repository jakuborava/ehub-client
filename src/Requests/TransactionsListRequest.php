<?php

namespace JakubOrava\EhubClient\Requests;

class TransactionsListRequest extends BaseRequest
{
    public function dateInsertedFrom(string $dateInsertedFrom): self
    {
        return $this->addParam('dateInsertedFrom', $dateInsertedFrom);
    }

    public function dateInsertedTo(string $dateInsertedTo): self
    {
        return $this->addParam('dateInsertedTo', $dateInsertedTo);
    }

    public function campaignId(string $campaignId): self
    {
        return $this->addParam('campaignId', $campaignId);
    }

    public function creativeId(string $creativeId): self
    {
        return $this->addParam('creativeId', $creativeId);
    }

    public function type(string $type): self
    {
        return $this->addParam('type', $type);
    }

    public function code(string $code): self
    {
        return $this->addParam('code', $code);
    }

    public function orderId(string $orderId): self
    {
        return $this->addParam('orderId', $orderId);
    }

    public function productId(string $productId): self
    {
        return $this->addParam('productId', $productId);
    }

    public function newCustomer(bool $newCustomer): self
    {
        return $this->addParam('newCustomer', $newCustomer);
    }

    public function resolutionFrom(string $resolutionFrom): self
    {
        return $this->addParam('resolutionFrom', $resolutionFrom);
    }

    public function resolutionTo(string $resolutionTo): self
    {
        return $this->addParam('resolutionTo', $resolutionTo);
    }

    public function status(string $status): self
    {
        return $this->addParam('status', $status);
    }

    public function payoutStatus(string $payoutStatus): self
    {
        return $this->addParam('payoutStatus', $payoutStatus);
    }
}