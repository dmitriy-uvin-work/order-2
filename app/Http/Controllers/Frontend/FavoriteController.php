<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function attach(Request $request)
    {
        if ($request->ajax()) {
            try {
                $user = Auth::user();
                $product_id = $request->get('product_id');

                $product = Product::where('iid', $product_id)->first();

                if ($product) {
                    if ($user) {
                        $favorites = DB::table('favorite')->where('user_id', Auth::user()->getAuthIdentifier())->get()->pluck('product_id')->toArray();

                        if (!in_array($product_id, $favorites)) {
                            Auth::user()->favorites()->attach($product_id);
                        }
                    }
                } else {
                    throw new \DomainException("", 404);
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
                $product_id = $request->get('product_id');

                Auth::user()->favorites()->detach($product_id);

            } catch (\Exception $exception) {
                throw new \DomainException($exception->getMessage(), $exception->getCode());
            }
        }

        return true;
    }

    public function list(Request $request)
    {
        $user = Auth::user();
        $cart = $request->get('wishlist') ?? [];
        $products = [];

        DB::beginTransaction();
        try {
            $wish_collect = collect($this->getFavoriteList($cart,$user));

            if (!empty($wish_collect)) {

                $ids = $wish_collect->pluck('product_id');

                $products = Product::whereIn('iid', $ids)->active()->get();
            }
            if ($user && count($products) > 0) {
                DB::table('favorite')->where('user_id', $user->getAuthIdentifier())->delete();
                foreach ($products as $product) {
                    DB::table('favorite')->insert(['user_id'=>$user->getAuthIdentifier(), 'product_id' => $product->id]);
                }
            }

            $view = view('frontend.render.wish-modal', compact('products'))->render();

            DB::commit();

            return response()->json(['view' => $view, 'products' => $products]);

        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \DomainException($exception->getMessage(), $exception->getCode());
        }
    }

    private function getFavoriteList($wish, $user)
    {
        if ($user) {
            $ids = [];

            foreach ($wish as $item) {
                $ids[] = $item['product_id'];
            }

            $user_wish = $user->favorites ?? [];

            if (count($user_wish) > 0) {
                foreach ($user_wish as $arr) {
                    if (!in_array($arr->iid, $ids)) {
                        $wish[] = [
                            'product_id' => $arr->id,
                        ];
                    }
                }
            }
        }

        return $wish;
    }
}
