<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Repositories\TransactionsRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\PurchasingRequest;
use App\Http\Services\CartService;
use App\Http\Services\UDSService;
use App\Models\District;
use App\Models\Order;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchasingController extends Controller
{
    const STATUS_PROCESSING = 1;
    const STATUS_WAITING = 0;
    const PAYMENT_CASH = 0;

    const PAYMENT_TYPE_PAYME = 1;
    const PAYMENT_TYPE_CLICK = 2;
    const PAYMENT_TYPE_APELSIN = 3;

    private $cartService;
    private $udsService;

    public function __construct(CartService $cartService, UDSService $udsService)
    {
        $this->cartService = $cartService;
        $this->udsService = $udsService;
    }

    public function index(Request $request)
    {
        $old = json_decode($request->get('old'), true) ?? [];

        if ($request->ajax()) {

            if (count($old) < 1) {
                $array = json_decode($request->get('form'), true) ?? [];
                $old = [];
                foreach ($array as $arr) {
                    if (!empty($arr['value'])) {
                        $old += [$arr['name'] => $arr['value']];
                    }
                }
            }

            $cartValues = $this->cartService->getCartValues($request, true);

            // regions & districts
            $regions = Region::orderBy('name')->get();
            if (isset($old['delivery_region'])) {
                $districts = District::where('region_id', $old['delivery_region'])->orderBy('name')->get();
                $region = $regions->where('id', $old['delivery_region'])->first();

                // update cart values
                $cartValues['delivery_price'] = $region->getPrice($cartValues['total_weight']);
                $cartValues['total_price'] = $cartValues['total_price'] + $cartValues['delivery_price'];
            }

            // uds points

            if (count($cartValues['products']) > 0) {

                if (isset($old['uds_code']) && $old['uds_code'] != '') {
                    $userPoints = $this->udsService->getUserPoints($old['uds_code'], $cartValues['net_price']);
                    if (!isset($userPoints->errorCode)) {
                        $udsInfo = $userPoints;
                    }
                    if (isset($userPoints->errorCode)) {
                        $udsInfoError = $userPoints->message;
                    }
                }

            }

            $content = view('frontend.render.purchase-content', compact('regions', 'districts', 'cartValues', 'udsInfo', 'udsInfoError', 'old'))->render();
            $sidebar = view('frontend.render.purchase-sidebar', ['products'=>$cartValues['products']])->render();

            return response()->json(['content' => $content, 'sidebar' => $sidebar]);

        }

        return view('frontend.profile.purchasing');
    }

    public function post(PurchasingRequest $request)
    {

        $user = Auth::user();

        $data = $request->except(['_token']);

        DB::beginTransaction();
        try {
            $cartValues = $this->cartService->getCartValues($request, true);

            if (isset($data['delivery_region'])) {
                $region = Region::where('id', $data['delivery_region'])->first();
                // update cart values
                $cartValues['delivery_price'] = $region->getPrice($cartValues['total_weight']);
                $cartValues['total_price'] += $cartValues['delivery_price'];
            }

            if ((int)$data['total_price'] === (int)$cartValues['total_price']) {

                $order = new Order;
                $order->user_id = $user->getAuthIdentifier();
                $order->phone = $data['phone'];
                $order->net_price = $cartValues['net_price'];
                $order->price = $cartValues['total_price'];
                $order->delivery_price = $cartValues['delivery_price'];
                $order->status = self::STATUS_WAITING;
                $order->payment_status = self::STATUS_WAITING;
                $order->payment_type = $data['payment_type'];
                $order->delivery_type = $data['delivery_type'];
                $order->delivery_address = $data['delivery_address'];
                $order->delivery_region = $data['delivery_region'];
                $order->delivery_district = $data['delivery_district'];
                $order->save();

                foreach ($cartValues['products'] as $product) {
                    DB::table('order_product')->insert([
                        'order_id' => $order->id,
                        'product_id' => $product->iid,
                        'quantity' => $product->cart_quantity,
                        'price' => $product->price,
                    ]);
                }

            } else {
                throw new \DomainException('Ваш корзина не совпадает с базой. Попробуйте заного!', 500);
            }

            $user->cart()->detach();

            DB::commit();

            switch ($order->payment_type) {
                case self::PAYMENT_TYPE_CLICK:
                    $return_url = route('profile.purchasing.response',['id'=>$order->id]);
                    return redirect("https://my.click.uz/services/pay?service_id=".env('CLICK_SERVICE_ID')."&merchant_id=".env('CLICK_MERCHANT_ID')."&amount={$order->price}&transaction_param={$order->id}&return_url={$return_url}&card_type=uzcard");
                case self::PAYMENT_TYPE_PAYME:
                    $return_url = route('profile.payment.postback');
                    return redirect("https://checkout.paycom.uz/".base64_encode("m=".env('PAYCOM_MERCHANT_ID').";ac.order_id={$order->id};a={$order->price};c={$return_url}"));
                case self::PAYMENT_TYPE_APELSIN:
                    $return_url = route('purchasing.apelsin', ['id' => $order->id]);
//                    $return_url = route('profile.payment.postback.apelsin');
                    return redirect($return_url);
//                    return redirect('https://oplata.kapitalbank.uz?cash='.env('APELSIN_HASH_ID').'&redirectUrl='.$return_url.'&description=Оплата товара&amount='.$order->price.'&order_id='.$order->id);
                default:
                    return redirect()->route('profile.purchasing.response',['id'=>$order->id])->with(['success'=>'Ваш заказ успешно оформлен. Спасибо за покупку']);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with(['error'=>$exception->getMessage()]);
        }
    }

    public function response($id)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->getAuthIdentifier())->where('id', $id)->firstOrFail();

        $userRepository = new UserRepository();
        $balance = $userRepository->getBalanceAttribute($user->id);

        if ($balance < $order->price) {
            return view('frontend.profile.order-response', compact('order'))->withErrors(['error'=>'Недостаточна денег']);
        }

        $transactionRepository = new TransactionsRepository();
        $transactionRepository->createExpense($user->id,$order->id,$order->price);

        return view('frontend.profile.order-response', compact('order'));
    }

    public function apelsin($id)
    {
        $order = Order::findOrFail($id);

        return view('frontend.payment.apelsin', compact('order'));
    }
}
