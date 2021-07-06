<?php

require_once 'EmailRouter.php';

class Router {

    public function __construct()
    {

    }

    /** Handles routing of API domains */
    public function handleRequest($path, $method, $params = false) {

        $domain = substr($path, 1, strpos($path, '/') - 1);

        switch($domain) {

            case 'emails':

                $emailRouter = new EmailRouter();
                return $emailRouter->handleRequest($path, $method, $params);

            default:
                return new Error('API route not found', 404);
        }

    }

}