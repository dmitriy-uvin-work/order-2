<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\CrudService;
use App\Http\Services\FileService;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    private $model;
    private $fileService;
    private $crudService;

    public function __construct(Menu $model, FileService $fileService, CrudService $crudService)
    {
        $this->model = $model;
        $this->fileService = $fileService;
        $this->crudService = $crudService;
    }

    public function index(Request $request)
    {
        $data =  $this->model::filter($request->input())->orderBy('sort')->paginate(15);
        return view('admin.menu.index', compact('data'));
    }

    public function form($id = null)
    {
        if ($id) {
            $data = $this->model::findOrFail($id);
        } else {
            $data = $this->model;
        }

        return view('admin.menu.form', compact('data'));
    }

    public function post(Request $request, $id = null)
    {
        try {
            $this->crudService->CREATE_OR_UPDATE($this->model, $request, $id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('admin.menu.index')->with('success', 'Успешно сохранено');
    }

    public function destroy($id)
    {
        $this->model::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Успешно удалено');
    }
}
