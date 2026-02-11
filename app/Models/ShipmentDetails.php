<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentDetails extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'fkControlNo',
    //     'order_no',
    //     'category',
    //     'item_code',
    //     'item_name',
    //     'shipout_qty',
    //     'unit_price',
    //     'amount',
    //     'remarks',
    //     'logdel'
    // ];

    // public function shipment(){
    //     return $this->belongsTo(Shipment::class, 'fkControlNo');
    // }

    protected $connection = 'mysql';
    protected $table = 'shipment_details';
}
