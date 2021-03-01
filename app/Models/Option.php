<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'name', 'sort', 'created_at', 'updated_at'];

    public function values()
    {
        return $this->hasMany(OptionValue::class, 'option_id');
    }
}
