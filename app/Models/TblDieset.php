<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\TblWarehouse;
use App\Models\Devices;

class TblDieset extends Model
{
    use HasFactory;

    protected $connection = 'mysql_rapid_pps';
    protected $table = 'tbl_dieset';

    public function pps_warehous_info(){
        return $this->hasOne(TblWarehouse::class, 'MaterialType','DeviceName')->where('Factory', 3);
    }

    public function ppd_matrix_info(){
        return $this->hasOne(Devices::class, 'code','R3Code');
    }

}
