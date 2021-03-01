<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Country;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class BrandController extends Controller
{
    public function list()
    {
        $collection = Brand::all();

        $sorted = $collection->sortBy('name');
        $grouped = $sorted->groupBy(function ($item) {
            return iconv_substr($item->name, 0, 1);
        });

        $countries = Country::whereHas('brand')->orderBy('name')->get();

        return view('frontend.pages.brand-list', compact('grouped', 'countries'));
    }

    public function view(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $perPage = $request->ajax() ? 16 : 15;

        $products = Product::active()->where('brand_id', $brand->id)->filter($request)->paginate($perPage);

        if ($request->ajax()) {
            $view = View::make('frontend.render.catalog', ['products'=>$products])->render();
            return response()->json(['view'=>$view]);
        }

        return view('frontend.pages.brand-view', compact('brand', 'products'));
    }
}
