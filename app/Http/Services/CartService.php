<?php


namespace App\Http\Services;


use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartService
{
    private $rService;

    public function __construct(RegosService $rService)
    {
        $this->rService = $rService;
    }

    private function getStartCart($cart, $user, $sync)
    {
        if ($user) {
            $ids = [];

            foreach ($cart as $item) {
                $ids[] = $item['product_id'];
            }

            $user_cart = $user->cart ?? [];

            if (count($user_cart) > 0) {
                foreach ($user_cart as $arr) {
                    if (!in_array($arr->iid, $ids)) {
                        $cart[] = [
                            'product_id' => $arr->iid,
                            'quantity' => $arr->pivot->quantity,
                        ];
                    }
                }
            }
        }

        // будет проверка через регос ...
        if ($sync == true && count($cart) > 0) {
            $this->rService->getExt(collect($cart)->pluck('product_id'));
        }

        return $cart;
    }

    private function getActiveCart($startCart, $user)
    {
        $cart_collect = collect($startCart);
        $products = [];

        if (!empty($cart_collect)) {

            $ids = $cart_collect->pluck('product_id');

            $products = Product::whereIn('iid', $ids)->active()->where('quantity', '>', 0)->get();

            if (count($products) > 0) {
                foreach ($products as $product) {
                    $cart_q = $cart_collect->where('product_id', $product->iid)->first()['quantity'];
                    $product->setAttribute('cart_quantity', $cart_q);
                }
            }
        }
        if ($user && count($products) > 0) {
            DB::table('cart')->where('user_id', $user->getAuthIdentifier())->delete();
            foreach ($products as $product) {
                DB::table('cart')->insert(['user_id'=>$user->getAuthIdentifier(), 'product_id' => $product->iid, 'quantity' => $product->cart_quantity]);
            }
        }

        return $products;
    }

    public function getCartItems($request, $sync = false)
    {
        $cart = $request->get('cart') ?? [];
        $user = Auth::user();

        DB::beginTransaction();
        try {
            $startCart = $this->getStartCart($cart, $user, $sync);
            $activeCart = $this->getActiveCart($startCart, $user);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \DomainException($exception->getMessage(), $exception->getCode());
        }
        return $activeCart;
    }

    public function getCartValues($request, $sync = false) : array
    {
        $products = $this->getCartItems($request, $sync);

        $array = [
            'net_price' => 0,
            'total_price' => 0,
            'total_weight' => 0,
            'total_quantity' => 0,
            'ids' => [],
            'products' => [],
            'delivery_price' => 0
        ];

        foreach ($products as $product) {
            $array['net_price'] += ((int)$product->cart_quantity * (int)$product->getPrice());
            $array['total_price'] += ((int)$product->cart_quantity * (int)$product->getPrice());
            $array['total_weight'] += ((int)$product->cart_quantity * ((int)$product->weight) ?? 0);
            $array['total_quantity'] += ((int)$product->cart_quantity);
            $array['ids'][] = $product->iid;
            $array['products'] = $products;
        }

        return $array;
    }
}
