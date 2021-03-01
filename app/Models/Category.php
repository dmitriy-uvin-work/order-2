<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Category extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'parent_id', 'name', 'slug', 'image', 'sort', 'status', 'stock_id', 'deleted', 'created_at', 'updated_at'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public static $helpers = [
        'folderName' => 'Category',
    ];

    public function imageSize($field)
    {
        switch ($field) {
            case 'image':
                return [
                    'thumb' => [100, 115],
                    'large' => [380, null],
                    'original' => [null, null]
                ];
        }

        return [];
    }

    public function getRecommendedSize()
    {
        return "размер: 380x430";
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id')->withDefault();
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'iid')->withDefault();
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product', 'category_id', 'product_id');
    }

    public static function getCategoryTree()
    {
        $arr = self::orderBy('sort')->withCount('children')->get();
        // Запускаем рекурсивную постройку дерева и отдаем на выдачу
        return self::buildTree($arr, 0);
    }

    // Сама функция рекурсии
    public static function buildTree($arr, $pid = 0) {
        // Находим всех детей раздела
        $found = $arr->filter(function($item) use ($pid){
            return $item->parent_id == $pid;
        });

        // Каждому детю запускаем поиск его детей
        foreach ($found as $key => $cat) {
            $sub = self::buildTree($arr, $cat->id);
            $cat->sub = $sub;
        }

        return $found;
    }

    public function options()
    {
        return $this->belongsToMany(Option::class, 'category_option', 'category_id', 'option_id')->orderBy('created_at', 'asc');
    }

    public function singleOptions()
    {
        return $this->belongsToMany(OptionValue::class, 'category_option', 'category_id', 'single_option_id', 'id')->orderBy('created_at', 'asc');
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'category_brand', 'category_id', 'brand_id')->orderBy('name');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($model) {
            /**
             * Remove Folder
             */
            $imageFolder = public_path() . $model->folderPath();
            File::deleteDirectory($imageFolder);

            foreach ($model->children as $child)
            {
                $child->delete();
            }
        });
    }
}
