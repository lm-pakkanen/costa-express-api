<?php

namespace Src\helpers;

use Error;

class Validator {

    public static function isMobilePhone($input, bool $requireInternational = true): bool {

        if (empty($input)) { return false; }

        $input = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        $input = str_replace('-', '', $input);

        if ($requireInternational && strpos($input, '+') !== 0) {
            return false;
        }

        if (strlen($input) < 7 || strlen($input) > 16) {
            return false;
        }

        return true;
    }

    public static function isEmailAddress($input): bool {

        if (empty($input)) { return false; }

        $input = filter_var($input, FILTER_SANITIZE_EMAIL);

        return filter_var($input, FILTER_VALIDATE_EMAIL);

    }

    public static function isDate($input): bool {

        if (empty($input)) { return false; }

        $input = explode('.', $input);

        if (count($input) !== 3) {
            return false;
        }

        // Day.Month.Year -> Month,Day,Year
        return checkdate($input[1], $input[0], $input[2]);

    }

    public static function isPersonName($input): bool {

        if (empty($input) || !is_array($input)) { return false; }

        $firstName = $input['firstName'];
        $lastName = $input['lastName'];

        if (!(strlen($firstName) >= 1 && strlen($firstName) < 150)) {
            return false;
        }

        if (!(strlen($lastName) >= 1 && strlen($lastName) < 150)) {
            return false;
        }

        return true;

    }

    public static function isAddress($input): bool {

        if (empty($input || !is_array($input))) { return false; }

        $street = $input['street'];
        $zip = $input['zip'];
        $region = $input['region'];
        $country = $input['country'];

        if (
            (empty($street) || strlen($street) > 150) ||
            (empty($zip) || strlen($zip) > 150) ||
            (empty($country) || strlen($country) > 150)
        ) {
            return false;
        }

        if (!empty($region) && strlen($region) > 150) {
            return false;
        }

        return true;
    }

    public static function isRequestProposalMessageValid($input): bool {

        if (!empty($input) && strlen($input > 5000)) {
            return false;
        }

        return true;
    }

    public static function isRequestProposalCargoDescriptionValid($input): bool {
        return !(empty($input) || strlen($input > 5000));
    }

    public static function isRequestProposalParamsValid($params): bool {

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