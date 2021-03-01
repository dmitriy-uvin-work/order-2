<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Services\Paycom\Order;
use App\Models\Banner;
use App\Models\Country;
use App\Models\Menu;
use App\Models\Page;
use App\Models\PaycomOrder;
use App\Models\PaycomTransaction;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
//        $order = PaycomOrder::find(9);
//        $order->state = 1;
//        $order->save();
//        dd(PaycomOrder::find(1));
//        dd(PaycomTransaction::all());
//        PaycomTransaction::truncate();
        $banners = Banner::orderBySort()->get();
        $submenu = Menu::orderBySort()->get();

        $newProducts = Product::active()->quantity()->orderByDate()->with('categories')->limit(20)->get();
        $hitProducts = Product::active()->quantity()->inRandomOrder()->with('categories')->limit(20)->get();

        $stocks = Stock::active()->filled()->orderBy('created_at', 'desc')->get();

        return view('frontend.pages.index', compact('banners', 'submenu', 'newProducts', 'hitProducts', 'stocks'));
    }

    public function getPage($slug)
    {
        $data = Page::where('slug', $slug)->firstOrFail();
        return view('frontend.pages.page', compact('data'));
    }

    public function getHelp()
    {
        $pages = Page::active()->where('on_top', 1)->orderBySort()->get();
        return view('frontend.pages.help', compact('pages'));
    }

    public function getSearchResult(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('query');
            $products = [];

            if (!empty($query)) {
                $products = Product::active()->with('categories')->where('name', 'like', '%'.$query.'%')->orWhere('description', 'like', '%'.$query.'%')->paginate(80);
            }

            $view = view('frontend.render.search-modal', compact('products', 'query'))->render();

            return response()->json(['html' => $view]);
        }

        return abort(404);
    }
}
