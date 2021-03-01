<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Services\UDSService;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{

    private $udsService;

    public function __construct(UDSService $udsService)
    {
        $this->udsService = $udsService;
    }

    public function index()
    {
        return view('frontend.profile.index');
    }

    public function getUdsInfo(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $data = (object)[];
            try {
                if (!empty($user->phone)) {
                    $data = $this->udsService->getUserInfo($user->phone);
                    if (isset($data->errorCode)) {
                        if ($data->errorCode == 'notFound') {
                            $data->errorCode = 'notFound';
                        } else {
                            throw new \DomainException($data->message, 500);
                        }
                    }
                }
            } catch (\Exception $exception) {
                return response()->json(['error'=>$exception->getMessage()], 500);
            }

            $view = view('frontend.render.uds-info-profile', ['data' => $data])->render();

            return response()->json(['view' => $view]);
        }

        return abort(404);
    }

    public function update(Request $request)
    {
        $request->validate([
            'surname' => ['required'],
            'name' => ['required'],
        ]);

        $user = Auth::user();
        $user->name = $request->get('name');
        $user->surname = $request->get('surname');

        if ($request->has('password') && !empty($request->get('password'))) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->back()->with(['success'=>'Ваш данные успешно изменён']);
    }

    public function getFavorite()
    {
        $ids = DB::table('favorite')->where('user_id', Auth::user()->getAuthIdentifier())->get()->pluck('product_id')->toArray();
        $products = Product::active()->whereIn('id', $ids)->paginate(8);
        return view('frontend.profile.favorite', compact('products'));
    }
}
