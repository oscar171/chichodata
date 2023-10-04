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
        $wharehouses = Endpoint::select('wharehouse_name')->distinct()->get();
        $wharehousesName = [];
        foreach ($wharehouses as $wharehouse) {
            $wharehousesName[$wharehouse->wharehouse_name] = $wharehouse->wharehouse_name;
        }
        return $wharehousesName;
    }
}
