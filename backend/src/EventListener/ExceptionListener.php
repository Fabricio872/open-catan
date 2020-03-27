<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();;

        $response = new JsonResponse();
        $body = new \stdClass();
        $body->code = $exception->getCode();
        $body->message = $exception->getMessage();
        $body->file = $exception->getFile();
        $body->line = $exception->getLine();

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $body->code = $exception->getStatusCode();
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $body->code = $exception->getStatusCode();
        }

        $response->setJson(json_encode($body));

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}