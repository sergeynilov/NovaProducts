<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShipping extends Model
{
    protected $table = 'order_shippings';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable
        = ['order_id', 'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_phone',
            'shipping_email', 'shipping_country', 'shipping_address', 'shipping_address2', 'shipping_city', 'shipping_state', 'shipping_postcode'];

    public function scopeGetById($query, $id)
    {
        if (empty($id)) {
            return $query;
        }

        return $query->where($this->table . '.id', $id);
    }

    public function scopeGetByOrderId($query, int $orderId = null)
    {
        if ( ! empty($orderId)) {
            $query->where($this->table . '.order_id', $orderId);
        }

        return $query;
    }

    public function category()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

}

