<?php

namespace App\Exceptions;

class InvalidRequestException extends HttpException
{
    public function __construct($data = [], $code = '40',$http_code = 400) // Should this be 40 or 400? 
    {
        parent::__construct($http_code, $code, 'Invalid request.', $data);
    }
}