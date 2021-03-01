<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $dates = ['last_update'];

    protected $fillable = ['id', 'iid', 'parent_id', 'name', 'deleted', 'last_update', 'created_at', 'updated_at'];
}
