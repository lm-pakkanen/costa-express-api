<?php

namespace Src\routes;

use Error;
use Src\models\APIResponse;

/**
 * Class Router
 * @package Src\routes
 */
class Router {

    /**
     *
     * Handles routing of API domains
     *
     * @param $path > Request path (/$domain/$path/...)
     * @param $method > Request METHOD (GET,POST,PUT,DELETE)
     * @param $params > Request parameters (request body)
     * @return APIResponse
     */
    public static function handleRequest($path, $method, $params): APIResponse {

        /** Split paths into array, remove first (empty) element */
        $pathsArray = explode('/', $path);
        $pathsArray = array_slice($pathsArray, 2);
        $routeDomain = $pathsArray[0];

        switch($routeDomain) {

            case 'emails': {
                $emailRouter = new EmailRouter();
                return $emailRouter->handleRequest($path, $method, $params);
            }

            default: {
                throw new Error('API route not found', 404);
            }

        }

    }

}