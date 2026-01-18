<?php

declare(strict_types=1);

namespace App\Core;

class Result
{
    public bool $success;
    public mixed $data;
    public string $message;
    public int $statusCode;
    public mixed $errors;

    private function __construct(
        bool $success,
        mixed $data = null,
        string $message = '',
        int $statusCode = 200,
        mixed $errors = null
    ) {
        $this->success = $success;
        $this->data = $data;
        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->errors = $errors;
    }

    public static function success(
        mixed $data = null,
        string $message = 'Operation successful',
        int $statusCode = 200
    ): self {
        return new self(true, $data, $message, $statusCode);
    }

    public static function error(
        string $message = 'Operation failed',
        mixed $errors = null,
        int $statusCode = 400
    ): self {
        return new self(false, null, $message, $statusCode, $errors);
    }

    
    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function isError(): bool
    {
        return !$this->success;
    }
}