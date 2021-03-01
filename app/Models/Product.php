<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Product extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'iid', 'name', 'alt_name', 'slug', 'code', 'image', 'gallery', 'description', 'information', 'volume',
        'quantity', 'stock_id', 'category_id', 'color_id', 'producer_id', 'brand_id', 'group_id', 'status', 'deleted', 'last_update', 'created_at', 'updated_at'];

    protected $dates = ['last_update'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public static $helpers = [
        'folderName' => 'Product',
    ];

    public function imageSize($field)
    {
        switch ($field) {
            case 'image':
            case 'gallery':
                return [
                    'thumb' => [52, 52],
                    'medium' => [315, null],
                    'large' => [700, null],
                    'original' => [null, null]
                ];
        }

        return [];
    }

    public function name()
    {
        return !empty($this->alt_name) ? $this->alt_name : $this->name;
    }

    public function getRecommendedSize()
    {
        return "размер: 700x700";
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'iid')->withDefault();
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'iid')->withDefault();
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'iid')->withDefault();
    }

    public function options()
    {
        return $this->belongsToMany(OptionValue::class, 'option_product', 'product_id', 'option_id', 'iid')->orderBy('created_at', 'asc');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag', 'product_id', 'tag_id', 'iid')->orderBy('created_at', 'asc');
    }

    public function scopeActive($data)
    {
        return $data->where('price', '>', 0);
    }

    public function scopeQuantity($data)
    {
        return $data->where('quantity', '>', 0);
    }

    public function discount()
    {
        $discount = 0;
        $stocks = [];

        if ($this->categories()->count()) {
            foreach ($this->categories as $category) {
                if ($category && $category->stock && $category->stock->discount) {
                    $stocks[] = $category->stock;
                }
            }
        }

        if ($this->brand && $this->brand->stock && $this->brand->stock->discount) {
            $stocks[] = $this->brand->stock;
        }

        if ($this->stock && $this->stock->discount) {
            $stocks[] = $this->stock;
        }

        foreach ($stocks as $stock) {
            if ($stock->active && $stock->started_at <= Carbon::now() && $stock->ended_at >= Carbon::now()) {
                $discount = $stock->discount;
            }
        }

        return $discount;
    }

    public function getPrice()
    {
        return $this->discount() ? $this->price - ($this->price * $this->discount() / 100) : $this->price;
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
