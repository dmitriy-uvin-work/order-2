<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RegosSync;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function home()
    {
        $sync = RegosSync::orderBy('sort', 'asc')->get(); //dd(Carbon::createFromFormat('d-m-Y', "01-01-2020")->timestamp);
        return view('admin.home', compact('sync'));
    }

    public function destroyImage(Request $request)
    {
        if ($request->ajax()) {
            $path = str_replace('/storage', 'storage', $request->get('path'));
            $thumb_path = str_replace('.', '_thumb.', $path);
            $medium_path = str_replace('.', '_medium.', $path);
            $large_path = str_replace('.', '_large.', $path);
            $original_path = str_replace('.', '_original.', $path);

            @unlink($thumb_path);
            @unlink($medium_path);
            @unlink($large_path);
            @unlink($original_path);
        }
        return response()->json(['success'=>true]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function callRegosMethod(Request $request)
    {
        $request->validate([
            'methodName' => 'required|exists:regos_syncs,method'
        ]);

        try {
            $methodName = $request->get('methodName');
            Artisan::call('sync:'.$methodName);
            $method = RegosSync::where('method', $methodName)->firstOrFail();
            $view = view('admin.render.method-row', compact('method'))->render();
        } catch (\Exception $exception) {
            return response()->json(['error'=>$exception->getMessage()], 500);
        }
        return response()->json(['success'=>true, 'view' => $view]);
    }
}
