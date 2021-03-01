<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoProgramSetting extends Model
{
    protected $fillable = ['id', 'iid', 'program_id', 'key', 'value', 'discount', 'interaction_method', 'deleted', 'last_update', 'created_at', 'updated_at'];
}
