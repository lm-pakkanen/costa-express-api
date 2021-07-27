<?php

namespace Src\controllers;

use Error;

use Src\helpers\Validator;
use Src\models\APIResponse;


/**
 * Class EmailController
 * @package Src\controllers
 */
class EmailController {

    /**
     * EmailController constructor.
     */
    public function __construct()
    {

    }

    /**
     *
     * Handles sending email
     *
     * @param $templateID > Email template ID
     * @param $params > Email template parameters
     * @throws Error
     * @return APIResponse
     */
    public function sendEmail($templateID, $params): APIResponse {

        if (!self::isTemplateParamsValid($templateID, $params)) {
            throw new Error('Template parameters invalid', 400);
        }

        // TODO: Handle sending

        return new APIResponse(200, 'OK');
    }

    /**
     *
     * Checks if template parameters are valid
     *
     * @param $templateID > Email template ID
     * @param $params > Email template parameters
     * @throws Error
     * @return bool
     */
    private static function isTemplateParamsValid($templateID, $params): bool {

        if (empty($templateID)) {
            throw new Error('Required parameter missing (templateID');
        }

        switch ($templateID) {

            case 'requestProposal': {
                return Validator::isRequestProposalParamsValid($params);
            }

            default: {
                throw new Error('Invalid templateID', 400);
            }

        }

    }

}