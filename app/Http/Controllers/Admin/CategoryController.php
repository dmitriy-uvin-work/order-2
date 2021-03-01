<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\CrudService;
use App\Http\Services\FileService;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    private $model;
    private $fileService;
    private $crudService;

    public function __construct(Category $model, FileService $fileService, CrudService $crudService)
    {
        $this->model = $model;
        $this->fileService = $fileService;
        $this->crudService = $crudService;
    }

    public function index()
    {
        $data =  $this->model::getCategoryTree();
        return view('admin.category.index', compact('data'));
    }

    public function form($id = null)
    {
        if ($id) {
            $data = $this->model::with('options','singleOptions','brands')->findOrFail($id);
        } else {
            $data = $this->model;
        }

        $options = Option::all();
        $brands = Brand::orderBy('created_at', 'desc')->get();
        $singleOptions = OptionValue::whereNull('option_id')->get();
        $categories = Category::getCategoryTree();
        $stocks = Stock::active()->get();

        return view('admin.category.form', compact('data', 'categories', 'options', 'singleOptions', 'brands', 'stocks'));
    }

    public function post(Request $request, $id = null)
    {
        try {
            $model = $this->crudService->CREATE_OR_UPDATE($this->model, $request, $id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('admin.category.form', ['id'=>$model->id])->with('success', 'Успешно сохранено');
    }

    public function update(Request $request)
    {
        $data = $request->input('nestable');
        if ($data) {
            $array = json_decode($data);
            $this->recursion($array);
        }

        return redirect()->back()->with('success', 'Успешно обновлено');
    }

    private function recursion($array, $parent_id = null)
    {
        if (count($array)) {
            $i = 1;
            foreach ($array as $arr) {
                $this->model->where('id', $arr->id)->update(['sort' => $i, 'parent_id' => $parent_id]);
                if (isset($arr->children)) {
                    $this->recursion($arr->children, $arr->id);
                }
                $i++;
            }
        }
    }

    public function destroy($id)
    {
        $this->model::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Успешно удалено');
    }
}
