// app/Services/TwilioService.php

namespace App\Services;

use Twilio\Rest\Client;

<?php
class TwilioService
{
    protected $client;
    protected $messagingServiceSid;

    public function __construct()
    {
        $this->client = new Client(config('services.twilio.sid'), config('services.twilio.token'));
        $this->messagingServiceSid = config('services.twilio.messaging_service_sid');
    }

    public function sendSms($to, $message)
    {
        return $this->client->messages->create($to, [
            'messagingServiceSid' => $this->messagingServiceSid,
            'body' => $message
        ]);
    }
}
