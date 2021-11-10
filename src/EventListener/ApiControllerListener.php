<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class ApiControllerListener
{
    public function onKernelRequest(RequestEvent $event) {
        $request = $event->getRequest();
        $controller = $request->attributes->get('_controller');
        if(!str_starts_with($controller, 'App\Controller\ApiController')) {
            return;
        }

        if ('application/json' !== $request->headers->get('Content-Type')) {
            throw new UnsupportedMediaTypeHttpException();
        }
    }
}
