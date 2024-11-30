<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

class ProductAttribute extends Model
{
    use HasFactory;
    protected $table = 'product_attributes';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['product_id', 'key', 'value' ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    public function scopeGetById($query, $id)
    {
        if (empty($id)) {
            return $query;
        }
        return $query->where($this->table . '.id', $id);
    }


    public function scopeGetByProductId($query, int $productId= null)
    {
        if (!empty($productId)) {
            $query->where($this->table . '.product_id', $productId);
        }
        return $query;
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id','id');
    }

//    public static function getValidationRulesArray() : array
//    {
//        $validationRulesArray = [
//            'product_id'     => 'required|exists:'.( with(new Product)->getTable() ).',id',
//            'attribute_id'     => 'required|exists:'.( with(new Attribute)->getTable() ).',id',
//        ];
//        return $validationRulesArray;
//    }

}
