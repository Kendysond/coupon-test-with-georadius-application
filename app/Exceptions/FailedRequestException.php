<?php

namespace App\Exceptions;

class FailedRequestException extends HttpException
{
    public function __construct($message = 'Something went wrong, contact site administrator.', $data = [])
    {
        parent::__construct(402, '42', $message , $data);
    }
}