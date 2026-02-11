<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'ctrl_no',
    //     'ps_ctrl_no',
    //     'shipment_date',
    //     'rev_no',
    //     'sold_to',
    //     'shipped_by',
    //     'cutoff_month',
    //     'grand_total',
    //     'logdel'
    // ];

    protected $connection = 'mysql';
    protected $table = 'shipments';

    public function shipment_details(){
        return $this->hasMany(ShipmentDetails::class, 'shipment_id', 'id');
    }
}
