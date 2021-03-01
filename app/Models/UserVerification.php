<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    protected $fillable = ['id', 'value', 'code', 'token', 'type', 'verified_at', 'created_at', 'updated_at',];

    const TYPE_PHONE = 1;
    const TYPE_EMAIL = 2;
}
