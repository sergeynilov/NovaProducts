<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['product_id', 'order_id', 'qty', 'price'];

    public function scopeGetById($query, $id)
    {
        if (empty($id)) {
            return $query;
        }

        return $query->where($this->table . '.id', $id);
    }

    public function scopeGetByProductId($query, int $productId = null)
    {
        if ( ! empty($productId)) {
            $query->where($this->table . '.product_id', $productId);
        }

        return $query;
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function scopeGetByOrderId($query, int $orderId = null)
    {
        if ( ! empty($orderId)) {
            $query->where($this->table . '.order_id', $orderId);
        }

        return $query;
    }

}
