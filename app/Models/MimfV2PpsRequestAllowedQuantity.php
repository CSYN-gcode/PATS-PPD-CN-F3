<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\MimfV2;

class MimfV2PpsRequestAllowedQuantity extends Model
{
    protected $table = 'mimf_v2_pps_request_allowed_quantities';
    protected $connection = 'mysql';
}
