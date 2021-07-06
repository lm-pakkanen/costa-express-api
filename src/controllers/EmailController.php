<?php

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class EmailController {

    public static function sendProposalRequestEmail($params) {

        $apiEndpoint = 'https://api.emailjs.com/api/v1.0/email/send';

        $isParamsValid = self::vaidateRequestProposalParams($params);

        if (!$isParamsValid) {
            return new Error('Invalid values found in $params', 400);
        }

        try {

            $emailClient = new Client();

            $emailClient->post($apiEndpoint, [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'user_id' => '',
                    'service_id' => '',
                    'template_id' => '',
                    'accessToken' => '',
                    'template_params' => self::getRequestProposalTemplateParams($params)
                ]
            ]);

        } catch (GuzzleException $exception) {
            return new Error($exception->getMessage(), $exception->getCode());
        }

        return new APIResponse(200, 'OK');
    }

    private static function vaidateRequestProposalParams($params) {

        return true;

    }

    private static function getRequestProposalTemplateParams($params): array {

        return [
            'senderFirstName' => $params['sender']['firstName'],
            'senderLastName' => $params['sender']['lastName'],
            'senderEmailAddress' => $params['sender']['emailAddress'],
            'deliveryStartDate' => $params['deliveryStartDate'],
            'pickupAddressStreet' => $params['pickupAddress']['street'],
            'pickupAddressZip' => $params['pickupAddress']['zip'],
            'pickupAddressCity' => $params['pickupAddress']['city'],
            'pickupAddressCountry' => $params['pickupAddress']['country'],
            'pickupPhone' => $params['pickupPhone'],
            'deliveryAddressStreet' => $params['deliveryAddress']['street'],
            'deliveryAddressZip' => $params['deliveryAddress']['zip'],
            'deliveryAddressCity' => $params['deliveryAddress']['city'],
            'deliveryAddressCountry' => $params['deliveryAddress']['country'],
            'deliveryPhone' => $params['deliveryPhone'],
            'cargoDescription' => $params['cargoDescription'],
            'messageContent' => $params['messageContent']
        ];

    }

}