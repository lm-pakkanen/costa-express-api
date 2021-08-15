<?php

namespace Src\routes;

use Error;

use Src\controllers\EmailController;
use Src\models\APIResponse;

require_once __DIR__ . '/../controllers/EmailController.php';

/**
 * Class EmailRouter
 * @package Src\routes
 */
class EmailRouter {

    /**
     *
     * Handles request sent to /emails/*
     *
     * @param $path
     * @param $method
     * @param $params
     * @return APIResponse
     */
    public static function handleRequest($path, $method, $params): APIResponse {

        /** Split paths into array, remove first (empty) element & path domain */
        $pathsArray = explode('/', $path);
        $pathsArray = array_slice($pathsArray, 3);
        $route = $pathsArray[0];

        switch($route) {

            case 'templates': {

                $templateID = $pathsArray[1];
                $action = $pathsArray[2];

                if (empty($templateID)) {
                    throw new Error('Required parameter missing (templateID)', 400);
                }

                if (empty($action)) {
                    throw new Error('Required parameter missing (action)', 400);
                }

                switch ($action) {

                    case 'send': {

                        if ($method !== 'POST') {
                            throw new Error('Requested method not supported for this action', 400);
                        }

                        $emailController = new EmailController();

                        $emailController->sendEmail($templateID, $params);

                        return new APIResponse(200, 'OK');

                    }

                    default: {
                        throw new Error('Action not supported for templates', 400);
                    }

                }

            }

            default: {
                throw new Error('API route not found', 404);
            }

        }

    }

}