<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

use Auth;
use DataTables;

use App\Imports\Import;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\User;
use App\Models\MimfV2;
use App\Models\Devices;
use App\Models\TblDieset;
use App\Models\PPSRequest;
use App\Models\PPSItemList;
use App\Models\TblWarehouse;
use App\Models\TblPoReceived;
use App\Models\MimfV2PpsRequest;
use App\Models\MimfV2PpsRequestAllowedQuantity;

class MimfV2Controller extends Controller
{
    public function viewMimfV2(Request $request){
        date_default_timezone_set('Asia/Manila');

        $get_mimfs = MimfV2::with([
            'pps_po_received_info.po_received_to_pps_whse_info',
        ])
        ->where('logdel', 0)
        ->orderBy('control_no', 'DESC')
        ->get();
        
        // return $get_mimfs;
        return DataTables::of($get_mimfs)
        ->addColumn('action', function($get_mimf) use($request){
            $mimf_stamping_matrix_id = "";
            $result = '<center>';
                // if($get_mimf->pps_po_received_info->POBalance != 0){ // COMMENT MUNA SABI NI AIRA, KAILANGAN DAW MAG REQUEST KAHIT 0 BALANCE NA - 02-10-2025
                    $balance = '';
                    $result .= '
                    <button class="btn btn-dark btn-sm text-center mr-2
                    actionEditMimf"
                    mimf-id="'. $get_mimf->id .'"
                    po_received-id="'. $get_mimf->pps_po_received_info->id .'"
                    data-bs-toggle="modal"
                    data-bs-target="#modalMimf"
                    data-bs-keyboard="false" title="Edit">
                    <i class="nav-icon fa fa-edit"></i>
                    </button>';
                // }else{
                //     $balance = '0';
                //     $result .= '<span class="badge badge-pill badge-success"> COMPLETED! </span><br>';
                // }


                $result .= '
                <button class="btn btn-warning btn-sm text-center
                    actionMimfPpsRequest"
                    mimf-id="'. $get_mimf->id .'"
                    device_name-id="'. $get_mimf->device_name .'"
                    mimf-status="'. $get_mimf->status .'"
                    balance="'. $balance .'"
                    data-bs-toggle="modal"
                    data-bs-target="#modalMimf"
                    data-bs-keyboard="false" title="PPS Request">
                    <i class="nav-icon fa fa-history"></i>
                </button><br>';
            $result .= '</center>';
            return $result;
        })

        ->addColumn('yec_po_no', function($get_mimf){
            $result = $get_mimf->pps_po_received_info->ProductPONo;
            return $result;
        })

        ->addColumn('po_balance', function($get_mimf){
            $result = $get_mimf->pps_po_received_info->POBalance;
            return $result;
        })

        ->rawColumns([
            'action',
            'category',
            'yec_po_no',
            'po_balance'
        ])
        ->make(true);
    }

    public function getControlNoV2(Request $request){
        date_default_timezone_set('Asia/Manila');

        $get_last_control_no = MimfV2::orderBy('id', 'DESC')->where('logdel', 0)->first();
        $control_no_format = "MIMF-".NOW()->format('ym')."-";

        if ($get_last_control_no == null){
            $new_control_no = $control_no_format.'001';
        }elseif(explode('-',$get_last_control_no->control_no)[1] != NOW()->format('ym')){
            $new_control_no = $control_no_format.'001';
        }else{
            $explode_control_no = explode("-",  $get_last_control_no->control_no);
            $string_pad = str_pad($explode_control_no[2]+1,3,"0",STR_PAD_LEFT);
            $new_control_no = $control_no_format.$string_pad;
        }
        return response()->json(['newControlNo' => $new_control_no]);
    }

    public function getPmiPoFromPoReceived(Request $request){
        date_default_timezone_set('Asia/Manila');

            $get_po_received_pmi_po_for_molding = TblPoReceived::with([
                'matrix_info',
            ])
            ->where('OrderNo',$request->getValue)
            ->where('logdel', 0)
            ->get();

            return response()->json(['getPoReceivedPmiPoForMolding' => $get_po_received_pmi_po_for_molding]);
    }

    public function createUpdateMimfV2(Request $request){
        date_default_timezone_set('Asia/Manila');
        $data = $request->all();
        $validator = Validator::make($data, [
            'mimf_control_no'               => 'required',
            'mimf_pmi_po_no'                => 'required',
            'mimf_date_issuance'            => 'required',
            'mimf_prodn_quantity'           => 'required',
            'mimf_device_code'              => 'required',
            'mimf_device_name'              => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['validationHasError' => 1, 'error' => $validator->messages()]);
        }else{
            DB::beginTransaction();
            try {
                $check_existing_po = MimfV2::with('mimf_request_details')->where('pmi_po_no', $request->mimf_pmi_po_no)
                    ->where('logdel', 0)
                    ->get();

                $check_existing_control_no = MimfV2::where('control_no', $request->mimf_control_no)->where('logdel', 0)->exists();

                $mimf = [
                    'pps_po_rcvd_id'        => $request->pps_po_rcvd_id,
                    'control_no'            => $request->mimf_control_no,
                    'date_issuance'         => $request->mimf_date_issuance,
                    'pmi_po_no'             => $request->mimf_pmi_po_no,
                    'prodn_qty'             => $request->mimf_prodn_quantity,
                    'device_code'           => $request->mimf_device_code,
                    'device_name'           => $request->mimf_device_name,
                ];

                if(isset($request->ul_sticker)){
                    $mimf['sticker']  = $request->ul_sticker;
                }

                if(Devices::where('name', $request->mimf_device_name)->where('status', 1)->exists()){
                    if($check_existing_control_no != 1){
                        $mimf['created_by']  = $request->mimf_created_by;
                        $mimf['created_at']  = date('Y-m-d H:i:s');
                        if($check_existing_po->isEmpty()){
                            if($request->create_edit == 'create'){
                                MimfV2::insert(
                                    $mimf
                                );
                            }else{
                                return response()->json(['result' => 2]);
                            }
                        }else{
                            return response()->json(['result' => 3]);
                        }
                    }else{
                        if($request->create_edit == 'create'){
                            return response()->json(['result' => 1]);
                        }else{
                            if($check_existing_po->isNotEmpty()){
                                $mimf['updated_by']  = $request->mimf_created_by;
                                $mimf['updated_at']  = date('Y-m-d H:i:s');
                                if($check_existing_po[0]->id == $request->mimf_id){
                                    MimfV2::where('id', $request->mimf_id)
                                    ->update(
                                        $mimf
                                    );
                                }else{
                                    return response()->json(['result' => 3]);
                                }
                            }else{
                                return response()->json(['result' => 2]);
                            }
                        }
                    }
                }else{
                    return response()->json(['result' => 0]);
                }

                DB::commit();
                return response()->json(['hasError' => 0]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['hasError' => 1, 'exceptionError' => $e->getMessage()]);
            }
        }
    }

    public function getMimfByIdV2(Request $request){
        date_default_timezone_set('Asia/Manila');

        $get_mimf_to_edit = MimfV2::where('id', $request->mimfID)->get();
        return response()->json(['getMimfToEdit'  => $get_mimf_to_edit]);
    }

    public function viewMimfPpsRequest(Request $request){
        date_default_timezone_set('Asia/Manila');

        $get_mimf_pps_requests = MimfV2PpsRequest::with([
            'rapid_pps_request_info'
        ])
        ->where('logdel', 0)
        ->where('mimf_id', $request->mimfID)
        ->orderBy('created_at', 'DESC')
        ->get();

        //! Uncomment to correct the balance and uncomment the "pageLength": 100, in mimf_v2_blade.php
        //! Kung ang the data is greater than 100, bahala ka na moshing ^_^
        $materials_data = [];
        if($get_mimf_pps_requests->isNotEmpty()){
            foreach ($get_mimf_pps_requests as $request) {
                if ($request->material_code) {
                    if (!isset($materials_data[$request->material_code])) {
                        $materials_data[$request->material_code] = ['virgin_material' => 0, 'needed_kgs' => 0];
                    }
            
                    if ($request->virgin_material !== null) {
                        $materials_data[$request->material_code]['virgin_material'] += $request->virgin_material;
                    } else {
                        $materials_data[$request->material_code]['needed_kgs'] += $request->needed_kgs;
                    }
                }
            }
            $flattened_result = [];
            foreach ($materials_data as $material_code => $data) {
                $flattened_result[] = [
                    'material_code' => $material_code,
                    'virgin_material' => $data['virgin_material'],
                    'needed_kgs' => $data['needed_kgs'],
                    'total' => $data['virgin_material'] + $data['needed_kgs']
                ];
            }
            $test =  MimfV2PpsRequestAllowedQuantity::where('mimf_id', $get_mimf_pps_requests[0]->mimf_id)->get(); 
            for ($i=0; $i < count($flattened_result); $i++) { 
                for ($ii=0; $ii < count($test); $ii++) {
                    if($flattened_result[$i]['material_code'] == $test[$ii]->pps_whse_partnumber){
                        $sum = $test[$ii]->allowed_quantity - $flattened_result[$i]['total'];
                        MimfV2PpsRequestAllowedQuantity::where('mimf_id', $test[0]->mimf_id)->where('pps_whse_partnumber', $test[$ii]->pps_whse_partnumber)
                            ->update([
                                'updated_At'    => date('Y-m-d H:i:s'),
                                'balance'       => $sum
                            ]);
                    }
                }
            }
        }
        //! Uncomment to correct the balance and uncomment the "pageLength": 100, in mimf_v2_blade.php
                
        return DataTables::of($get_mimf_pps_requests)
        ->addColumn('action', function($get_mimf_pps_request) use($request){
            if($get_mimf_pps_request->rapid_pps_request_info != null){
                $pps_request_id = $get_mimf_pps_request->rapid_pps_request_info->pkid;
            }else{
                $pps_request_id = '';
            }
            $result = '<center>';
            $result .= '
                <button class="btn btn-dark btn-sm text-center
                    actionEditMimfPpsRequest"
                    mimf_pps_request-id="'. $get_mimf_pps_request->id .'"
                    mimf_pps_request-material_type="'. $get_mimf_pps_request->material_type .'"
                    rapid_pps_request-id="'. $pps_request_id .'"
                    data-bs-toggle="modal"
                    data-bs-target="#modalMimfPpsRequest"
                    data-bs-keyboard="false" title="Edit PPS Request">
                    <i class="nav-icon fa fa-edit"></i>
                </button>';
            $result .= '</center>';
            return $result;
        })

        ->addColumn('pps_control_no', function($get_mimf_pps_request) use($request){
            $result = '<center>';
                if($get_mimf_pps_request->rapid_pps_request_info != null){
                    $result .= $get_mimf_pps_request->rapid_pps_request_info->control_number;
                }else{
                    $result .= '<span class="badge text-dark"> No Data <br> in Rapid <br> PPS Request </span>';
                    // $result .= '<span class="badge badge-pill badge-success"> No Data in Rapid PPS Request </span><br>';

                }
            $result .= '</center>';
            return $result;
        })


        ->rawColumns([
            'action',
            'pps_control_no'
        ])
        ->make(true);
    }

    public function getPpdMaterialType(Request $request){

        $get_device = Devices::with(
            'material_process.material_details.stamping_pps_warehouse_info'
        )
        ->where('name', $request->getMimfDeviceName)
        ->get();

        for ($i=0; $i < count($get_device[0]->material_process); $i++) {
            return response()->json(['getDeviceName'  => $get_device[0]->material_process[$i]]);
        }
    }

    public function getPpsWarehouseInventory(Request $request){
        $get_inventory = TblWarehouse::with(['pps_warehouse_transaction_info'])
        ->where('MaterialType', $request->ppsWarehouseInventory)
        ->whereIn('Factory', [0,1])
        ->get();

        $total_balanace = '';

        if($get_inventory->isNotEmpty()){
            $in = 0;
            $out = 0;
            if($get_inventory[0]->pps_warehouse_transaction_info->isNotEmpty()){
                $total_balanace = 0;
                $boh = $get_inventory[0]->pps_warehouse_transaction_info[0]->Boh;
                for ($i=0; $i < count($get_inventory[0]->pps_warehouse_transaction_info); $i++) {
                    $in += $get_inventory[0]->pps_warehouse_transaction_info[$i]->In;
                    $out += $get_inventory[0]->pps_warehouse_transaction_info[$i]->Out;
                }

                $total_balanace = number_format($boh+$in-$out, 2, '.', '');
            }

            return response()->json(['getInventory'  => $get_inventory, 'totalBalanace' => $total_balanace]);
        }else{
            return response()->json(['result'  => '0']);
        }
    }

    public function getPpsRequestPartialQuantity(Request $request){
            if($request->getMoldingProductCategory == 1){
                $calcualate_dieset = TblDieset::with(['ppd_matrix_info'])->where('R3Code', $request->getMimfMatrixItemCode)->get();
                if($calcualate_dieset[0]->ShotWgt == 0){
                    return response()->json(['shotWgtAlert' => 1]);
                }else if($calcualate_dieset[0]->NoOfCav == 0){
                    return response()->json(['noOfCavAlert' => 1]);
                }
                else{
                    $calculate = $request->getPartialQuantity*$calcualate_dieset[0]->ShotWgt/$calcualate_dieset[0]->NoOfCav/1000;
                    return response()->json(['calculate' => $calculate, 'calcualateDieset' => $calcualate_dieset]);
                }
            }else{
                $get_device_code = Devices::where('code', $request->getMimfMatrixItemCode)->where('status', '1')->get();
                return response()->json(['getDeviceCode' => $get_device_code]);
            }
    }

    public function checkRequestQtyForIssuance(Request $request){
        $allowed_quantity = MimfV2PpsRequestAllowedQuantity::where('mimf_id', $request->getMimfId)->where('pps_whse_partnumber', $request->getPartnumber)->get();
        $request_id = MimfV2PpsRequest::where('id', $request->mimfRequestId)->where('logdel', 0)->get();
        // return $allowed_quantity;
        return response()->json(['allowedQuantity' => $allowed_quantity, 'requestId' =>  $request_id]);
    }

    public function createUpdateMimfPpsRequest(Request $request){
        date_default_timezone_set('Asia/Manila');
        $data = $request->all();
    
        $get_control_no = PPSRequest::orderBy('pkid', 'DESC')
        ->where('deleted', 0)
        ->first();

        $get_ids =  TblDieset::with(['ppd_matrix_info'])->where('R3Code', $request->get_device_code)->get();

        $get_itemlist_id = PPSItemList::where('partcode', $request->mimf_material_code)
        ->where('partname', $request->mimf_material_type)
        ->whereIn('Factory', [0,1])
        ->first();

        if($request->molding_product_category == 1){
            $request_qty = $request->mimf_virgin_material;
        }else{
            $request_qty = $request->mimf_needed_kgs;
        }

        if($request->molding_product_category == 1){
            $multiplier = null;
            if($get_ids->isNotEmpty()){
                $dieset_id = $get_ids[0]->id;
            }else{
                return response()->json(['result' => 0]);
            }
        }else{
            $dieset_id = null;
            $multiplier = $request->multiplier;
        }

        if($get_itemlist_id != null){
            $pps_item_list_id = $get_itemlist_id->pkid_itemlist;
            $pps_item_list_matls_cat = $get_itemlist_id->matls_cat;
        }else{
            return response()->json(['result' => 1]);
        }

        $validator = Validator::make($data, [
            'mimf_needed_kgs'               => 'required',
            'pps_whse_id'                   => 'required',
            'mimf_material_code'            => 'required',
            'mimf_material_type'            => 'required',
            'mimf_quantity_from_inventory'  => 'required',
            'date_mimf_prodn'               => 'required',
            'mimf_delivery'                 => 'required',
            'mimf_remark'                   => 'required',
        ]);

        $explode_pps_request_control_no = explode("-",  $get_control_no->control_number);
        $control_no_format = "PPS-".NOW()->format('ym')."-";

        if(explode('-',$get_control_no->control_number)[1] != NOW()->format('ym')){
            $pps_request_new_control_no = $control_no_format.'001';
        }else{
            $string_pad = str_pad($explode_pps_request_control_no[2]+1,3,"0",STR_PAD_LEFT);
            $pps_request_new_control_no = $control_no_format.$string_pad;
        }

        if ($validator->fails()) {
            return response()->json(['validationHasError' => 1, 'error' => $validator->messages()]);
        }else{
            DB::beginTransaction();
            try {
                if($request->molding_product_category == 1){
                    $virgin_material = $request->mimf_virgin_material;
                    $recycled = $request->mimf_recycled;
                }else{
                    $virgin_material = null;
                    $recycled = null;
                }
                $mimf_pps_request = [
                    'mimf_id'               => $request->get_mimf_id,
                    'pps_whse_id'           => $request->pps_whse_id,
                    'pps_dieset_id'         => $dieset_id,
                    'product_category'      => $request->molding_product_category,
                    'material_code'         => $request->mimf_material_code,
                    'material_type'         => $request->mimf_material_type,
                    'qty_invt'              => $request->mimf_quantity_from_inventory,
                    'request_qty'           => $request->request_quantity,
                    'multiplier'            => $multiplier,
                    'needed_kgs'            => $request->mimf_needed_kgs,
                    'virgin_material'       => $virgin_material,
                    'recycled'              => $recycled,
                    'prodn'                 => $request->date_mimf_prodn,
                    'delivery'              => $request->mimf_delivery,
                    'remarks'               => $request->mimf_remark,
                ];

                $mimf_pps_request_allowed_qty = [
                    'mimf_id'               => $request->get_mimf_id,
                    'pps_whse_partnumber'   => $request->mimf_material_code,
                    'allowed_quantity'      => $request->mimf_molding_allowed_quantity,
                ];

                $pps_request = [
                    'created_on'        => date('Y-m-d H:i:s'),
                    'created_by'        => Auth::user()->username,
                    'updated_by'        => '',
                    'deleted'           => '0',
                    'fk_itemlist'       => $pps_item_list_id,
                    'matls_cat'         => $pps_item_list_matls_cat,
                    'qty'               => $request_qty,
                    'destination'       => $request->mimf_remark,
                    'fk_issuance'       => '0',
                    'r_remarks'         => '',
                    'i_remarks'         => '',
                    'cancelled'         => '0',
                    'acknowledgedby'    => '',
                    'acknowledged'      => '0',
                    'receive_date'      => '',
                ];

                $allowed_qty = MimfV2PpsRequestAllowedQuantity::where('mimf_id', $request->get_mimf_id)
                    ->where('pps_whse_partnumber', $request->mimf_material_code)
                    ->where('logdel', '0')
                    ->get();

                if($request->mimf_pps_request_id == ''){
                    // return $request->mimf_pps_request_id;
                    $mimf_pps_request['created_by']  = $request->created_by;
                    $mimf_pps_request['created_at']  = date('Y-m-d H:i:s');
                    
                    $mimf_request_id = MimfV2PpsRequest::insertGetId(
                        $mimf_pps_request
                    );

                    $pps_request['mimf_pps_request_id'] =  $mimf_request_id;
                    $pps_request['control_number']      =  $pps_request_new_control_no;
                    PPSRequest::insert(
                        $pps_request
                    );

                    if(count($allowed_qty) == 0){
                        // if($request->molding_product_category == 1){
                            //     $insert_balance = $request->mimf_molding_allowed_quantity - $request->mimf_virgin_material;
                            // }else{
                                //     $insert_balance = $request->mimf_molding_allowed_quantity - $request->mimf_needed_kgs;
                                // }
                                
                                $mimf_pps_request_allowed_qty['balance']    = $request->left_quantity;
                                $mimf_pps_request_allowed_qty['created_by'] = $request->created_by;
                                $mimf_pps_request_allowed_qty['created_at'] = date('Y-m-d H:i:s');
                                MimfV2PpsRequestAllowedQuantity::insert(
                                    $mimf_pps_request_allowed_qty
                                );
                    }else{
                        $mimf_pps_request_allowed_qty['balance']    = $request->left_quantity;
                        $mimf_pps_request_allowed_qty['updated_by'] = $request->created_by;
                        $mimf_pps_request_allowed_qty['updated_at'] = date('Y-m-d H:i:s');
                        MimfV2PpsRequestAllowedQuantity::where('id', $allowed_qty[0]->id)->update(
                            $mimf_pps_request_allowed_qty
                        );
                    }
                }else{

                    $mimf_pps_request['updated_by']  = $request->created_by;
                    $mimf_pps_request['updated_at']  = date('Y-m-d H:i:s');
                    MimfV2PpsRequest::where('id', $request->mimf_pps_request_id)->update(
                        $mimf_pps_request
                    );
                    PPSRequest::where('mimf_pps_request_id', $request->mimf_pps_request_id)->update(
                        $pps_request
                    );

                    $mimf_pps_request_allowed_qty['balance']    = $request->left_quantity;
                    $mimf_pps_request_allowed_qty['updated_by'] = $request->created_by;
                    $mimf_pps_request_allowed_qty['updated_at'] = date('Y-m-d H:i:s');
                    MimfV2PpsRequestAllowedQuantity::where('id', $allowed_qty[0]->id)->update(
                        $mimf_pps_request_allowed_qty
                    );
                }

                DB::commit();
                return response()->json(['hasError' => 0]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['hasError' => 1, 'exceptionError' => $e->getMessage()]);
            }
        }
    }

    public function getMimfPpsRequestById(Request $request){
        date_default_timezone_set('Asia/Manila');

        $get_mimf_pps_request_to_edit =  MimfV2PpsRequest::with([
            'rapid_pps_request_info'
        ])
        ->where('id', $request->mimfPpsRequestID)
        ->get();

        $get_mimf_pps_request_allowed_qty_to_edit = MimfV2PpsRequestAllowedQuantity::where('mimf_id', $get_mimf_pps_request_to_edit[0]->mimf_id)
        ->where('pps_whse_partnumber', $get_mimf_pps_request_to_edit[0]->material_code)
        ->where('logdel', '0')
        ->get();

        return response()->json(['getMimfPpsRequestToEdit'  => $get_mimf_pps_request_to_edit, 'getMimfPpsRequestAllowedQtyToEdit' => $get_mimf_pps_request_allowed_qty_to_edit]);
    }

    public function import(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        $rapidx_user_name   = $_SESSION['rapidx_name'];
        $po_collections     = Excel::toCollection(new Import, request()->file('import_po'));
        $variable           = '';

        for($col_start = 0; $col_start < count($po_collections[0]); $col_start++){
            if($po_collections[0][$col_start][0] == null){
                $variable = $po_collections[0][$col_start][1];
            }

            if($po_collections[0][$col_start][0] != null){
                $check_existing_po = MimfV2::with('mimf_request_details')->where('pmi_po_no', $po_collections[0][$col_start][0])
                ->where('logdel', 0)
                ->get();

                $get_last_control_no = MimfV2::orderBy('id', 'DESC')->where('logdel', 0)->first();
                $control_no_format = "MIMF-".NOW()->format('ym')."-";

                if ($get_last_control_no == null){
                    $new_control_no = $control_no_format.'001';
                }elseif(explode('-',$get_last_control_no->control_no)[1] != NOW()->format('ym')){
                    $new_control_no = $control_no_format.'001';
                }else{
                    $explode_control_no = explode("-",  $get_last_control_no->control_no);
                    $string_pad = str_pad($explode_control_no[2]+1,3,"0",STR_PAD_LEFT);
                    $new_control_no = $control_no_format.$string_pad;
                }

                $get_po_received_pmi_po = TblPoReceived::with([
                    'matrix_info',
                ])
                ->where('OrderNo',$po_collections[0][$col_start][0])
                ->where('logdel', 0)
                ->get();

                if(count($get_po_received_pmi_po) > 0){
                    if(count($check_existing_po) == 0){
                        $mimf_po_array = [
                            'pps_po_rcvd_id'        => $get_po_received_pmi_po[0]->id,
                            'control_no'            => $new_control_no,
                            'date_issuance'         => date('Y-m-d'),
                            'pmi_po_no'             => $po_collections[0][$col_start][0],
                            'prodn_qty'             => $get_po_received_pmi_po[0]->OrderQty,
                            'device_code'           => $get_po_received_pmi_po[0]->ItemCode,
                            'device_name'           => $get_po_received_pmi_po[0]->ItemName,
                            'created_by'            => $rapidx_user_name,
                            'created_at'            => NOW()
                        ];

                        MimfV2::insert(
                            $mimf_po_array
                        );
                    }
                }
            }
        }

        return response()->json(['result' => 1]);
    }
}
