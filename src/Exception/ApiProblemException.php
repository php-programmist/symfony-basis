<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiProblemException extends HttpException
{
    /**
     * @var array
     */
    protected $errors;
    
    public function __construct(
        int $statusCode,
        string $message = null,
        array $errors = [],
        \Throwable $previous = null,
        array $headers = [],
        ?int $code = 0
    ) {
    
        $this->errors = $errors;
        foreach ($errors as $error) {
            $message.="\r\n - ".$error;
        }
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
    
    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
    
}