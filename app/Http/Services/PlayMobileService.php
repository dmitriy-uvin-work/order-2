<?php


namespace App\Http\Services;


use GuzzleHttp\Client;

class PlayMobileService
{
    protected $login;
    protected $password;

    public function __construct() {
        $this->login = config('env.PLAY_MOBILE_LOGIN');
        $this->password = config('env.PLAY_MOBILE_PASSWORD');
    }

    public function sendCode($phone, $code)
    {
        $date = new \DateTime();
        $messageId = $this->generateMessageId();

        $url = 'http://91.204.239.44/broker-api/send';
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => $this->basicAuth(),
            'X-Timestamp' => $date->format(\DateTime::ATOM),
        ];
        $json = [
            'messages' => [
                'recipient' => $phone,
                'message-id' => $messageId,
                'sms' => [
                    'originator' => '3700',
                    'content' => [
                        'text' => "Beautyholic \n Confirmation code ".$code
                    ]
                ],
            ]
        ];

        $client = new Client(['http_errors' => false]);

        $response = $client->post($url, [
            'headers' => $headers,
            'json' => $json,
        ]);

        return json_decode($response->getStatusCode());
    }

    protected function basicAuth()
    {
        $login = $this->login;
        $password = $this->password;
        return 'Basic ' . base64_encode($login . ':' . $password);
    }

    public function generateMessageId($length = 9)
    {
        $characters = '123456789';
        $charactersLength = strlen($characters);
        $randomNumber = '';
        for ($i = 0; $i < $length; $i++) {
            $randomNumber .= $characters[rand(0, $charactersLength - 1)];
        }
        return 'bth'.$randomNumber;
    }

    public function generateCode($length = 4)
    {
        $characters = '123456789';
        $charactersLength = strlen($characters);
        $randomNumber = '';
        for ($i = 0; $i < $length; $i++) {
            $randomNumber .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomNumber;
    }
}
