<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25.08.2020
 * Time: 12:28
 */

namespace App\Traits;


trait ModelHelperTrait
{

    public function folderPath($field = null)
    {
        return '/storage/images/'.self::$helpers['folderName'].'/'.($this->attributes['iid'] ?? $this->attributes['id']).'/'.$field;
    }

    public function getImg($field, $size)
    {
        $img = $this->$field;

        if ($img == null) {
            return '/frontend/images/Default/default.svg';
        }

        return str_replace('.', '_'.$size.'.', $this->folderPath($field).'/'.$img);
    }

    public function getImage($field, $src, $size = null)
    {
        if ($size) {
            return str_replace('.', '_'.$size.'.', $this->folderPath($field).'/'.$src);
        } else {
            return $this->folderPath($field).'/'.$src;
        }
    }

    // attributes
    public function getStatusUIAttribute()
    {
        switch ($this->attributes['status']) {
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
    public function getIsNewAttribute()
    {
        $newDate = new \DateTime('-2 days');
        return $this->created_at > $newDate;
    }

    // setters
    public function setCountryIdAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['country_id'] = (int)$value;
        }
    }
    public function setRegionIdAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['region_id'] = (int)$value;
        }
    }
    public function setBrandIdAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['brand_id'] = (int)$value;
        }
    }
    public function setOptionIdAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['option_id'] = (int)$value;
        }
    }

    // scopes
    public function scopeOnlyNew($data)
    {
        $newDate = new \DateTime('-2 days');

        return $data->whereDate('created_at','>', $newDate);
    }
    public function scopeOrderBySort($data)
    {
        return $data->orderBy('sort', 'asc');
    }
    public function scopeOrderByDate($data)
    {
        return $data->orderBy('created_at', 'desc');
    }
    public function scopeActive($data)
    {
        return $data->whereStatus(1);
    }

    public function scopeFilter($q)
    {
        if (request('name')) {
            $q->where('name', 'ILIKE', '%' . request('name') . '%');
        }

        if (request('iid')) {
            $q->where('iid', request('iid'));
        }

        if (request('in_stock')) {
            $q->where('quantity', '>', 0);
        }

        if (request('not_available')) {
            $q->where('quantity', 0);
        }

        if (request('status')) {
            $q->where('status', 1);
        }

        if (request('code')) {
            $q->where('code', request('code'));
        }

        if (request('color_id')) {
            $q->where('color_id', '!=', null);
        }

        if(request('category')) {
            $category = request('category');
            $q->whereHas('categories', function ($query) use ($category){
                $query->where('categories.id', $category)->orWhere('categories.parent_id', $category);
            });
        }

        if(request('option')) {
            $option_value = request('option');
            $q->whereHas('options', function ($query) use ($option_value){
                $query->whereIn('option_values.id', $option_value);
            });
        }

        if(request('tag')) {
            $tag = request('tag');
            $q->whereHas('tags', function ($query) use ($tag) {
                $query->where('tags.id', $tag);
            });
        }

        if(request('brand')) {
            $q->whereIn('brand_id', request('brand'));
        }

        if (request('price_max')) {
            $min = str_replace(' ', '', request('price_min'));
            $max = str_replace(' ', '', request('price_max'));

            $q->whereBetween('price', array((int)$min, (int)$max));
        }

        if (request('sort_by')) {
            $sort = explode('/', request('sort_by'));
            $q->orderBy($sort[0], $sort[1]);
        }
    }
}
