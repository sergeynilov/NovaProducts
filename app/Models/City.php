<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class City extends Model
{
    protected $table = 'cities';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['address', 'postal_code', 'country',  'region', 'geo_lat', 'geo_lon'];


    public function scopeGetByRegion($query, $region = null)
    {
        if (!isset($region) or strlen($region) == 0) {
            return $query;
        }
        return $query->where(with(new City)->getTable().'.region', $region);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public static function getCitiesSelectionArray($return_array= false) :array {
        $cities = City::orderBy('address','asc')->get();
        $citiesSelectionArray= [];
        foreach( $cities as $nextCity ) {
            if(!$return_array) {
                if(!empty($nextCity->id) and !empty($nextCity->city)) {
                    $citiesSelectionArray[$nextCity->id] = $nextCity->city;
                }
            } else {
                if( !empty($nextCity->address)) {
                    $citiesSelectionArray[] = [
                        'code'    => $nextCity->id,
                        'label' => /*  $nextCity->id .' : '  .  */ $nextCity->address,
                    ];
                    continue;
                }
            }
        }
        return $citiesSelectionArray;
    }


}
