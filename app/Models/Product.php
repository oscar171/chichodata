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

    static public function getStoresNameOptions()
    {
        $stores = Product::select('store_name')->distinct()->get();
        $storesName = [];
        foreach ($stores as $store) {
            $storesName[$store->store_name] = $store->store_name;
        }
        return $storesName;
    }

    static public function getWharehouseNameOptions()
    {
        $wharehouses = Product::select('warehouse_name')->distinct()->get();
        $wharehousesName = [];
        foreach ($wharehouses as $wharehouse) {
            $wharehousesName[$wharehouse->store_name] = $wharehouse->warehouse_name;
        }
        return $wharehousesName;
    }
}
