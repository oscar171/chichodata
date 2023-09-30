<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'product_id',
        'store_id',
        'store_name',
        'name',
        'sku',
        'brand',
        'model',
        'url',
        'image_url',
        'status',
        'created_cocacola',
        'updated_cocacola',
        'extracted',
        'lowest_price',
        'offer_price',
        'normal_price',
        'warehouse_name',
        'warehouse_id',
    ];

    public function categories()
    {
        return $this->belongsToMany(Categorie::class);
    }

    public function competitors()
    {
        return $this->hasMany(Competitor::class);
    }
}
