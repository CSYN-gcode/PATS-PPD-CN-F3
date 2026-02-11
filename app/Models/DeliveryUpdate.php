<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryUpdate extends Model
{
    use HasFactory;
    protected $table = "delivery_updates";
    protected $connection = "mysql";

    public function runcard_info(){
    	return $this->hasOne(ProductionRuncard::class,'id', 'runcard_id');
    }
}
