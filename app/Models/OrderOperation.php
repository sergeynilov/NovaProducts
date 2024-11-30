<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderOperation extends Model
{
    protected $table = 'order_operations';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['order_id', 'creator_id', 'operation_type', 'before_status', 'status', 'info', 'error_info', 'ip_address' ];

    public function scopeGetById($query, $id)
    {
        if (empty($id)) {
            return $query;
        }
        return $query->where($this->table . '.id', $id);
    }

    public function scopeGetByOrderId($query, int $orderId= null)
    {
        if (!empty($orderId)) {
            $query->where($this->table . '.order_id', $orderId);
        }
        return $query;
    }
    public function category(){
        return $this->belongsTo(Order::class, 'order_id','id');
    }

}
