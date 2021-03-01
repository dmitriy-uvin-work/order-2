<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Services\CartService;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function attach(Request $request)
    {
        if ($request->ajax()) {

            try {
                $product_id = (int)$request->get('product_id');
                $quantity = $request->get('quantity');

                $product = Product::active()->where('iid', $product_id)->where('quantity', '>=', $quantity)->first();

                if ($product) {

                    $user = Auth::user();

                    if ($user) {
                        DB::table('cart')->updateOrInsert([
                            'user_id' => $user->getAuthIdentifier(),
                            'product_id' => $product_id
                        ], [
                            'quantity' => $quantity
                        ]);
                    }

                } else {
                    throw new \DomainException("Нет в наличии", 404);
                }

            } catch (\Exception $exception) {
                throw new \DomainException($exception->getMessage(), $exception->getCode());
            }
        }

        return true;
    }

    public function detach(Request $request)
    {
        if ($request->ajax()) {
            try {
                $user = Auth::user();
                if ($user) {
                    $product_id = $request->get('product_id');

                    Auth::user()->cart()->detach($product_id);
                }
            } catch (\Exception $exception) {
                throw new \DomainException($exception->getMessage(), $exception->getCode());
            }
        }

        return true;
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {

            $cart = $this->cartService->getCartValues($request, false);

            $view = view('frontend.render.cart-modal', compact('cart'))->render();

            return response()->json(['view' => $view, 'products' => $cart['products']]);
        }

        return true;
    }
}
