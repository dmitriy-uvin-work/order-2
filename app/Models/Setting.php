<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'meta_title', 'meta_description', 'meta_keywords', 'meta_tags', 'phone', 'email',
        'address', 'working_hours', 'instagram', 'twitter', 'vk', 'facebook', 'telegram', 'policy_link', 'about',
        'created_at', 'updated_at',];
}
