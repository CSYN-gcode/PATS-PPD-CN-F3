<?php

namespace App\Http\Controllers;

use App\Exports\ExportLotTraceabilityReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

// use Illuminate\Http\Request;

class LottraceabilityController extends Controller
{
    public function exportLotTraceabilityReport(Request $request){

        // return $request->date_to;

        //QUERY BUILDER
        $secondMoldingData = DB::connection('mysql')
        ->table('production_runcards as a')
        ->Join('production_runcard_stations as b', 'a.id', '=', 'b.prod_runcards_id')
        ->leftJoin('production_runcard_station_mods as c', 'b.id', '=', 'c.prod_runcard_stations_id')
        ->leftJoin('defects_infos as d', 'c.mode_of_defects', '=', 'd.id')
        ->leftJoin('users as e', 'b.operator_name', '=', 'e.id')
        ->whereBetween('a.created_at', [$request->date_from, $request->date_to])
        // ->get();
        ->select(
                'a.id as id',
                'a.po_quantity as po_quantity',
                )
        // ->groupBy('a.id')
        ->get();

        // return $secondMoldingData;




        return Excel::download(new ExportLotTraceabilityReport(
                $secondMoldingData
        ),
        'Traceability Report.xlsx');


}
}
