<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaycomTransaction extends Model
{
    public function order()
    {
        return $this->belongsTo(PaycomOrder::class, 'order_id');
    }
}
