<?php

namespace Src\controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as phpMailerException;

use Error;
use Exception;

use Src\helpers\Validator;

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

        $messageSubject = 'CostaExpress | Yhteydenotto sivustolta';

        $messageReceiverEmail = 'info@costaexpress.fi';
        $messageReceiverName = 'CostaExpress info';

        $messageSenderEmail = 'yhteydenotto@costaexpress.fi';
        $messageSenderName = 'CostaExpress';

        //$messageReceiverEmail = 'kuikka87@gmail.com';
        //$messageReceiverName = 'CostaExpress info';

        try {

            $mailer = new PHPMailer(getenv('ENVIRONMENT') === 'dev');

            $mailer->CharSet = 'UTF-8';
            $mailer->Encoding = 'base64';

            $mailer->isSMTP();
            $mailer->Host = 'smtp.qnet.fi';
            $mailer->Port = 25;

            $mailer->setFrom($messageSenderEmail, $messageSenderName);

            $customerEmailAddress = $params['senderEmailAddress'];
            $customerName = $params['senderFirstName'] . ' ' . $params['senderLastName'];

            $mailer->addReplyTo(htmlspecialchars($customerEmailAddress), htmlspecialchars($customerName));

            $mailer->addAddress($messageReceiverEmail, $messageReceiverName);

            $mailer->Subject = htmlspecialchars($messageSubject);

            $messageBody = file_get_contents(__DIR__ . '/../emailTemplates/requestProposal.html');

            $messageBody = str_replace('{{messageSubject}}', htmlspecialchars($messageSubject), $messageBody);

            foreach ($params as $key => $value) {
                $messageBody = str_replace('{{' . htmlspecialchars($key) . '}}', htmlspecialchars($value), $messageBody);
            }

            $mailer->msgHTML($messageBody);

            if (!$mailer->send()) {
                throw new Error('Could not send email: ' . $mailer->ErrorInfo);
            }

        } catch (phpMailerException $exception) {
            throw new Error($exception->errorMessage(), 500);
        } catch (Exception $exception) {
            throw new Error($exception->getMessage(), 500);
        }

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