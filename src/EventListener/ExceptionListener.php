<?php

namespace App\EventListener;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

class ExceptionListener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $env;

    public function __construct(LoggerInterface $logger, string $environment)
    {
        $this->logger = $logger;
        $this->env    = $environment;
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

        $this->logException($event, $exception);

        $response = new JsonResponse($data, $code);

        $event->setResponse($response);
    }

    protected function logException(GetResponseForExceptionEvent $event, Exception $exception)
    {
        $this->logger->error(
            json_encode([
                'status'     => $this->getStatusCode($exception),
                'exception'  => $exception->getMessage(),
                'parameters' => $event->getRequest()->request->all(),
            ]),
            [
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
                'headers' => $this->getHeaders($exception),
                'trace'   => $exception->getTraceAsString(),
            ]
        );
    }

    protected function getStatusCode(Exception $exception): int
    {
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
        }

        return $statusCode;
    }

    protected function getHeaders(Exception $exception): array
    {
        $headers = [];
        if ($exception instanceof HttpException) {
            $headers = $exception->getHeaders();
        }

        return $headers;
    }
}
