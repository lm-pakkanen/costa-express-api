<?php

namespace Src\routes;

use Error;

class Router {

    /** Handles routing of API domains */
    public static function handleRequest($path, $method, $params) {

        /** Split paths into array, remove first (empty) element */
        $pathsArray = explode('/', $path);
        $pathsArray = array_slice($pathsArray, 1);
        $routeDomain = $pathsArray[0];

        switch($routeDomain) {

            case 'emails': {
                $emailRouter = new EmailRouter();
                return $emailRouter->handleRequest($path, $method, $params);
            }

            default: {
                return new Error('API route not found', 404);
            }

        }

    }

}