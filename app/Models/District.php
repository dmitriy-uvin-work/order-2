<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'name', 'code', 'parent_code', 'region_id', 'created_at', 'updated_at'];
}
