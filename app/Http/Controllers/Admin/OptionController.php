<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\CrudService;
use App\Http\Services\FileService;
use App\Models\Option;
use App\Models\OptionValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OptionController extends Controller
{
    private $model;
    private $fileService;
    private $crudService;

    public function __construct(Option $model, FileService $fileService, CrudService $crudService)
    {
        $this->model = $model;
        $this->fileService = $fileService;
        $this->crudService = $crudService;
    }

    public function index(Request $request)
    {
        $data =  $this->model::filter($request->input())->withCount('values')->paginate(15);
        $options = OptionValue::whereNull('option_id')->paginate(15);
        return view('admin.option.index', compact('data', 'options'));
    }

    public function form($id = null)
    {
        if ($id) {
            $data = $this->model::findOrFail($id);
        } else {
            $data = $this->model;
        }
        return view('admin.option.form', compact('data'));
    }

    public function post(Request $request, $id = null)
    {
        try {
            $data = $this->crudService->CREATE_OR_UPDATE($this->model, $request, $id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('admin.option.form', ['id' => $data->id])->with('success', 'Успешно сохранено');
    }

    public function destroy($id)
    {
        $this->model::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Успешно удалено');
    }
}
