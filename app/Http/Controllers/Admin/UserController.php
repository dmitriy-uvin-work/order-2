<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public $modelClass = User::class;

    public function index(Request $request)
    {
        $data = $this->modelClass::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.user.index', compact('data'));
    }

    public function form(Request $request)
    {
        if ($request->ajax()) {

            $id = $request->get('user_id');

            if ($id) {
                $data = $this->modelClass::findOrFail($id);
            } else {
                $data = new $this->modelClass;
            }

            $view = view('admin.user.form', compact('data'))->render();

            return response()->json(['view'=>$view], 200);
        }

        return abort(404);
    }

    public function post(Request $request, $id = null)
    {
        $data = $request->only(['name', 'email', 'password']);
        $password = $request->get('password');

        DB::beginTransaction();
        try {

            if ($id) {
                $request->validate([
                    'name' => 'required',
                    'email' => 'nullable|email|unique:users,email,'.$id,
                    'role' => 'required', Rule::in(['admin', 'client']),
                ]);
                unset($data['name'], $data['email'], $data['password']);
                if (!empty($password)) {
                    $data['password'] = bcrypt($password);
                }
                $user = $this->modelClass::findOrFail($id);
                $user->update($data);
            } else {
                $request->validate([
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'role' => 'required', Rule::in(['admin', 'client']),
                    'password' => 'required'
                ]);
                if (!empty($password)) {
                    $data['password'] = bcrypt($password);
                }
                $user = $this->modelClass::create($data);
            }

            DB::table('roles')->updateOrInsert([
                'user_id' => $user->id
            ], [
                'role' => $request->get('role')
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \DomainException($exception->getMessage(), $exception->getCode());
        }

        return redirect()->back()->with('success', 'Успешно сохранено');
    }
}
