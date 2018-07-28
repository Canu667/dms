<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

class ExceptionListener
{
    /**
     * @var string
     */
    private $env;

    public function __construct(string $environment)
    {
        $this->env = $environment;
    }

    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();
        $code      = Response::HTTP_INTERNAL_SERVER_ERROR;

        if ($exception instanceof ValidatorException) {
            $code = Response::HTTP_BAD_REQUEST;
        } elseif ($exception instanceof HttpExceptionInterface) {
            $code = $exception->getStatusCode();
        }

        $data = [
            'message' => $exception->getMessage(),
            'code'    => $code,
        ];

        if ($this->env === 'dev' || $this->env === 'test') {
            $data['stack_trace'] = $exception->getTraceAsString();
        }

        $response = new JsonResponse($data, $code);

        $event->setResponse($response);
    }
}
