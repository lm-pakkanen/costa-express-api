<?php

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

}