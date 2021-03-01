<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\CrudService;
use App\Http\Services\FileService;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $model;
    private $fileService;
    private $crudService;

    public function __construct(Product $model, FileService $fileService, CrudService $crudService)
    {
        $this->model = $model;
        $this->fileService = $fileService;
        $this->crudService = $crudService;
    }

    public function index(Request $request)
    {
        $data =  $this->model::filter($request->input())->with('brand', 'categories')->orderBy('iid', 'desc')->paginate(15);
        return view('admin.product.index', compact('data'));
    }

    public function form($id = null)
    {
        if ($id) {
            $data = $this->model::findOrFail($id);
        } else {
            $data = $this->model;
        }

        $brands = Brand::orderBy('name')->get();
        $options = Option::with('values')->get();
        $singleOptions = OptionValue::whereNull('option_id')->get();
        $tags = Tag::orderBy('name')->get();
        $categories = Category::getCategoryTree();
        $stocks = Stock::active()->get();

        return view('admin.product.form', compact('data', 'brands', 'options', 'singleOptions', 'tags', 'categories', 'stocks'));
    }

    public function post(Request $request, $id = null)
    {
        $this->validate($request, [
            'description' => 'max:1400',
            'information' => 'max:1400',
            'image' => 'max:1000000'
        ]);

        try {
            $data = $this->crudService->CREATE_OR_UPDATE($this->model, $request, $id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('admin.product.form', ['id' => $data->id])->with('success', 'Успешно сохранено');
    }
}

// SQLSTATE[23503]: Foreign key violation: 7 ERROR: insert or update on table "category_option" violates foreign key constraint "category_option_category_id_foreign" DETAIL: Key (category_id)=(5934) is not present in table "categories". (SQL: insert into "category_option" ("category_id", "option_id") values (5934, 2))
