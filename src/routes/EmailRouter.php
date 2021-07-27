<?php

namespace Src\routes;

use Error;

use Src\controllers\EmailController;
use Src\models\APIResponse;

require_once __DIR__ . '/../controllers/EmailController.php';

class EmailRouter {

    public static function handleRequest($path, $method, $params) {

        /** Split paths into array, remove first (empty) element & path domain */
        $pathsArray = explode('/', $path);
        $pathsArray = array_slice($pathsArray, 2);
        $route = $pathsArray[0];

        switch($route) {

            case 'send': {

                if ($method === 'POST') {


                    $emailController = new EmailController();

                    try {
                        $emailController->sendProposalRequestEmail($params);
                    } catch (Error $error) {
                        return $error;
                    }

                    return new APIResponse(200, 'OK');

                }

                return new Error('This route does not support requested method');
            }

            default: {
                return new Error('API route not found', 404);
            }

        }

    }

}