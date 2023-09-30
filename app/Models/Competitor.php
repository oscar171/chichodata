<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competitor extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'retail_product_id',
        'store_id',
        'store_name',
        'name',
        'sku',
        'brand',
        'model',
        'url',
        'image_url',
        'status',
        'created_retailer',
        'updated_retailer',
        'extracted',
        'lowest_price',
        'offer_price',
        'normal_price',
        'warehouse_name',
        'warehouse_id',
        'match_status'
    ];
}
