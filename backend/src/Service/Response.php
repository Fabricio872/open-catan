<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

class Response
{
    private static $instance;
    private array $errors = [];
    private string $message = "";

    public static function inst(int $seed = null)
    {
        if (self::$instance == null) {
            self::$instance = new Response();
        }

        return self::$instance;
    }

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

            $response = [
                "status" => "success",
                "message" => $this->message,
            ];
        } else {
            $response = [
                "status" => "failed",
                "errors" => $this->errors,
            ];
        }

        return new JsonResponse($response, ($response["status"] == "failed") ? 400 : 200);
    }
}