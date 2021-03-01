<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function view($slug)
    {
        $product = Product::active()->where('slug', $slug)->with('tags', 'brand')->firstOrFail();
        $tags = $product->tags;
        $similarProducts = Product::active()->whereHas('tags', function ($query) use ($tags) {
            for ($i = 0; $i < count($tags); $i++){
                $query->orwhere('name', 'like',  '%' . $tags[$i]->name .'%');
            }
        })->where('id', '!=', $product->id)->limit(3)->get();

        $colorProducts = [];
        if (!empty($product->producer_id)) {
            $colorProducts = Product::active()
                ->where('producer_id', $product->producer_id)
                ->where('color_id', '!=', null)
                ->where('id', '!=', $product->id)
                ->get();

            $colorProducts->prepend($product);
        }

        return view('frontend.pages.product-view', compact('product', 'similarProducts', 'colorProducts'));
    }
}
