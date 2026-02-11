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

use App\Models\Mimf;
use App\Models\User;
use App\Models\Devices;
use App\Models\TblDieset;
use App\Models\PPSRequest;
use App\Models\PPSItemList;
use App\Models\TblWarehouse;
use App\Models\TblPoReceived;
use App\Models\MimfPpsRequest;

class MimfController extends Controller
{
    public function viewMimf(Request $request){
        date_default_timezone_set('Asia/Manila');
        
        $get_mimfs = Mimf::with([
            'pps_po_received_info.po_received_to_pps_whse_info',
        ])
        ->where('logdel', 0)
        ->orderBy('control_no', 'DESC')
        ->get();

        return DataTables::of($get_mimfs)
        ->addColumn('action', function($get_mimf) use($request){
            $result = '<center>';
            if($get_mimf->pps_po_received_info->POBalance != 0){
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
            }else{
                $result .= '<span class="badge badge-pill badge-success"> COMPLETED! </span>';
            }

            $result .= '
                <button class="btn btn-warning btn-sm text-center 
                    actionMimfPpsRequest" 
                    mimf-id="'. $get_mimf->id .'"
                    device_name-id="'. $get_mimf->device_name .'"
                    data-bs-toggle="modal" 
                    data-bs-target="#modalMimf"
                    data-bs-keyboard="false" title="PPS Request">
                    <i class="nav-icon fa fa-history"></i>
                </button>';
            $result .= '</center>';
            return $result;
        })

        ->addColumn('yec_po_no', function($get_mimf){
            $result = $get_mimf->pps_po_received_info->ProductPONo;
            return $result;
        })

        ->addColumn('po_balance', function($get_mimf){
            $result = '<center>';
            $result .= '<span class="badge badge-pill badge-warning"> <strong> '.$get_mimf->pps_po_received_info->POBalance.' </strong> </span>';;
            $result .= '</center>';
            return $result;
        })
        ->addColumn('yec_po_no', function($get_mimf){
            $result = $get_mimf->pps_po_received_info->ProductPONo;
            return $result;
        })
        
        ->rawColumns([
            'action',
            'yec_po_no',
            'po_balance'
        ])
        ->make(true);
    }

    public function getControlNo(Request $request){
        date_default_timezone_set('Asia/Manila');

        $get_last_control_no = Mimf::orderBy('id', 'DESC')->where('logdel', 0)->first();
        $control_no_format = "MIMF-".NOW()->format('ym')."-";
        if ($get_last_control_no == null){
            $new_control_no = $control_no_format.'001';
        }elseif(explode('-',$get_last_control_no->control_no)[2] != NOW()->format('ym')){
            $new_control_no = $control_no_format.'001';
        }else{
            $explode_control_no = explode("-",  $get_last_control_no->control_no);
            $string_pad = str_pad($explode_control_no[3]+1,3,"0",STR_PAD_LEFT);
            $new_control_no = $control_no_format.$string_pad;
        }
        return response()->json(['newControlNo' => $new_control_no]);
    }

    public function getPmiPoFromPoReceived(Request $request){
        date_default_timezone_set('Asia/Manila');
        
        $get_po_received_pmi_po = TblPoReceived::with([
            'matrix_info',
        ])
        ->where('OrderNo',$request->getValue)
        ->where('logdel', 0)
        ->get();
        
        return response()->json(['getPoReceivedPmiPo' => $get_po_received_pmi_po]);
    }

    public function updateMimf(Request $request){
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
            // DB::beginTransaction();
            // try {
                $check_existing_po = Mimf::where('pmi_po_no', $request->mimf_pmi_po_no)
                    ->where('logdel', 0)
                    ->exists();

                $check_existing_control_no = Mimf::where('id', $request->mimf_id)
                    ->where('control_no', $request->mimf_control_no)
                    ->where('pmi_po_no', $request->mimf_pmi_po_no)
                    ->where('logdel', 0)
                    ->exists();

                $mimf = [
                    'pps_po_rcvd_id'        => $request->pps_po_rcvd_id,
                    'control_no'            => $request->mimf_control_no,
                    'date_issuance'         => $request->mimf_date_issuance,
                    'pmi_po_no'             => $request->mimf_pmi_po_no,
                    'prodn_qty'             => $request->mimf_prodn_quantity,
                    'device_code'           => $request->mimf_device_code,
                    'device_name'           => $request->mimf_device_name,
                ];

                if(Devices::where('name', $request->mimf_device_name)->where('status', 1)->exists()){
                    if($check_existing_control_no != 1){
                        $mimf['created_by']  = $request->mimf_created_by;
                        $mimf['created_at']  = date('Y-m-d H:i:s');
                        if($check_existing_po != 1){
                            if($request->create_edit == 'create'){
                                Mimf::insert(
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
                            $mimf['updated_by']  = $request->mimf_created_by;
                            $mimf['updated_at']  = date('Y-m-d H:i:s');
                            if($check_existing_po == 1 ){
                                Mimf::where('id', $request->mimf_id)
                                ->update(
                                    $mimf
                                );
                            }else{
                                return response()->json(['result' => 2]);
                            }
                        }
                    }
                }else{
                    return response()->json(['result' => 0]);
                }

                // DB::commit();
                return response()->json(['hasError' => 0]);
            // } catch (\Exception $e) {
            //     DB::rollback();
            //     return response()->json(['hasError' => 1, 'exceptionError' => $e->getMessage()]);
            // }
        }
    }

    public function getMimfById(Request $request){
        date_default_timezone_set('Asia/Manila');
        
        $get_mimf_to_edit = Mimf::where('id', $request->mimfID)->get();
        return response()->json(['getMimfToEdit'  => $get_mimf_to_edit]);
    }

    public function viewMimfPpsRequest(Request $request){
        date_default_timezone_set('Asia/Manila');
        
        $get_mimf_pps_requests = MimfPpsRequest::with([
            'rapid_pps_request_info'
        ])
        ->where('logdel', 0)
        ->where('mimf_id', $request->mimfID)
        ->orderBy('created_at', 'DESC')
        ->get();

        return DataTables::of($get_mimf_pps_requests)
        ->addColumn('action', function($get_mimf_pps_request) use($request){
            $result = '<center>';
            $result .= '
                <button class="btn btn-dark btn-sm text-center 
                    actionEditMimfPpsRequest"
                    mimf_pps_request-id="'. $get_mimf_pps_request->id .'" 
                    rapid_pps_request-id="'. $get_mimf_pps_request->rapid_pps_request_info->pkid .'"  
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
            $result .= $get_mimf_pps_request->rapid_pps_request_info->control_number;
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
            if($get_device[0]->material_process[$i]->process == '2'){
                return response()->json(['getDeviceName'  => $get_device[0]->material_process[$i]]);
            }
        }
    }

    public function getPpsWarehouseInventory(Request $request){
        $get_inventory = TblWarehouse::with(['pps_warehouse_transaction_info'])->where('MaterialType', $request->ppsWarehouseInventory)
        ->get();

        if($get_inventory->isNotEmpty()){
            $in = 0;
            $out = 0;
            $total_balanace = 0;
            if($get_inventory[0]->pps_warehouse_transaction_info != null){
                for ($i=0; $i < count($get_inventory[0]->pps_warehouse_transaction_info); $i++) { 
                    $in += $get_inventory[0]->pps_warehouse_transaction_info[$i]->In;
                    $out += $get_inventory[0]->pps_warehouse_transaction_info[$i]->Out;
                }
                $total_balanace = number_format($in-$out, 2, '.', '');
            }
            return response()->json(['getInventory'  => $get_inventory, 'totalBalanace' => $total_balanace]);
        }else{
            return response()->json(['result'  => '0']);
        }
    }

    public function getPpsRequestPartialQuantity(Request $request){
        $calcualate_dieset = TblDieset::with(['ppd_matrix_info'])->where('R3Code', $request->getMimfMatrixItemCode)->get();
        // return $calcualate_dieset;
        $calculate = $request->getPartialQuantity*$calcualate_dieset[0]->ShotWgt/$calcualate_dieset[0]->NoOfCav/1000;
        
        return response()->json(['calculate' => $calculate, 'calcualateDieset' => $calcualate_dieset]);
    }

    public function checkRequestQtyForIssuance(Request $request){
        $check_request_qty = Mimf::with([
            'mimf_request_details'
            ])
            // ->where('pmi_po_no',$request->getMimfPoNo)
            ->where('logdel', 0)
            ->get();
        $total_request_qty = 0;
        if($check_request_qty != ''){
            for ($i=0; $i < count($check_request_qty[0]->mimf_request_details); $i++) { 
                $total_request_qty += $check_request_qty[0]->mimf_request_details[$i]->request_qty;
            }
        }

        return response()->json(['checkRequestQty' => $check_request_qty,'checkTotalRequestQty'  => $total_request_qty]);
    }

    public function createUpdateMimfPpsRequest(Request $request){
        date_default_timezone_set('Asia/Manila');
        $data = $request->all();

        $get_control_no = PPSRequest::orderBy('pkid', 'DESC')
        ->where('deleted', 0)
        ->first();

        $get_ids =  TblDieset::with(['ppd_matrix_info'])->where('R3Code', $request->get_mimf_device_name)->get();

        $get_itemlist_id = PPSItemList::where('partcode', $request->mimf_material_code)
        ->where('partname', $request->mimf_material_type)
        ->where('Factory', '!=', 3)
        ->first();

        if($get_ids->isNotEmpty()){
            $dieset_id = $get_ids[0]->id; 
        }else{
            return response()->json(['result' => 0]);
        }

        if($get_ids[0]->ppd_matrix_info != null){
            $mimf_matrix_id = $get_ids[0]->ppd_matrix_info->id; 
        }else{
            return response()->json(['result' => 1]);
        }

        if($get_itemlist_id != null){
            $pps_item_list_id = $get_itemlist_id->pkid_itemlist;
            $pps_item_list_matls_cat = $get_itemlist_id->matls_cat; 
        }else{
            return response()->json(['result' => 2]);
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
            // DB::beginTransaction();
            // try {

                $mimf_pps_request = [
                    'mimf_id'               => $request->mimf_id,
                    'pps_whse_id'           => $request->pps_whse_id,
                    'pps_dieset_id'         => $dieset_id,
                    'ppd_matrix_id'         => $mimf_matrix_id,
                    'material_code'         => $request->mimf_material_code,
                    'material_type'         => $request->mimf_material_type,
                    'qty_invt'              => $request->mimf_quantity_from_inventory,
                    'request_qty'           => $request->request_quantity,
                    'needed_kgs'            => $request->mimf_needed_kgs,
                    'virgin_material'       => $request->mimf_virgin_material,
                    'recycled'              => $request->mimf_recycled,
                    'prodn'                 => $request->date_mimf_prodn,
                    'delivery'              => $request->mimf_delivery,
                    'remarks'               => $request->mimf_remark,
                ];

                $pps_request = [
                    'created_on'        => date('Y-m-d H:i:s'),
                    'created_by'        => Auth::user()->username,
                    'updated_by'        => '',
                    'deleted'           => '0',
                    'fk_itemlist'       => $pps_item_list_id,
                    'matls_cat'         => $pps_item_list_matls_cat,
                    'qty'               => $request->mimf_virgin_material,
                    'destination'       => $request->mimf_remark,
                    'fk_issuance'       => '0',
                    'r_remarks'         => '',
                    'i_remarks'         => '',
                    'cancelled'         => '0',
                    'acknowledgedby'    => '',
                    'acknowledged'      => '0',
                    'receive_date'      => '',
                ];
                if($request->pps_request_id == ''){
                    $mimf_pps_request['created_by']  = $request->created_by;
                    $mimf_pps_request['created_at']  = date('Y-m-d H:i:s');
                    $mimf_pps_request_id = MimfPpsRequest::insertGetId(
                        $mimf_pps_request
                    );

                    $pps_request['mimf_pps_request_id'] =  $mimf_pps_request_id;
                    $pps_request['control_number']      =  $pps_request_new_control_no;
                    PPSRequest::insert(
                        $pps_request
                    );
                }else{
                    $mimf_pps_request['updated_by']  = $request->created_by;
                    $mimf_pps_request['updated_at']  = date('Y-m-d H:i:s');
                    MimfPpsRequest::where('id', $request->pps_request_id)->update(
                        $mimf_pps_request
                    );

                    PPSRequest::where('mimf_pps_request_id', $request->pps_request_id)->update(
                        $pps_request
                    );
                }

                // DB::commit();
                return response()->json(['hasError' => 0]);
            // } catch (\Exception $e) {
            //     DB::rollback();
            //     return response()->json(['hasError' => 1, 'exceptionError' => $e->getMessage()]);
            // }
        }
    }

    public function getMimfPpsRequestById(Request $request){
        date_default_timezone_set('Asia/Manila');
        
        $get_mimf_pps_request_to_edit =  MimfPpsRequest::with([
            'rapid_pps_request_info'
        ])->where('id', $request->mimfPpsRequestID)->get();
        return response()->json(['getMimfPpsRequestToEdit'  => $get_mimf_pps_request_to_edit]);
    }

}
