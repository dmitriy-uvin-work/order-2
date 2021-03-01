<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PaycomOrder extends Model
{
    protected $fillable = ['id', 'user_id', 'amount', 'product_ids', 'state', 'phone'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
