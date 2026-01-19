<?php

namespace JakubOrava\EhubClient\Exceptions;

class ApiErrorException extends EhubClientException
{
    public function __construct(string $message = '', int $code = 0)
    {
        parent::__construct($message, $code);
    }
}
