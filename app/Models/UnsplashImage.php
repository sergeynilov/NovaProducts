<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UnsplashImage extends Model
{
    use Sluggable;
    protected $table = 'unsplash_images';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['title', 'slug', 'featured', 'unsplash_id'];

    public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function scopeGetBySlug($query, string $slug = null): Builder
    {
        if ( ! isset($slug) or strlen($slug) == 0) {
            return $query;
        }

        return $query->where($this->table . '.slug', $slug);
    }

    public function scopeGetByFeatured($query, $featured = null)
    {
        if ( ! isset($featured) or strlen($featured) == 0) {
            return $query;
        }

        return $query->where(with(new Category)->getTable() . '.featured', $featured);
    }



    /*         Schema::create('unsplash_images', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->boolean('featured')->default(false);

            $table->string('unsplash_id', 20);
            $table->timestamps();
        });
 */




}
