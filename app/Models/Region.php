<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'name', 'code', 'delivery', 'sort', 'created_at', 'updated_at'];

    public function districts()
    {
        return $this->hasMany(District::class, 'region_id');
    }

    public function setDeliveryAttribute($value) {
        $this->attributes['delivery'] = json_encode($value);
    }

    public function getPrice($weight): int
    {
        $dy = json_decode($this->delivery);
        if ($dy) {
            $kg = $weight / 1000;
            foreach ($dy as $item) {
                if (($kg >= (int)$item->{'from'} && $kg <= (int)$item->{'up-to'})) {
                    return (int)$item->{'price'};
                }
            }
        }
        // default price for delivery
        return 25000;
    }
}
