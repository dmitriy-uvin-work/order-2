<?php

namespace App;

use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_MODERATOR = 'moderator';
    const ROLE_CLIENT = 'client';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'surname', 'email', 'email_verified_at', 'password', 'phone', 'phone_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->surname;
    }

    public function role()
    {
        return $this->hasOne(Role::class);
    }

    public function verified()
    {
        return (!empty($this->email_verified_at) || !empty($this->phone_verified_at));
    }

    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'favorite', 'user_id', 'product_id')->active()->orderBy('created_at', 'asc')->withTimestamps();
    }

    public function cart()
    {
        return $this->belongsToMany(Product::class, 'cart', 'user_id', 'product_id')->active()->orderBy('created_at', 'asc')->withPivot('quantity')->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id')->orderBy('created_at', 'asc');
    }
}
