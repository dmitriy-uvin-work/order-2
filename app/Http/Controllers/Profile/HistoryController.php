<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function list()
    {
        $orders = Order::orderBy('created_at', 'desc')->withCount('products')->get();
        return view('frontend.profile.history', compact('orders'));
    }

    public function view($id)
    {
        $order = Order::with('products')->findOrFail($id);
        return view('frontend.profile.history-view', compact('order'));
    }
}
