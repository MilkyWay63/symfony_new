<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        // Get incoming request
        $request = $event->getRequest();
        $prevException = $exception->getPrevious();

        // Check if it is a rest api request
        if ('application/json' === $request->headers->get('Content-Type')) {

            // Customize your response object to display the exception details
            $response = new JsonResponse([
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'traces' => $exception->getTrace()
            ]);

            // HttpExceptionInterface is a special type of exception that
            // holds status code and header details
            if ($exception instanceof HttpExceptionInterface && !$prevException instanceof ValidationFailedException) {
                $response->setStatusCode($exception->getStatusCode());
                $response->headers->replace($exception->getHeaders());

            } elseif ($exception instanceof HttpExceptionInterface && $prevException instanceof ValidationFailedException) {
                $errors = [];
                foreach ($prevException->getViolations() as $violation) {
                    $errors[$violation->getPropertyPath()] = $violation->getMessage();
                }
                $response = new JsonResponse([
                    'errors' => $errors
                ], Response::HTTP_BAD_REQUEST);
            } else {

                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            // sends the modified response object to the event
            $event->setResponse($response);
        } else {
            if ($exception instanceof NotFoundHttpException) {
                //entity not found by value resolver

                if ($exception->getFile() === '/app/vendor/symfony/doctrine-bridge/ArgumentResolver/EntityValueResolver.php') {
                    $response = new JsonResponse([
                        'errors' => [
                            'message' => 'Entity not found',
                        ]
                    ], Response::HTTP_BAD_REQUEST);

                    $event->setResponse($response);
                }
            }
        }
    }
}
