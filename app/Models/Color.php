<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use ModelHelperTrait;

    protected $dates = ['last_update'];

    protected $fillable = ['id', 'iid', 'name', 'alt_name', 'deleted', 'last_update', 'created_at', 'updated_at'];

    public function getName()
    {
        return !empty($this->alt_name) ? $this->alt_name : $this->name;
    }
}
