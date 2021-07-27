<?php

namespace Src\controllers;

use Error;

use Src\helpers\Validator;
use Src\models\APIResponse;


class EmailController {

    public function __construct()
    {

    }

    public function sendProposalRequestEmail($params): APIResponse {

        $isParamsValid = self::isParamsValid($params);

        if (!$isParamsValid) {
            throw new Error('Invalid values found in parameters', 400);
        }

        return new APIResponse(200, 'OK');
    }

    private static function isParamsValid($params): bool {

        // Required data missing
        if (empty($params)) {
            throw new Error('Required params not received');
        }

        $meta = $params['meta'];
        $sender = $params['sender'];
        $receiver = $params['receiver'];

        // Required data missing
        if (empty($meta) || empty($sender) || empty($receiver)) {
            throw new Error('Required data not received  (meta,sender,receiver)');
        }

        $deliveryStartDate = $params['deliveryStartDate'];

        $cargoDescription = $params['cargoDescription'];
        $message = $params['message'];

        $senderName = $sender['name'];
        $senderAddress = $sender['address'];
        $senderEmailAddress = $sender['emailAddress'];
        $senderPhone = $sender['phone'];

        $receiverAddress = $receiver['address'];
        $receiverPhone = $receiver['phone'];

        if (!Validator::isMobilePhone($senderPhone)) {
            throw new Error('Sender phone number is not valid', 400);
        }

        if (!Validator::isMobilePhone($receiverPhone)) {
            throw new Error('Receiver phone number is not valid', 400);
        }

        if (!Validator::isEmailAddress($senderEmailAddress)) {
            throw new Error('Sender email addres is not valid', 400);
        }

        if (!Validator::isDate($deliveryStartDate)) {
            throw new Error('Delivery start date is not valid', 400);
        }

        if (!Validator::isAddress($senderAddress)) {
            throw new Error('Sender address is not valid', 400);
        }

        if (!Validator::isAddress($receiverAddress)) {
            throw new Error('Receiver address is not valid', 400);
        }

        if (!Validator::isRequestProposalCargoDescriptionValid($cargoDescription)) {
            throw new Error('Cargo description is not valid', 400);
        }

        if (!Validator::isRequestProposalMessageValid($message)) {
            throw new Error('Message is not valid', 400);
        }

        if (!Validator::isPersonName($senderName)) {
            throw new Error('Sender name is not valid', 400);
        }

        return true;
    }

}