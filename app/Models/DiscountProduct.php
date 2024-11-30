<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DiscountProduct extends Model
{
    protected $table = 'discount_product';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['discount_id', 'product_id'];

    /* CREATE TABLE `discount_product` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `discount_id` tinyint unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`), */
    public function scopeGetByProductId($query, int $productId = null)
    {
        if ( ! empty($productId)) {
            $query->where($this->table . '.product_id', $productId);
        }

        return $query;
    }

    public function scopeGetByDiscountId($query, int $discountId = null)
    {
        if ( ! empty($discountId)) {
            $query->where($this->table . '.discount_id', $discountId);
        }

        return $query;
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

//    public function product(): BelongsTo
//    {
//        return $this->belongsTo(product::class);
//    }

    public function products(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

}

