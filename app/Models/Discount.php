<?php

namespace App\Models;

use App\Enums\DiscountActiveEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
//use Laravel\Nova\Fields\HasManyThrough;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Discount extends Model
{
    use SortableTrait;

    public $sortable = [
        'order_column_name' => 'sort_order',
        'sort_when_creating' => true,
        'nova_order_by' => 'DESC',
    ];

    protected $table = 'discounts';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable
        = [
            'name', 'active', 'active_from', 'active_till', 'sort_order', 'min_qty', 'max_qty', 'percent', 'description'
        ];

    protected $casts
        = [
            'active_from' => 'datetime',
            'active_till' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'active' => DiscountActiveEnum::class,
        ];

    public function scopeGetById($query, $id)
    {
        return $query->where($this->table . '.id', $id);
    }

//    public function discountProducts() : HasMany
//    {
//        return $this->hasMany(DiscountProduct::class);
//    }

    public function scopeGetByActive($query, $active = null)
    {
        if ( ! isset($active) or strlen($active) == 0) {
            return $query;
        }

        return $query->where($this->table . '.active', $active);
    }

    public function scopeGetOnlyActive($query)
    {
        return $query->where($this->table . '.active', DiscountActiveEnum::ACTIVE);
    }



    /**
     * Get all of the discounts for the product.
     */
//    public function discounts()
//    {
//        return $this->hasManyThrough(Discount::class, DiscountProduct::class);
//    }


    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

}
