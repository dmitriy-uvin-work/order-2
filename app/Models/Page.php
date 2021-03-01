<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Page extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'title', 'slug', 'body', 'image', 'sort', 'on_top', 'status', 'created_at', 'updated_at'];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public static $helpers = [
        'folderName' => 'Page',
    ];

    public function imageSize($field)
    {
        switch ($field) {
            case 'image':
                return [
                    'thumb' => [100, 52],
                    'large' => [946, null, 85],
                    'original' => [null, null]
                ];
        }
        return [];
    }

    public function getRecommendedSize()
    {
        return "размер: 946x450";
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            /**
             * Remove Folder
             */
            $imageFolder = public_path() . $model->folderPath();
            File::deleteDirectory($imageFolder);
        });
    }
}
