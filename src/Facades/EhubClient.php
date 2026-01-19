<?php

namespace JakubOrava\EhubClient\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JakubOrava\EhubClient\EhubClient
 */
class EhubClient extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JakubOrava\EhubClient\EhubClient::class;
    }
}
