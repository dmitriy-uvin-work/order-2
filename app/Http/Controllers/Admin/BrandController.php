<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\CrudService;
use App\Http\Services\FileService;
use App\Models\Brand;
use App\Models\Country;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    private $model;
    private $fileService;
    private $crudService;

    public function __construct(Brand $model, FileService $fileService, CrudService $crudService)
    {
        $this->model = $model;
        $this->fileService = $fileService;
        $this->crudService = $crudService;
    }

    public function index(Request $request)
    {
        $data =  $this->model::filter($request->input())->orderBy('name')->paginate(15);
        return view('admin.brand.index', compact('data'));
    }

    public function form($id = null)
    {
        if ($id) {
            $data = $this->model::findOrFail($id);
        } else {
            $data = $this->model;
        }
        $countries = Country::all();
        $stocks = Stock::active()->get();

        return view('admin.brand.form', compact('data', 'countries', 'stocks'));
    }

    public function post(Request $request, $id = null)
    {
        try {
            $this->crudService->CREATE_OR_UPDATE($this->model, $request, $id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }

        if ($request->get('_previous')) {
            return redirect($request->get('_previous'))->with('success', 'Успешно сохранено');
        }

        return redirect()->route('admin.brand.index')->with('success', 'Успешно сохранено');
    }
}
