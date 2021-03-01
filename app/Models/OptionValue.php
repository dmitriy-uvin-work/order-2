<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Illuminate\Database\Eloquent\Model;

class OptionValue extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'option_id', 'name', 'sort', 'created_at', 'updated_at'];
}
