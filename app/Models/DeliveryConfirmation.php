<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryConfirmation extends Model
{
    use HasFactory;
    protected $table = "delivery_confirmation";
    protected $connection = "mysql";
}
