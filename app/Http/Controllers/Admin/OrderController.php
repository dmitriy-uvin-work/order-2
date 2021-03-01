<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\CrudService;
use App\Http\Services\FileService;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $model;
    private $fileService;
    private $crudService;

    public function __construct(Order $model, FileService $fileService, CrudService $crudService)
    {
        $this->model = $model;
        $this->fileService = $fileService;
        $this->crudService = $crudService;
    }

    public function index(Request $request)
    {
        $data = $this->model::filter($request->input())->withCount('products')->orderByDate()->paginate(15);
        return view('admin.order.index', compact('data'));
    }

    public function form($id)
    {
        $order = $this->model::with('products')->findOrFail($id);
        return view('admin.order.form', compact('order'));
    }

    public function post(Request $request, $id)
    {
        try {
            $data = $request->except(['_token', 'q']);
            $order = Order::findOrFail($id);

            $order->status = (int)$data['status'];
            $order->save();

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('admin.order.index')->with('success', 'Успешно сохранено');
    }
}
