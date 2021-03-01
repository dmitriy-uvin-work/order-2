<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegosSync extends Model
{
    /**
     * @var array [getSessionId, getItem, getColor, getItemPrice, getItemCurrentQuantity, getItemGroup, getCountry, getBrand,
     * getPromoProgram, getPromoProgramSetting]
     */

    protected $fillable = ['id', 'method', 'value', 'sort', 'status', 'last_sync_at', 'created_at', 'updated_at'];

    protected $dates = ['last_sync_at'];

    public function getStatusUIAttribute()
    {
        switch ($this->status) {
            case 1:
                return '<span class="btn-status btn-status-success">успешно</span>';
            case 0:
                return '<span class="btn-status btn-status-waiting">неудача</span>';
            default:
                return '';
        }
    }

    public function getMethodLabelAttribute()
    {
        switch ($this->method) {
            case 'getItem':
                return 'синхронизировать товары';
            case 'getColor':
                return 'синхронизировать цвета';
            case 'getItemPrice':
                return 'синхронизировать цену на товар';
            case 'getItemCurrentQuantity':
                return 'синхронизировать количество товара';
            case 'getItemGroup':
                return 'синхронизировать группы';
            case 'getCountry':
                return 'синхронизировать справочник стран';
            case 'getBrand':
                return 'синхронизировать бренды';
            case 'getSessionId':
                return 'получить идентификатор сеанса';
            case 'getPromoProgram':
                return 'синхронизировать промоакции';
            case 'getPromoProgramSetting':
                return 'синхронизировать настройки промоакции';
            default:
                return '';
        }
    }

}
