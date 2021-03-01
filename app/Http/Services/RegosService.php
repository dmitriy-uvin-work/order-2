<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 31.08.2020
 * Time: 15:38
 */

namespace App\Http\Services;


use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Country;
use App\Models\Group;
use App\Models\Product;
use App\Models\PromoProgramSetting;
use App\Models\RegosSync;
use App\Models\Stock;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegosService
{

    protected $token;
    protected $apiLogin;
    protected $login;
    protected $password;
    protected $endpoint;
    protected $model;

    public function __construct(RegosSync $model)
    {
        $this->token = config('env.REGOS_TOKEN');
        $this->apiLogin = config('env.REGOS_API_LOGIN');
        $this->login = config('env.REGOS_LOGIN');
        $this->password = config('env.REGOS_PASSWORD');
        $this->endpoint = 'https://uzb.api.regos.uz/v1';
        $this->model = $model;
    }

    public function login()
    {
        try {
            $url = $this->endpoint . '/auth/login';

            $headers = [
                'Content-type' => 'application/json',
                'Token' => $this->token,
                'ApiLogin' => $this->apiLogin,
                'Authorization' => $this->basicAuth(),
            ];

            $json = [
                "device_name" => "Beautyholic-Site"
            ];

            $client = new Client();

            $res = $client->request('POST', $url, [
                'headers' => $headers,
                'json' => $json
            ]);

            $data = json_decode($res->getBody()->getContents());

            $this->model::updateOrCreate([
                'method' => 'getSessionId',
            ], [
                'value' => $data->ok == true ? $data->result->session_id : $data->result->description,
                'status' => $data->ok == true ? 1 : 0,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now()
            ]);

        } catch (GuzzleException $exception) {

            $this->handleException("getSessionId", $exception->getCode(), $exception->getMessage());

        }
    }

    public function prolongSession()
    {
        $session_id = $this->model::whereMethod('getSessionId')->first();

        try {
            if ($session_id && $session_id->status == 1) {
                $url = $this->endpoint . '/auth/prolongSession';

                $headers = [
                    'Content-type' => 'application/json',
                    'ApiLogin' => $this->apiLogin,
                    'Token' => $this->token,
                    'SessionID' => $session_id->value,
                ];

                $client = new Client();

                $res = $client->request('POST', $url, [
                    'headers' => $headers,
                ]);

                $data = json_decode($res->getBody()->getContents());

                $session_id->status = $data->ok == true ? 1 : 0;
                $session_id->updated_at = Carbon::now();
                $session_id->save();
            } else {
                throw new \DomainException();
            }
        } catch (\Exception $exception) {

            Artisan::call('regos:login');

            throw new \DomainException();
        }

        return true;
    }

    public function getExt($ids)
    {
        $session_id = $this->model::whereMethod('getSessionId')->orderBy('created_at', 'desc')->first();

        if ($session_id && $session_id->status == 1) {

            DB::beginTransaction();

            try {
                $url = $this->endpoint . '/Item/getExt';

                $headers = [
                    'Content-type' => 'application/json',
                    'ApiLogin' => $this->apiLogin,
                    'Token' => $this->token,
                    'SessionID' => $session_id->value,
                ];

                $json = json_encode([
                    "ids" => $ids,
                    "price_type_id" => 1
                ]);

                $client = new Client();

                $res = $client->request('POST', $url, [
                    'headers' => $headers,
                    'body' => $json
                ]);
                $data = json_decode($res->getBody()->getContents());

                if ($data->ok == true) {
                    $this->updateModel($data->result, 'getExt');
                } else {
                    throw new \DomainException($data->result->description, 500);
                }

                DB::commit();

            } catch (\Exception $exception) {
                DB::rollBack();
                throw new \DomainException($exception->getMessage(), $exception->getCode());
            }
        }

        return true;
    }

    public function sync($method)
    {
        $session_id = $this->model::whereMethod('getSessionId')->orderBy('created_at', 'desc')->first();

        if ($session_id && $session_id->status == 1) {

            DB::beginTransaction();

            try {
                $url = $this->endpoint . '/Sync/' . $method;

                $model = $this->model::whereMethod($method)->first();

                $last_update = Carbon::createFromFormat('d-m-Y', "01-01-2020")->timestamp;

                if ($model && !empty($model->last_sync_at)) {
                    $last_update = $model->last_sync_at->timestamp;
                }

                $headers = [
                    'Content-type' => 'application/json',
                    'ApiLogin' => $this->apiLogin,
                    'Token' => $this->token,
                    'SessionID' => $session_id->value,
                ];

                $params = [
                    'last_update' => $last_update
                ];

//                if ($method == 'getPromoProgram') {
//                    $params['type_ids'] = [2];
//                }

                $json = json_encode($params);

                $client = new Client();

                $res = $client->request('POST', $url, [
                    'headers' => $headers,
                    'body' => $json
                ]);
                $data = json_decode($res->getBody()->getContents());

                $m = $this->model::updateOrCreate([
                    'method' => $method,
                ], [
                    'value' => $data->ok == true ? count($data->result) . ' обработанных данных' : $data->result->description,
                    'status' => $data->ok == true ? 1 : 0,
                    'updated_at' => Carbon::now()
                ]);

                if ($data->ok == true) {
                    $m->update(['last_sync_at' => Carbon::now()]);
                    $this->updateModel($data->result, $method);
                }

                DB::commit();

            } catch (\Exception $exception) {
                DB::rollBack();
                $this->handleException($method, $exception->getCode(), $exception->getMessage());
            }
        }

        return true;
    }

    private function updateModel($data, $method)
    {
        try {
            switch ($method) {
                case "getExt":
                    foreach ($data as $item) {
                        Product::updateOrCreate([
                            'iid' => $item->item->id
                        ], [
                            'name' => $item->item->name,
                            'slug' => Str::slug($item->item->name),
                            'quantity' => $item->quantity->allowed,
                            'price' => $item->price,
                            'deleted' => $item->item->deleted_mark
                        ]);
                    }
                    break;
                case "getItem":
                    foreach ($data as $item) {
                        if (!empty($item->name)) {
                            Product::updateOrCreate([
                                'iid' => $item->id
                            ], [
                                'name' => $item->name,
                                'slug' => Str::slug($item->name),
                                'code' => $item->code,
                                'group_id' => $item->group_id,
                                'brand_id' => $item->brand_id,
                                'producer_id' => $item->producer_id,
                                'color_id' => $item->color_id,
                                'deleted' => $item->deleted,
                                'last_update' => $item->last_update,
                            ]);
                        }
                    }
                    break;
                case "getColor":
                    foreach ($data as $item) {
                        if (!empty($item->name)) {
                            Color::updateOrCreate([
                                'iid' => $item->id
                            ], [
                                'name' => $item->name,
                                'deleted' => $item->deleted,
                                'last_update' => $item->last_update,
                            ]);
                        }
                    }
                    break;
                case "getItemPrice":
                    foreach ($data as $item) {
                        Product::where('iid', $item->item_id)->update([
                            'price' => $item->value,
                        ]);
                    }
                    break;
                case "getItemCurrentQuantity":
                    foreach ($data as $item) {
                        Product::where('iid', $item->item_id)->update([
                            'quantity' => $item->quantity,
                        ]);
                    }
                    break;
                case "getItemGroup":
                    foreach ($data as $item) {
                        Group::updateOrCreate([
                            'iid' => $item->id
                        ], [
                            'name' => $item->name,
                            'parent_id' => $item->parent_id,
                            'deleted' => $item->deleted,
                            'last_update' => $item->last_update,
                        ]);
                    }
                    break;
                case "getCountry":
                    foreach ($data as $item) {
                        Country::updateOrCreate([
                            'iid' => $item->id
                        ], [
                            'name' => $item->name,
                            'fullname' => $item->fullname,
                            'code' => $item->code,
                            'alfa2' => $item->alfa2,
                            'alfa3' => $item->alfa3,
                            'deleted' => $item->deleted,
                            'last_update' => $item->last_update,
                        ]);
                    }
                    break;
                case "getBrand":
                    foreach ($data as $item) {
                        Brand::updateOrCreate([
                            'iid' => $item->id
                        ], [
                            'name' => $item->name,
                            'slug' => Str::slug($item->name),
                            'deleted' => $item->deleted,
                            'last_update' => $item->last_update,
                        ]);
                    }
                    break;
                case "getPromoProgram":
                    foreach ($data as $item) {
                        Stock::updateOrCreate([
                            'iid' => $item->id
                        ], [
                            'name' => $item->name,
                            'active' => $item->active,
                            'started_at' => Carbon::createFromFormat('Y-m-d H:i:s', $item->start_date . ' ' . $item->start_time),
                            'ended_at' => Carbon::createFromFormat('Y-m-d H:i:s', $item->end_date . ' ' . $item->end_time),
                            'priority' => $item->priority,
                            'deleted' => $item->deleted,
                        ]);
                    }
                    break;
                case "getPromoProgramSetting":
                    $programIds = Stock::all()->pluck('iid')->toArray();
                    foreach ($data as $item) {
                        $value = json_decode($item->value, true);
                        if (in_array($item->program_id, $programIds)) {
                            switch ($item->key) {
                                case 'general_settings':
                                    PromoProgramSetting::updateOrCreate([
                                        'iid' => $item->id
                                    ], [
                                        'program_id' => $item->program_id,
                                        'key' => $item->key,
                                        'value' => $item->value,
                                        'discount' => isset($value['discount']) ? $value['discount'] : null,
                                        'interaction_method' => isset($value['interaction_method']) ? $value['interaction_method'] : null,
                                        'deleted' => $item->deleted,
                                    ]);

                                    $stock = Stock::where('iid', $item->program_id)->first();
                                    $stock->discount = isset($value['discount']) ? $value['discount'] : null;
                                    $stock->save();
                                    break;
                                case 'item_group':
                                    if ($item->deleted) {
                                        DB::table('promo_program_group')->where([
                                            'group_id' => $value['group_id'],
                                            'program_id' => $item->program_id
                                        ])->delete();
                                    } else {
                                        DB::table('promo_program_group')->updateOrInsert([
                                            'group_id' => $value['group_id'],
                                            'program_id' => $item->program_id,
                                            'include_children' => $value['include_children']
                                        ]);
                                    }
                                    break;
                                case 'item':
                                    if ($item->deleted) {
                                        DB::table('promo_program_item')->where([
                                            'item_id' => $value['item_id'],
                                            'program_id' => $item->program_id
                                        ])->delete();
                                    } else {
                                        DB::table('promo_program_item')->updateOrInsert([
                                            'item_id' => $value['item_id'],
                                            'program_id' => $item->program_id
                                        ]);
                                    }
                                    break;
                                case 'setting':
                                    $stock = Stock::where('iid', $item->program_id)->first();
                                    $stock->discount = isset($value['discount_percent']) ? $value['discount_percent'] : null;
                                    $stock->save();
                                    break;

                            }
                        }
                    }
                    break;
            }
        } catch (\Exception $e) {
            throw new \DomainException($e->getMessage(), $e->getCode());
        }
    }

    private function handleException($method, $code, $message)
    {
        $this->model::updateOrCreate([
            'method' => $method,
        ], [
            'value' => $this->exceptionMessages($message, $code),
            'status' => 0,
            'updated_at' => Carbon::now()
        ]);

        throw new \DomainException($message, $code);
    }

    private function basicAuth()
    {
        $login = $this->login;
        $password = $this->password;
        return 'Basic ' . base64_encode($login . ':' . $password);
    }

    private function exceptionMessages($message, $code = null)
    {
        switch ($code) {
            case 401:
                return 'Не авторизован. Неправильно указаны данные для авторизации';
            case 404:
                return 'Метод не найден. Указан несуществующий метод API';
            case 500:
                return 'Внутренняя ошибка сервера. Требуется обратиться к поддержку REGOS Software';
            case 503:
                return 'Сервер не принимает запросы по техническим причинам. Сервер находится на обслуживании';
            case 1000:
                return 'Ошибка подключения к базе данных';
            case 1001:
                return 'SQL запрос к базе данных вернул null';
            case 1002:
                return 'Отсутствует обязательное поле';
            case 1008:
                return 'Некоректные входные данные';
            case 1012:
                return 'Неверный входной параметр';
            default:
                return $message;
        }
    }
}
