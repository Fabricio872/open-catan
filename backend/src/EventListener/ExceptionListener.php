<?php

namespace App\EventListener;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();;

        $response = new JsonResponse();
        $body = new \stdClass();
        $body->code = $exception->getCode();
        $body->message = $exception->getMessage();
        $body->file = $exception->getFile();
        $body->line = $exception->getLine();

        if ($this->params->get("kernel.environment") == "dev") {

            $body->file = $exception->getFile();
            $body->line = $exception->getLine();
            $body->trace = $exception->getTrace();
        }

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $body->code = $exception->getStatusCode();
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response->setJson(json_encode($body));

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}