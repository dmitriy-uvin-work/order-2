<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Brand extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'iid', 'name', 'slug', 'image', 'stock_id', 'country_id', 'description', 'status', 'deleted', 'last_update', 'created_at', 'updated_at'];

    protected $dates = ['last_update'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public static $helpers = [
        'folderName' => 'Brand',
    ];

    public function imageSize($field)
    {
        switch ($field) {
            case 'image':
                return [
                    'thumb' => [100, 48],
                    'large' => [200, null],
                    'original' => [null, null]
                ];
        }

        return [];
    }

    public function getRecommendedSize()
    {
        return "размер: 200x90";
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'iid')->withDefault();
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'iid')->withDefault();
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id', 'iid');
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
