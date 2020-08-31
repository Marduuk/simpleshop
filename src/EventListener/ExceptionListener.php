<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Class ExceptionListener
 * @package App\EventListener
 */
class ExceptionListener
{
    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $data = [
            'success' => false,
            'error' => $exception->getMessage(),
        ];

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else if ($exception instanceof ValidationException){
            $data['error'] = $exception->getJoinedMessages();
            $response->setStatusCode(400);
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            $data['error'] = 'Something went wrong.';
        }

        $response->setContent(json_encode($data));
        $event->setResponse($response);
    }
}