<?php
/**
 * Model generated with custom stubs file generator.
 *
 * Version 0.9
 */

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\ProductDiscountPriceAllowedEnum;
use App\Enums\ProductInStockEnum;
use App\Enums\ProductIsFeaturedEnum;
use App\Enums\ProductStatusEnum;
use App\Library\Facades\LoggedUserFacade;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use MateuszPeczkowski\NovaHeartbeatResourceField\Traits\HasHeartbeats;

class Product extends Model implements HasMedia
{
    use Sluggable, InteractsWithMedia;
    use HasHeartbeats;

    protected $table = 'products';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $casts
        = [
            'published_at' => 'datetime',
            'regular_price' => MoneyCast::class,
            'sale_price' => MoneyCast::class,
            'status' => ProductStatusEnum::class,
            'in_stock' => ProductInStockEnum::class,
            'discount_price_allowed' => ProductDiscountPriceAllowedEnum::class,
            'is_featured' => ProductIsFeaturedEnum::class,
            'updated_at' => 'datetime',
            'created_at' => 'datetime',
        ];

    protected $fillable = ['title', 'user_id', 'brand_id', 'description', 'discount_price_allowed', 'in_stock', 'is_featured', 'published_at', 'regular_price', 'sale_price', 'short_description', 'sku', 'slug', 'status', 'stock_qty', 'pdf_help_file', 'audio_help_file'
        ];

    protected static function boot()
    {
        parent::boot();
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }


    public function scopeGetById($query, $id)
    {
        if (empty($id)) {
            return $query;
        }

        return $query->where($this->table . '.id', $id);
    }

    public function scopeGetByStatus($query, ProductStatusEnum $status = null)
    {
        if ( ! isset($status->value) or strlen($status->value) == 0) {
            return $query;
        }

        return $query->where($this->table . '.status', $status);
    }

    public function scopeGetByUserId($query, $userId = null)
    {
        if (empty($userId)) {
            return $query;
        }

        return $query->where($this->table . '.user_id', $userId);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function cityProduct(): HasMany
    {
        return $this->hasMany(CityProduct::class, 'product_id', 'id');
    }


    /**
     * Get all of the cities for the product.
     */
    public function cities(): BelongsToMany
    {
        return $this->belongsToMany(City::class);
    }

    /**
     * Get all of the category for the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'id');
    }

    public function scopeGetByCityId($query, $cityId = null)
    {
        if (empty($cityId)) {
            return $query;
        }

        return $query->where($this->table . '.city_id', $cityId);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function discountProducts(): HasMany
    {
        return $this->hasMany(DiscountProduct::class);
    }

    public function productAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }


    /**
     * Get all of the discounts for the product.
     */
    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class);
    }

    public function scopeGetPublishedAtBetween($query, Carbon $date1 = null, Carbon $date2 = null): Builder
    {
        if( !blank($date1) and !blank($date2)) {
            $query->whereBetween($this->table . '.published_at', [$date1, $date2]);
        }
        return $query;
    }


    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(130)
            ->height(130);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main')->singleFile();
        $this->addMediaCollection('my_multi_collection');
    }
//    public function discounts()
//    {
//        return $this->hasManyThrough(Discount::class, DiscountProduct::class);
//    }
//    public function discounts(): BelongsToMany
//    {
//        return $this->belongsToMany(Discount::class, 'discount_product'/*, 'discount_id', ''*/);
//    }


}
