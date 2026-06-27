<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function callAction($method, $parameters)
    {
        unset($parameters['locale']);
        return $this->{$method}(...array_values($parameters));
    }
}
