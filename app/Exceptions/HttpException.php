<?php

namespace App\Exceptions;

class HttpException extends \Exception
{
    private $httpStatusCode = 400;
    public $data = [];
    protected $message = null;

    public function __construct($httpStatusCode, $code, $message, $data = [])
    {
        $this->httpStatusCode = $httpStatusCode;
        
        $this->data = $data;
        $this->message = $message;
        

        parent::__construct($message, $code);
    }

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    public function getData()
    {
        return $this->data;
    }
    public function getErrorMessage()
    {
        return $this->message;
    }
}