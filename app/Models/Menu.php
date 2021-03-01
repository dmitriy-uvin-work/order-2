<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Menu extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'parent_id', 'name', 'image', 'url', 'sort', 'created_at', 'updated_at'];

    public static $helpers = [
        'folderName' => 'Menu',
    ];

    public function imageSize($field)
    {
        switch ($field) {
            case 'image':
                return [
                    'thumb' => [73, 61],
                    'large' => [73, 61],
                    'original' => [null, null]
                ];
        }

        return [];
    }

    public function getRecommendedSize()
    {
        return "размер: 75x65";
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($model) {
            /**
             * Remove Folder
             */
            $imageFolder = public_path() . $model->folderPath();
            File::deleteDirectory($imageFolder);
        });
    }
}
