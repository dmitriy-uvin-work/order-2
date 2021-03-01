<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function list()
    {
        $stocks = Stock::active()->orderBy('iid', 'desc')->paginate(6);
        return view('frontend.pages.stock-list', compact('stocks'));
    }

    public function view($id)
    {
        $stock = Stock::findOrFail($id);
        return view('frontend.pages.stock-view', compact('stock'));
    }
}
