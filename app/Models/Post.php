<?php

namespace App\Models;

use App\Traits\ModelHelperTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Post extends Model
{
    use ModelHelperTrait;

    protected $fillable = ['id', 'title', 'slug', 'short_description', 'image', 'body', 'status', 'published_at', 'created_at', 'updated_at'];

    protected $dates = ['published_at'];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function setPublishedAtAttribute($value)
    {
        $this->attributes['published_at'] = $value ? Date::createFromFormat('d.m.Y', $value) : now()->format('Y-m-d');
    }

    public static $helpers = [
        'folderName' => 'Post',
    ];

    public function imageSize($field)
    {
        switch ($field) {
            case 'image':
                return [
                    'thumb' => [100, 52],
                    'medium' => [420, null],
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

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id')->orderBy('created_at', 'asc');
    }

    public function getMonth()
    {
        return __('static.month.'.$this->published_at->format('n'));
    }

    public function getDate()
    {
        return $this->published_at->format('d '.$this->getMonth().', Y ');
    }

    public function getDateAttribute()
    {
        return $this->published_at->format('d.m.Y');
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
