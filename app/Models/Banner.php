<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Banner extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'note', 'title', 'btn_text', 'link', 'image', 'sort', 'status', 'created_at', 'updated_at'];

    public static function rules()
    {
        return [
            'id' => 'bigint',
            'title' => 'required|string',
            'image' => 'required',
        ];
    }

    public static $helpers = [
        'folderName' => 'Banner',
    ];

    public function imageSize($field)
    {
        switch ($field) {
            case 'image':
                return [
                    'thumb' => [100, 48],
                    'large' => [1920, null, 85],
                    'original' => [null, null]
                ];
        }

        return [];
    }

    public function getRecommendedSize()
    {
        return 'размер: 1920x940';
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
