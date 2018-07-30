<?php

namespace App\Exceptions;

class ResourceNotFoundException extends HttpException
{
    public function __construct($message =  'Resource not found.', $data = [])
    {
        parent::__construct(404, '33', $message, $data);
    }
}