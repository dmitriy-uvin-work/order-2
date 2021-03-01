<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'user_id', 'price', 'delivery_price', 'status', 'phone', 'payment_status',
        'payment_type', 'delivery_type', 'delivery_region', 'delivery_district', 'delivery_address', 'comment',
        'created_at', 'updated_at'];

    public static $STATUS_ARRAY = [
        0 => ['Ожидание оплаты', 'waiting'],
        1 => ['Обработка заказа', 'waiting'],
        2 => ['Завершен', 'success'],
        3 => ['Ошибка при оплате', 'danger'],
        5 => ['Отменен', 'danger'],
    ];

    public static $PAYMENT_STATUS_ARRAY = [
        0 => ['Ожидание оплаты', 'waiting'],
        2 => ['Оплачено', 'success'],
        3 => ['Ошибка при оплате', 'danger'],
        5 => ['Отменен', 'danger'],
    ];

    public static $PAYMENT_TYPE = [
        0 => 'Наличными',
        1 => 'Payme',
        2 => 'Click',
        3 => 'Apelsin',
    ];

    public static $DELIVERY_TYPE = [
        1 => 'Доставка',
        2 => 'Самовывоз',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product', 'order_id', 'product_id')->withPivot(['price', 'quantity']);
    }

    public function isFirst() {
        $f_order = $this->user->orders->first();
        if ($f_order->id == $this->id) {
            return true;
        } else {
            return false;
        }
    }

    // setters
    public function setPaymentTypeAttribute($value)
    {
        $this->attributes['payment_type'] = (int)$value;
    }

    public function setDeliveryTypeAttribute($value)
    {
        $this->attributes['delivery_type'] = (int)$value;
    }

    // attributes
    public function getPaymentTypeLabelAttribute()
    {
        return self::$PAYMENT_TYPE[$this->payment_type];
    }

    public function getDeliveryTypeLabelAttribute()
    {
        return self::$DELIVERY_TYPE[$this->delivery_type];
    }

    public function getStatusTextAttribute()
    {
        return self::$STATUS_ARRAY[$this->status][0];
    }

    public function getStatusUIAttribute()
    {
        return '<span class="btn-status btn-status-'.self::$STATUS_ARRAY[$this->status][1].'">'.self::$STATUS_ARRAY[$this->status][0].'</span>';
    }

    public function getPaymentStatusUIAttribute()
    {
        return '<span class="btn-status btn-status-'.self::$PAYMENT_STATUS_ARRAY[$this->status][1].'">'.self::$PAYMENT_STATUS_ARRAY[$this->status][0].'</span>';
    }
}
