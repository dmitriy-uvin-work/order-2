<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\CrudService;
use App\Http\Services\FileService;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    private $model;
    private $fileService;
    private $crudService;

    public function __construct(Post $model, FileService $fileService, CrudService $crudService)
    {
        $this->model = $model;
        $this->fileService = $fileService;
        $this->crudService = $crudService;
    }

    public function index(Request $request)
    {
        $data =  $this->model::filter($request->input())->paginate(15);
        return view('admin.post.index', compact('data'));
    }

    public function form($id = null)
    {
        if ($id) {
            $data = $this->model::findOrFail($id);
        } else {
            $data = $this->model;
        }

        $tags = Tag::orderBy('name')->get();

        return view('admin.post.form', compact('data', 'tags'));
    }

    public function post(Request $request, $id = null)
    {
        try {
            $data = $this->crudService->CREATE_OR_UPDATE($this->model, $request, $id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('admin.post.form', ['id' => $data->id])->with('success', 'Успешно сохранено');
    }

    public function destroy($id)
    {
        $this->model::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Успешно удалено');
    }
}
