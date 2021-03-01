<?php


namespace App\Http\Services;


use GuzzleHttp\Client;
use Ramsey\Uuid\Nonstandard\Uuid;

class UDSService
{

    protected $apiKey;
    protected $companyId;
    protected $endpoint;

    public function __construct()
    {
        $this->apiKey = config('env.UDS_API_KEY');
        $this->companyId = config('env.UDS_COMPANY_ID');
        $this->endpoint = 'https://api.uds.app/partner/v2';
    }

    public function getUserInfo($phone)
    {
        $date = new \DateTime();
        $uuid_v4 = Uuid::uuid4(); //generate universally unique identifier version 4 (RFC 4122)

        $url = $this->endpoint.'/customers/find?phone=%2B998'.$phone;
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => $this->basicAuth(),
            'X-Origin-Request-Id' => $uuid_v4->toString(),
            'X-Timestamp' => $date->format(\DateTime::ATOM),
        ];

        $client = new Client(['http_errors' => false]);
        $response  = $client->request('GET', ''.$url.'', ['headers' => $headers]);

        return json_decode($response->getBody());
    }

    public function getUserPoints($code,$price)
    {
        $date = new \DateTime();
        $uuid_v4 = Uuid::uuid4(); //generate universally unique identifier version 4 (RFC 4122)

        $url = 'https://api.uds.app/partner/v2/customers/find?code='. $code .'&total='. $price;
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => $this->basicAuth(),
            'X-Origin-Request-Id' => $uuid_v4->toString(),
            'X-Timestamp' => $date->format(\DateTime::ATOM),
        ];

        $client = new Client(['http_errors' => false]);
        $response  = $client->request('GET', ''.$url.'', ['headers' => $headers]);

        return json_decode($response->getBody());
    }

    private function basicAuth()
    {
        $companyId = $this->companyId;
        $apiKey = $this->apiKey;
        return 'Basic ' . base64_encode($companyId . ':' . $apiKey);
    }
}
