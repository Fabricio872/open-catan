<?php

namespace App\Service;

use App\Controller\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;

class Response extends ApiController
{
    private array $errors = [];
    private string $message = "";

    public function addError(string $errorMessage): void
    {
        $this->errors[] = $errorMessage;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function ok(): bool
    {
        return ($this->errors == []);
    }

    public function getResponse(): JsonResponse
    {
        if ($this->errors == []) {
            return $this->respondWithSuccess($this->message);
        } else {
            return $this->respondWithErrors($this->errors);
        }
    }
}