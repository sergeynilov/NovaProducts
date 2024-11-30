<?php

namespace App\Models;

use App\Enums\BrandActiveEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $table = 'brands';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['name', 'website', 'active'];

    protected $casts
        = [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'active' => BrandActiveEnum::class,
        ];

    public function scopeGetOnlyActive($query): Builder
    {
        return $query->where($this->table . '.active', BrandActiveEnum::ACTIVE);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

}

