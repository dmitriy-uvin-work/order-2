<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class CatalogController extends Controller
{
    public function index(Request $request, $slug = null)
    {

        $perPage = $request->ajax() ? 16 : 15;
        $currentCategory = null;

        if ($slug == 'new') {
            $products = Product::active()->onlyNew()->with('categories','brand')->filter($request);
            $currentCategory = (object)[
                'name' => 'Новинки'
            ];
        } else {
            if (!empty($slug)) {
                $currentCategory = Category::where('slug', $slug)->firstOrFail();
                $request->request->add(['category'=>$currentCategory->id]);
            }

            $products = Product::active()->quantity()->with('categories','brand')->groupBy('products.id', 'producer_id')->filter($request);
        }

        $products = $products->paginate($perPage);

        $maxPrice = DB::table('products')->max('price');

        //filters
        if ($currentCategory instanceof Category) {
            $brands = $currentCategory->brands;
            $options = $currentCategory->options;
            $singleOptions = $currentCategory->singleOptions;
        } else {
            $brands = Brand::orderBy('name')->get();
            $options = [];
            $singleOptions = [];
        }

        if ($request->ajax()) {
            $view = View::make('frontend.render.catalog', ['products'=>$products])->render();
            return response()->json(['view'=>$view]);
        }

        return view('frontend.pages.catalog', compact('products', 'brands', 'options', 'singleOptions', 'currentCategory', 'maxPrice'));
    }
}
