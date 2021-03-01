<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Repositories\TransactionsRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\PurchasingRequest;
use App\Http\Services\CartService;
use App\Http\Services\Paycom\Application;
use App\Http\Services\Paycom\Constants;
use App\Http\Services\UDSService;
use App\Models\District;
use App\Models\Order;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    private $cartService;
    private $udsService;

    public function __construct(CartService $cartService, UDSService $udsService)
    {
        $this->cartService = $cartService;
        $this->udsService = $udsService;
    }

    public function postback()
    {
//        dd(base64_encode('Paycom:fkWW6UNrzvzyV6DhrdHJ6aEhr3dRcvJYkaGx'));
        // load configuration
        $paycomConfig = require_once Constants::CONFIG_FILE;

        $application = new Application($paycomConfig);
        $application->run();
    }

    public function apelsin()
    {
        $body = @file_get_contents("php://input");

        $curlOptions = [
//            CURLOPT_URL => \Yii::app()->params->SalesDoubler['postbackUrl'] . "/{$postbackId}/{$this->clickId}?{$queryParams}",
            CURLOPT_URL => 'https://tovarka.topbook.com.ua/test',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT_MS => 500,
            CURLOPT_CONNECTTIMEOUT_MS => 500,
            CURLOPT_POSTFIELDS => $body,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);
        $response = curl_exec($curl);
        $error = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        echo($response);
    }
}
