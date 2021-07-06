<?php

require 'vendor/autoload.php';

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

$url = 'https://api.emailjs.com/api/v1.0/email/send';

$client = new Client();

try {

    $res = $client->post($url, [
        'headers' => [
            'Content-Type' => 'application/json'
        ],
        'json' => [
            'user_id' => "user_gZhFsukRmrjeOPzBaknuv",
            'service_id' => "Kuikka_gmail",
            'template_id' => "template_0p9bd8m",
            'accessToken' => '6b4e94a17090ed5dc225e554d1605a10',
            'template_params' => [
                'senderFirstName' => '',
                'senderLastName' => '',
                'senderEmailAddress' => '',
                'startDate' => '',
                'pickupAddressStreet' => '',
                'pickupAddressZipAndCity' => '',
                'pickupAddressCountry' => '',
                'pickupPhone' => '',
                'deliveryAddressStreet' => '',
                'deliveryAddressZipAndCity' => '',
                'deliveryAddressCountry' => '',
                'deliveryPhone' => '',
                'cargoDescription' => '',
                'messageContent' => ''
            ]
        ]
    ]);

} catch (GuzzleException $exception) {
    die($exception);
}

echo $res->getStatusCode();
echo $res->getHeader('content-type');
echo $res->getBody();