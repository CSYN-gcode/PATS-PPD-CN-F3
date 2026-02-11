<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\DeliveryUpdate;
use App\Models\ProductionRuncard;

class DeliveryUpdateController extends Controller
{
    public function search_po_delivery_update(Request $request){
        // $details = DB::connection('mysql')
        //     ->table('production_runcards')
        //     ->where('po_number', $request->po_number)
        //     ->whereNull('deleted_at')
        //     ->first();

        $po_received_info = DB::connection('mysql_rapid_pps')
            ->select("SELECT id, OrderNo, OrderQty
                FROM tbl_POReceived
                WHERE OrderNo = ? AND logdel = '0'", [$request->po_number]
            );

        $runcard_infos = ProductionRuncard::with(['oqc_inspection_info'])
                                        ->where('po_number', $request->po_number)
                                        ->where('status', 3)
                                        ->whereNull('deleted_at')
                                        ->orderBy('id', 'DESC')
                                        ->first();

        // return $runcard_infos;

        $po_received_count = count($po_received_info);
        $runcard_count = $runcard_infos ? 1 : 0;

        $result = ($po_received_count == $runcard_count) ? 1 : 0;

        $sum_actual_so = DB::connection('mysql')
            ->table('delivery_updates')
            ->where('po_no', $request->po_number)
            ->where('logdel', 0)
            ->sum('actual_so');

        return response()->json([
            'poReceivedInfo'    => $po_received_info,
            'runcardInfos'      => $runcard_infos,
            'result'            => $result,
            'sumActualSo'       => $sum_actual_so
        ]);
    }

    public function view_delivery_update(Request $request){
        $delivery_details = DB::connection('mysql')
            ->table('delivery_updates')
            ->join('users', 'delivery_updates.created_by', '=', 'users.employee_id')
            ->join('production_runcards', 'delivery_updates.runcard_id', '=', 'production_runcards.id')
            ->where('po_no', $request->po)
            ->where('logdel', 0)
            ->select('delivery_updates.*', 'production_runcards.shipment_output', 'users.employee_id', 'users.firstname', 'lastname')
            ->get();

            return DataTables::of($delivery_details)
            ->addColumn('action', function($delivery_detail){
                $result = "";
                $result .= "<center>";
                $result .= "<button class='btn btn-sm btn-dark btnDeliveryUpdate' delivery_update-id='$delivery_detail->id' data-bs-toggle='modal' data-bs-target='#modalDeliveryUpdate' value='0'><i class='fa-solid fa-edit'></i></button>";
                $result .= "</center>";
                return $result;
            })

            ->addColumn('variance', function($delivery_detail) use ($request) {
                static $remaining_qty = null;

                $variance_checking = DeliveryUpdate::with('runcard_info')
                    // ->where('id', $delivery_detail->id)
                    ->where('po_no', $delivery_detail->po_no)
                    ->where('logdel', 0)
                    ->orderBy('id', 'desc')
                    ->get();

                if ($remaining_qty === null) {
                    // $remaining_qty = $variance_checking[0]->runcard_info->po_quantity;
                    $remaining_qty =  $request->poQty;
                }

                $variance = $remaining_qty - $delivery_detail->actual_so;
                $remaining_qty = $variance;

                $result = $variance;
                // $result .="<br><br>Runcard QTY: ".$variance_checking[0]->runcard_info->po_quantity;
                // $result .="<br>Remaining QTY: ".$remaining_qty;
                // $result .="<br>Actual S/O: ".$delivery_detail->actual_so;
                // $result .="<br>Query: ".$delivery_detail->id;
                return $result;
            })

        ->addColumn('created_by', function($delivery_detail){
            $result = '';
            if($delivery_detail->firstname != ''){
                $result .= $delivery_detail->firstname.' '.$delivery_detail->lastname;
            }
            return $result;
        })

            ->rawColumns(['action', 'variance', 'created_by'])
            ->make(true);
    }


    public function get_employee_name(Request $request){
        date_default_timezone_set('Asia/Manila');

        $user_details = User::where('status', 1)->get();
        return response()->json(['userDetails' => $user_details]);
    }

    public function get_lot_no(Request $request){
        date_default_timezone_set('Asia/Manila');

        if($request->checking == 0){
            $runcard_details = ProductionRuncard::where('po_number', $request->poNumber)
                ->where('status', 3)
                ->whereNull('deleted_at')
                ->get();

            $check_delivery_update = '';
        }else{
            $runcard_details = ProductionRuncard::with('delivery_update_details')->where('po_number', $request->poNumber)
                ->where('production_lot', $request->runcardNumber)
                ->where('status', 3)
                ->whereNull('deleted_at')
                ->get();

                $check_delivery_update = DeliveryUpdate::where('po_no', $request->poNumber)->where('logdel', 0)->exists();
        }

        return response()->json(['runcardDetails' => $runcard_details, 'checkDeliveryUpdate' => $check_delivery_update]);
    }

    public function save_delivery_update(Request $request){
        date_default_timezone_set('Asia/Manila');
        $data = $request->all();

        $rules = [
        ];

        $validator = Validator::make($data, $rules);

        if($validator->passes()){
            DB::beginTransaction();
            try{
                $details = [
                    'runcard_id'        => $request->runcard_no,
                    'po_no'             => $request->po_no,
                    'po_received_qty'   => $request->po_received_quantity,
                    'lot_category'      => $request->lot_category,
                    'lot_no'            => $request->lot_no,
                    'lot_no_ext'        => $request->lot_no_ext,
                    'actual_so'         => $request->actual_so,
                    'package_category'  => $request->package_category,
                    'remarks'           => $request->remarks,
                ];

                if($request->delivery_update_id == ''){
                    $details['created_by']  = $request->scan_by;
                    $details['created_at']  = date('Y-m-d H:i:s');

                    DeliveryUpdate::insert(
                        $details
                    );
                }else{
                    $details['updated_by']  = $request->scan_by;
                    $details['updated_at']  = date('Y-m-d H:i:s');
                    DeliveryUpdate::where('id', $request->delivery_update_id)->update(
                        $details
                    );
                }
                DB::commit();
                return response()->json(['hasError' => 0, 'actualso' => $request->actual_so]);
            }
            catch(Exemption $e){
                DB::rollback();
                return $e;
            }
        }
        else{
            return response()->json(['hasError' => 1, 'exceptionError' => $e->getMessage()]);
        }
    }

    public function get_delivery_update_by_id(Request $request){
        date_default_timezone_set('Asia/Manila');

        $delivery_update_details = DeliveryUpdate::where('id', $request->deliveryUpdateId)->where('logdel', 0)->get();

        return response()->json(['deliveryUpdateDetails' => $delivery_update_details]);
    }

}
