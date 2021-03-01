<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'name', 'created_at', 'updated_at'];
}
