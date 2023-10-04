<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'api_key',
        'wharehouse_id',
        'wharehouse_name',
    ];

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
