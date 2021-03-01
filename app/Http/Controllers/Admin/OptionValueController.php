<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\CrudService;
use App\Http\Services\FileService;
use App\Models\Option;
use App\Models\OptionValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OptionValueController extends Controller
{
    private $model;
    private $parentModel;
    private $fileService;
    private $crudService;

    public function __construct(OptionValue $model, Option $parentModel, FileService $fileService, CrudService $crudService)
    {
        $this->model = $model;
        $this->parentModel = $parentModel;
        $this->fileService = $fileService;
        $this->crudService = $crudService;
    }

    public function index(Request $request, $option_id)
    {
        $data = $this->model::filter($request->input())->where('option_id', $option_id)->paginate(15);
        $option = $this->parentModel::findOrFail($option_id);
        return view('admin.option-value.index', compact('data', 'option'));
    }

    public function form($option_id, $id = null)
    {
        if ($id) {
            $data = $this->model::findOrFail($id);
        } else {
            $data = $this->model;
        }

        $option = $this->parentModel::findOrFail($option_id);

        return view('admin.option-value.form', compact('data', 'option'));
    }

    public function formSingle($id)
    {
        $data = $this->model::findOrFail($id);
        return view('admin.option-value.form', compact('data'));
    }

    public function post(Request $request, $option_id, $id = null)
    {
        try {
            $request->request->add(['option_id' => $option_id]);
            $this->crudService->CREATE_OR_UPDATE($this->model, $request, $id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('admin.option-value.index', ['option_id' => $option_id])->with('success', 'Успешно сохранено');
    }

    public function postSingle(Request $request, $id = null)
    {
        try {
            if ($id) {
                $model = OptionValue::findOrFail($id);
                $model->name = $request->get('name');
                $model->save();
            } else {
                $model = new OptionValue();
                $model->name = $request->get('name');
                $model->save();
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
        return redirect()->route('admin.option.index')->with('success', 'Успешно сохранено');
    }

    public function destroy($id)
    {
        $this->model::where(['id' => $id])->delete();
        return redirect()->back()->with('success', 'Успешно удалено');
    }
}
