<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Stock extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'iid', 'name', 'short_description', 'discount', 'note', 'color', 'product_id', 'image', 'body',
        'active', 'site_active', 'started_at', 'ended_at', 'deleted', 'last_update', 'created_at', 'updated_at',];

    protected $dates = ['started_at', 'ended_at'];

    public static $helpers = [
        'folderName' => 'Stock',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'stock_id', 'iid');
    }

    public function brands()
    {
        return $this->hasMany(Brand::class, 'stock_id', 'iid');
    }

    public function getCategories()
    {
        return $this->hasMany(Category::class, 'stock_id', 'iid');
    }

    public function getStockProductAttribute()
    {
        $products = [];
        if ($this->products()->count()) {
            $products = $this->products;
        } elseif ($this->brands()->count()) {
            $products = $this->brands[0]->products;
        } elseif ($this->getCategories()->count()) {
            $products = $this->categories[0]->products;
        }

        return count($products) ? $products->shuffle()->first() : null;
    }

    public function imageSize($field)
    {
        switch ($field) {
            case 'image':
                return [
                    'thumb' => [100, 52],
                    'large' => [946,null],
                    'original' => [null, null]
                ];
        }

        return [];
    }

    public function getRecommendedSize()
    {
        return "размер: 946x500";
    }

    public function getStatusUIAttribute()
    {
        switch ($this->isActive()) {
            case 1:
                return '<span class="btn-status btn-status-success">активно</span>';
                break;
            case 0:
                return '<span class="btn-status btn-status-danger">не активно</span>';
                break;
            default:
                return '';
        }
    }

    public function getActiveUIAttribute()
    {
        switch ($this->site_active) {
            case 1:
                return '<span class="btn-status btn-status-success">активно</span>';
                break;
            case 0:
                return '<span class="btn-status btn-status-danger">не активно</span>';
                break;
            default:
                return '';
        }
    }

    public function scopeFilled($data)
    {
        return $data->where('image', '!=', '');
    }

    public function scopeActive($data)
    {
        return $data->where(['active'=>1, 'site_active' => 1])->where('started_at', '<=', Carbon::now())->where('ended_at', '>=', Carbon::now());
    }

    private function isActive()
    {
        return ($this->active == 1) && Carbon::now()->between($this->started_at, $this->ended_at);
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
