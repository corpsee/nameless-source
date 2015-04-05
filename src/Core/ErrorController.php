<?php

namespace Nameless\Core;

use Symfony\Component\Debug\Exception\FlattenException;

class ErrorController extends Controller
{
    public function error(FlattenException $exception)
    {
        $data = [
            'styles' => ['/files/lib/bootstrap/3.2.0/css/bootstrap.css'],
        ];

        return $this->render($exception->getStatusCode(), $data, Template::FILTER_ESCAPE, [], null, dirname(__DIR__) . '/Core/templates/');
    }
}
