<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityProduct extends Model
{
    protected $table = 'city_product';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['product_id', 'city_id' ];

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
    public function product(){
        return $this->belongsTo(Product::class, '.product_id','id');
    }

    public function scopeGetByCityId($query, int $cityId= null)
    {
        if (!empty($cityId)) {
            $query->where($this->table . '.city_id', $cityId);
        }
        return $query;
    }
    public function city(){
        return $this->belongsTo(City::class, '.city_id','id');
    }


//    public static function getValidationRulesArray() : array
//    {
//        $validationRulesArray = [
//            'product_id'     => 'required|exists:'.( with(new Product)->getTable() ).',id',
//            'city_id'     => 'required|exists:'.( with(new City)->getTable() ).',id',
//        ];
//        return $validationRulesArray;
//    }

}
