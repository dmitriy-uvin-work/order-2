<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'iid', 'name', 'fullname', 'code', 'alfa2', 'alfa3', 'deleted', 'last_update', 'created_at', 'updated_at'];

    protected $dates = ['last_update'];

    protected $guarded = [];

    public function brand()
    {
        return $this->hasOne(Brand::class, 'country_id', 'iid')->withDefault();
    }
}
