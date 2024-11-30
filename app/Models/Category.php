<?php

namespace App\Models;

use App\Enums\CategoryActiveEnum;
use Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Validation\Rule;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia;
    use Sluggable;

    protected $table = 'categories';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable
        = [
            'name', 'parent_id', 'slug', 'active',  'description'
        ];

    public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $casts = [ 'created_at' => 'datetime', 'updated_at' => 'datetime', 'active' => CategoryActiveEnum::class];


    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeGetOnlyActive($query)
    {
        return $query->where($this->table . '.active', CategoryActiveEnum::ACTIVE);
    }

    public function scopeGetByParentId($query, ?int $parentId = null): Builder
    {
//        if ( ! empty($parentId)) {
            $query->where($this->table . '.parent_id', $parentId);
//        }

        return $query;
    }


    public function scopeGetByActive($query, $active = null)
    {
        if ( ! isset($active) or strlen($active) == 0) {
            return $query;
        }

        return $query->where(with(new Category)->getTable() . '.active', $active);
    }

    public function scopeGetBySlug($query, string $slug = null): Builder
    {
        if ( ! isset($slug) or strlen($slug) == 0) {
            return $query;
        }

        return $query->where($this->table . '.slug', $slug);
    }


    public function parent(): BelongsTo
  {
       return $this->belongsTo(Category::class, 'parent_id');
  }



    /*    public function scopeGetByKey($query, $key = null)
        {
            if ( ! empty($key)) {
                $query->where(with(new Category)->getTable() . '.key', $key);
            }

            return $query;
        }

        public function scopeGetByAuthorId($query, $author_id = null)
        {
            if (empty($author_id)) {
                return $query;
            }

            return $query->where(with(new Category)->getTable() . '.author_id', $author_id);
        }

        public function scopeGetById($query, $id)
        {
            if ( ! empty($id)) {
                if (is_array($id)) {
                    $query->whereIn(with(new Category)->getTable() . '.id', $id);
                } else {
                    $query->where(with(new Category)->getTable() . '.id', $id);
                }
            }

            return $query;
        }

        public function scopeGetByTitle($query, $title = null)
        {
            if (empty($title)) {
                return $query;
            }

            return $query->where(with(new Category)->getTable() . '.title', 'like', '%' . $title . '%');
        }

        public function scopeGetByPublished($query, $published = null)
        {
            if ( ! isset($published) or strlen($published) == 0) {
                return $query;
            }

            return $query->where(with(new Category)->getTable() . '.published', $published);
        }

        public function author()
        {
            return $this->belongsTo(User::class);
        }


        public static function getCategoryValidationRulesArray($cms_item_id = null, array $skipFieldsArray = []): array
        {
            $validationRulesArray = [
                'title'     => [
                    'required',
                    'string',
                    'max:255',
                ],
                'key'       => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique(with(new Category)->getTable())->ignore($cms_item_id),
                ],
                'text'      => 'required',
                'published' => 'boolean',
            ];

            foreach ($skipFieldsArray as $next_field) {
                if ( ! empty($validationRulesArray[$next_field])) {
                    unset($validationRulesArray[$next_field]);
                }
            }

            return $validationRulesArray;
        }*/

}
