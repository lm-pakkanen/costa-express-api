<?php

namespace Src\controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
     */
    public function sendEmail($templateID, $params) {

        if (!self::isTemplateParamsValid($templateID, $params)) {
            throw new Error('Template parameters invalid', 400);
        }

        $mailer = new PHPMailer(getenv('ENVIRONMENT') === 'dev');

        $mailer->isSMTP();
        $mailer->Host = 'smtp.qnet.fi';
        $mailer->Port = 25;

        $mailer->setFrom('yhteydenotto@costaexpress.fi', 'CostaExpress');

        $customerEmailAddress = $params['sender']['emailAddress'];
        $customerName = $params['sender']['name'];
        $customerName = $customerName['firstName'] . ' ' . $customerName['lastName'];

        $mailer->addReplyTo(htmlspecialchars($customerEmailAddress), htmlspecialchars($customerName));

        $mailer->addAddress('yhteydenotto@costaexpress.fi', 'CostaExpress');

        $messageSubject = 'CostaExpress | Yhteydenotto sivustolta';

        $mailer->Subject = htmlspecialchars($messageSubject);

        $messageBody = file_get_contents(__DIR__ . '/../emailTemplates/requestProposal.html');

        $messageBody = str_replace('{{messageSubject}}', htmlspecialchars($messageSubject), $messageBody);

        $sender = $params['sender'];
        $senderName = $sender['name'];
        $senderAddress = $sender['address'];

        $senderFirstName = $senderName['firstName'];
        $senderLastName = $senderName['lastName'];
        $senderEmailAddress = $sender['emailAddress'];

        $messageBody = str_replace('{{senderFirstName}}', htmlspecialchars($senderFirstName), $messageBody);
        $messageBody = str_replace('{{senderLastName}}', htmlspecialchars($senderLastName), $messageBody);
        $messageBody = str_replace('{{senderEmailAddress}}', htmlspecialchars($senderEmailAddress), $messageBody);

        $deliveryStartDate = $params['deliveryStartDate'];

        $messageBody = str_replace('{{deliveryStartDate}}', htmlspecialchars($deliveryStartDate), $messageBody);

        $senderAddressStreet = $senderAddress['street'];
        $senderAddressZipAndCity = $senderAddress['zip'];
        $senderAddressRegion = $senderAddress['region'];
        $senderAddressCountry = $senderAddress['country'];
        $senderPhone = $sender['phone'];

        $messageBody = str_replace('{{senderAddressStreet}}', htmlspecialchars($senderAddressStreet), $messageBody);
        $messageBody = str_replace('{{senderAddressZipAndCity}}', htmlspecialchars($senderAddressZipAndCity), $messageBody);
        $messageBody = str_replace('{{senderAddressRegion}}', htmlspecialchars($senderAddressRegion), $messageBody);
        $messageBody = str_replace('{{senderAddressCountry}}', htmlspecialchars($senderAddressCountry), $messageBody);
        $messageBody = str_replace('{{senderPhone}}', htmlspecialchars($senderPhone), $messageBody);


        $receiver = $params['receiver'];
        $receiverAddress = $receiver['address'];

        $receiverAddressStreet = $receiverAddress['street'];
        $receiverAddressZipAndCity = $receiverAddress['zip'];
        $receiverAddressRegion = $receiverAddress['region'];
        $receiverAddressCountry = $receiverAddress['country'];
        $receiverPhone = $receiver['phone'];

        $messageBody = str_replace('{{receiverAddressStreet}}', htmlspecialchars($receiverAddressStreet), $messageBody);
        $messageBody = str_replace('{{receiverAddressZipAndCity}}', htmlspecialchars($receiverAddressZipAndCity), $messageBody);
        $messageBody = str_replace('{{receiverAddressRegion}}', htmlspecialchars($receiverAddressRegion), $messageBody);
        $messageBody = str_replace('{{receiverAddressCountry}}', htmlspecialchars($receiverAddressCountry), $messageBody);
        $messageBody = str_replace('{{receiverPhone}}', htmlspecialchars($receiverPhone), $messageBody);

        $cargoDescription = $params['cargoDescription'];
        $message = $params['message'];

        $messageBody = str_replace('{{cargoDescription}}', htmlspecialchars($cargoDescription), $messageBody);
        $messageBody = str_replace('{{message}}', htmlspecialchars($message), $messageBody);

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