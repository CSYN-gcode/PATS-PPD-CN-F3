<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\DhdMonitoring;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DhdMonitoringController extends Controller
{
    public function viewDhdMonitoring(Request $request){
        $dhd_details = DhdMonitoring::where('logdel', 0)->get();
        // return $dhd_details;
        return DataTables::of($dhd_details)
        ->addColumn('action', function($dhd_details){
            $result = "<center>";
            $result .= "<button class='btn btn-primary btn-sm btnEdit mr-1' data-id='$dhd_details->id'><i class='fa-solid fa-pen-to-square'></i></button>";
            $result .= "</center>";
            return $result;
        })
        ->addColumn('dhd_number', function($dhd_details){
            $result = "<center>";
            $result .= "";
            $result .= "</center>";
            return $result;
        })

        ->addColumn('total_mixed_mat_kgs', function($dhd_details){
            $result = "<center>";
            $result .= "";
            $result .= "</center>";
            return $result;
        })

        ->rawColumns(['action','dhd_number', 'total_mixed_mat_kgs'])
        ->make(true);
    }

    public function add_dhd_monitoring(Request $request){
        date_default_timezone_set('Asia/Manila');
        // return $request->all();
        if(!isset($request->id)){
            $validation = array(
                'dhd_no' => ['required', 'string', 'max:255'],
                'device_name' => ['required', 'string', 'max:255'],
                'device_code' => ['required', 'string', 'max:255'],
                'mtl_name' => ['required', 'string', 'max:255'],
                'mtl_lot_virgin' => ['required', 'string', 'max:255'],
                'mtl_lot_recycle' => ['required', 'string', 'max:255'],
                'mtl_mix_virgin' => ['required', 'string', 'max:255'],
                'mtl_mix_recycle' => ['required', 'string', 'max:255'],
                'mtl_ttl_mixing' => ['required', 'string', 'max:255'],
                'mtl_dry_setting' => ['required', 'string', 'max:255'],
                'mtl_dry_actual' => ['required', 'string', 'max:255'],
                'mtl_dry_timeIn' => ['required', 'string', 'max:255'],
                'mtl_dry_timeOut' => ['required', 'string', 'max:255'],
                'dhd_ashift_actual_temp' => ['required', 'string', 'max:255'],
                'dhd_ashift_mtl_level' => ['required', 'string', 'max:255'],
                'dhd_ashift_time' => ['required', 'string', 'max:255'],
                'person_incharge' => ['required', 'string', 'max:255'],
                'qc_inspector' => ['required', 'string', 'max:255']
            );
        }else{
            $validation = array(
                'dhd_no' => ['required', 'string', 'max:255'],
                'device_name' => ['required', 'string', 'max:255'],
                'device_code' => ['required', 'string', 'max:255'],
                'mtl_name' => ['required', 'string', 'max:255'],
                'mtl_lot_virgin' => ['required', 'string', 'max:255'],
                'mtl_lot_recycle' => ['required', 'string', 'max:255'],
                'mtl_mix_virgin' => ['required', 'string', 'max:255'],
                'mtl_mix_recycle' => ['required', 'string', 'max:255'],
                'mtl_ttl_mixing' => ['required', 'string', 'max:255'],
                'mtl_dry_setting' => ['required', 'string', 'max:255'],
                'mtl_dry_actual' => ['required', 'string', 'max:255'],
                'mtl_dry_timeIn' => ['required', 'string', 'max:255'],
                'mtl_dry_timeOut' => ['required', 'string', 'max:255'],
                'dhd_ashift_actual_temp' => ['required', 'string', 'max:255'],
                'dhd_ashift_mtl_level' => ['required', 'string', 'max:255'],
                'dhd_ashift_time' => ['required', 'string', 'max:255'],
                'person_incharge' => ['required', 'string', 'max:255'],
                'qc_inspector' => ['required', 'string', 'max:255']
            );
        }

        $data = $request->all();
        $validator = Validator::make($data, $validation);
        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                $dhd_array = array(
                    'dhd_no' => $request->dhd_no,
                    'device_name' => $request->device_name,
                    'device_code' => $request->device_code,
                    'mtl_name' => $request->mtl_name,
                    'mtl_lot_virgin' => $request->mtl_lot_virgin,
                    'mtl_lot_recycle' => $request->mtl_lot_recycle,
                    'mtl_mix_virgin' => $request->mtl_mix_virgin,
                    'mtl_mix_recycle' => $request->mtl_mix_recycle,
                    'mtl_ttl_mixing' => $request->mtl_ttl_mixing,
                    'mtl_dry_setting' => $request->mtl_dry_setting,
                    'mtl_dry_actual' => $request->mtl_dry_actual,
                    'mtl_dry_timeIn' => $request->mtl_dry_timeIn,
                    'mtl_dry_timeOut' => $request->mtl_dry_timeOut,
                    'dhd_ashift_actual_temp' => $request->dhd_ashift_actual_temp,
                    'dhd_ashift_mtl_level' => $request->dhd_ashift_mtl_level,
                    'dhd_ashift_time' => $request->dhd_ashift_time,
                    'dhd_bshift_actual_temp' => $request->dhd_bshift_actual_temp,
                    'dhd_bshift_mtl_level' => $request->dhd_bshift_mtl_level,
                    'dhd_bshift_time' => $request->dhd_bshift_time,
                    'person_incharge' => $request->person_incharge,
                    'qc_inspector' => $request->remarks,
                    'dhd_bshift_actual_temp' => $request->dhd_bshift_actual_temp,
                    'remarks' => $request->remarks,

                );
                if(isset($request->id)){ // EDIT
                    DhdMonitoring::where('id', $request->id)
                    ->update($dhd_array);
                }
                else{ // ADD
                    $dhd_array['created_by'] = Auth::user()->id;
                    $dhd_array['created_at'] = date('Y-m-d H:i:s');

                    DhdMonitoring::insert($dhd_array);
                }

                DB::commit();

                return response()->json(['result' => 1, 'msg' => 'Successfully Added']);
            }
            catch(Exemption $e){
                DB::rollback();
                return $e;
            }



        }
    }

    public function get_dhd_monitoring(Request $request){
        return DhdMonitoring::where('id', $request->id)->first();
    }

}
