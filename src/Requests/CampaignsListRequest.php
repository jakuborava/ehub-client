<?php

namespace JakubOrava\EhubClient\Requests;

class CampaignsListRequest extends BaseRequest
{
    public function name(string $name): self
    {
        return $this->addParam('name', $name);
    }

    /**
     * @param array<int, int> $categories
     */
    public function categories(array $categories): self
    {
        return $this->addParam('categories', implode(',', $categories));
    }

    public function status(string $status): self
    {
        return $this->addParam('status', $status);
    }
}
