<?php

namespace App\Libraries;

use Twilio\Rest\Client;

class TwilioService
{
    private $client;
    private $from;

    public function __construct()
    {
        $this->client = new Client(getenv('TWILIO_SID'), getenv('TWILIO_AUTH_TOKEN'));
        $this->from = getenv('TWILIO_PHONE_NUMBER');
    }

    public function makeCall($to, $message)
    {
        return $this->client->calls->create(
            $to,
            $this->from,
            [
                "twiml" => "<Response><Say>{$message}</Say></Response>"
            ]
        );
    }
}