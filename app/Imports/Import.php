<?php

namespace App\Imports;

Use App\MimfV2;

use Maatwebsite\Excel\Concerns\ToModel;

class Import implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row){
        return new MimfV2([
            'pmi_po_no' => $row[0]
        ]);
    }
}
