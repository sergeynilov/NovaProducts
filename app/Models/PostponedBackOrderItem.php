<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostponedBackOrderItem extends Model
{
    use HasFactory;

    protected $table = 'postponed_back_order_items';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['creator_id', 'status', 'product_id', 'order_id', 'expires_at', 'qty', 'price', 'manager_id'];

    /*          {
        Schema::create('postponed_back_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreignId('order_id')->references('id')->on('orders')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('product_id')->references('id')->on('products')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->datetime('expires_at');

            $table->integer('qty')->unsigned();
            $table->integer('price')->unsigned()->comment('Cast on client must be used - Money sum = value/100');
            $table->decimal('total_price', 8, 2)
                ->storedAs('price * qty') // Define the virtual column
                ->index()->comment('Cast on client must be used - Money sum = value/100'); // Index the virtual column

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();

            $table->index(['order_id', 'product_id', 'qty'], 'postponed_back_order_items_3fields_index');
        });
        Artisan::call('db:seed', array('--class' => 'postponedBackOrderItemsWithInitData'));
    }

 */
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

    public function scopeGetByStatus($query, $status = null)
    {
        if ( ! empty($status)) {
            if (is_array($status)) {
                $query->whereIn($this->table . '.status', $status);
            } else {
                $query->where($this->table . '.status', $status);
            }
        }

        return $query;
    }


    public function scopeGetByOnlyExpired($query, bool $onlyExpired = false)
    {
        if ( ! $onlyExpired) {
            return $query;
        }
        $todayDate = Carbon::now()->startOfDay();
        $query->whereRaw($this->table . ".expires_at < '" . $todayDate . "' ");

        return $query;
    }

    public function scopeGetByExpiresAt($query, $filterExpiresAt = null, string $sign = null)
    {
        if ( ! empty($filterExpiresAt)) {
            if ( ! empty($sign)) {
                if (in_array($sign, ['=', '<', '>', '<=', '>=', '!-', '<>'])) {
                    $query->whereRaw($this->table . '.expires_at' . $sign . ' ?', [$filterExpiresAt]);
                }
            } else {
                $query->where($this->table . '.expires_at', $filterExpiresAt);
            }
        }

        return $query;
    }


}
