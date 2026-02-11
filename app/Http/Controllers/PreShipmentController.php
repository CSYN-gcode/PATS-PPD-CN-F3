<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DataTables;

use App\Models\RapidPreShipment;
use App\Models\RapidPreShipmentDetails;
use App\Models\ProductionRuncardStationMod;
use App\Models\QualificationDetail;
use App\Models\OqcInspectionDetail;
use App\Models\Devices;

use QrCode;

class PreShipmentController extends Controller
{
    public function getUsersByPos(Request $request){
        $users = DB::connection('mysql')->select('SELECT id, CONCAT(firstname, " ", lastname) AS full_name FROM users WHERE position IN ('.$request->position.') AND status = 1');
        return response()->json(['users' => $users]);
    }

    public function getPreShipmentDestination(Request $request){
        // $destinations = DB::table('tbl_Destination as destination')->select('*')->where('logdel', 0)->get();
        $destinations = DB::connection('mysql_rapid_pps')->select('SELECT * FROM tbl_Destination AS destination WHERE logdel = 0;');
        return response()->json(['result' => $destinations]);
    }

    public function getControlNumbers(Request $request){
        $all_control_no = DB::table('rapid_pre_shipments AS pre_shipment')
                                ->select('pre_shipment.control_no', 'pre_shipment.shipment_date')
                                ->when($request->control_number, function ($query) use ($request){
                                    return $query->where('pre_shipment.control_no', $request->control_number);
                                })
                                ->whereNull('pre_shipment.deleted_at')
                                ->get();

        return response()->json(['result' => $all_control_no]);
    }

    public function viewPreShipment(Request $request){
        $ProdRuncardData = DB::table('rapid_pre_shipments AS pre_shipment')
                                ->select('*')
                                ->when($request->control_no, function ($query) use ($request){
                                    return $query->where('pre_shipment.control_no', $request->control_no);
                                })
                                ->whereNull('pre_shipment.deleted_at')
                                ->get();

        return DataTables::of($ProdRuncardData)
        ->addColumn('action', function($row){
            $result = '';
            $result .= "<center>";

            if($row->status == 0 || $row->status == 1 || $row->status == 3){
                if($row->status == 0){//Pending
                    $btn_class = 'btn btn-primary';
                }else if($row->status == 1){//Submitted
                    $btn_class = 'btn btn-primary';
                }else if($row->status == 3){//Done
                    $btn_class = 'btn btn-success';
                }

                $result .= "<button class='".$btn_class." btn-sm mr-1' pre_shipment-id='".$row->id."' id='btnPrintPreShipment'>
                                <i class='fa-solid fa-print' disabled></i>
                            </button>";
            }

            if($row->status == 0 || $row->status == 1){
                $result .= "<button class='btn btn-primary btn-sm mr-1 btnUpdatePreShipment' pre_shipment-id='$row->id'>
                            <i class='fa-solid fa-pen-to-square'></i>
                        </button>";
            }

            if($row->status == 2 || $row->status == 3){
                $result .= "<button class='btn btn-info btn-sm mr-1 btnViewProdRuncardData' pre_shipment-id='$row->id'>
                                <i class='fa-solid fa-eye' title='View IPQC Inspection'></i>
                            </button>";
            }

            if($row->status == 1){
                $result .= "<button class='btn btn-success btn-sm mr-1' pre_shipment-id='".$row->id."' prod_runcard-status='".$row->status."' id='btnSubmitRuncardData'>
                                <i class='fa-solid fa-circle-check'></i>
                            </button>";
            }

            $result .= "</center>";
            return $result;
        })
        ->addColumn('status', function ($row){
            $result = "";

            switch($row->status){
                case 0: //Pending
                    $result .= '<center><span class="badge badge-pill badge-info">For PO Input</span></center>';
                    break;
                case 1: //Mass Prod
                    $result .= '<center><span class="badge badge-pill badge-primary">For Submission</span></center>';
                    break;
                case 2: //Resetup
                    $result .= '<center><span class="badge badge-pill badge-warning">For Re-setup</span></center>';
                    break;
                case 3: //Done
                    $result .= '<center><span class="badge badge-pill badge-success">Done</span></center>';
                    break;
            }
            return $result;
        })
        ->rawColumns(['action','status'])
        ->make(true);
    }

    public function searchPoDeliveryUpdate(Request $request){
        $po_details = DB::table('delivery_updates AS DeliveryUpdate')
                        ->select('*')
                        ->leftJoin('production_runcards AS ProdRuncard', 'DeliveryUpdate.runcard_id', '=', 'ProdRuncard.id')
                        ->when($request->po_number, function ($query) use ($request){
                            return $query->where('DeliveryUpdate.po_no', $request->po_number);
                        })
                        ->where('DeliveryUpdate.logdel', 0)
                        ->get();

        if(empty($po_details)){
            $result = 0;
            $po_details = '';
        }else{
            $result = 1;
            $po_details = $po_details[0];
        }

        return response()->json(['result' => $result, 'po_details' => $po_details]);
    }

    public function viewSearchPoResult(Request $request){
        if(!isset($request->pre_ship_details_id)){
            $po_details = DB::table('delivery_updates AS DeliveryUpdate')
                        ->select('*')
                        ->leftJoin('production_runcards AS ProdRuncard', 'DeliveryUpdate.runcard_id', '=', 'ProdRuncard.id')
                        ->where('DeliveryUpdate.po_no', $request->po_number)
                        ->where('DeliveryUpdate.logdel', 0)
                        ->get();
        }else{
            $po_details = DB::table('rapid_pre_shipment_details AS pre_shipmt_details')
                        ->select('*')
                        ->where('pre_shipmt_details.id', $request->pre_ship_details_id)
                        ->whereNull('pre_shipmt_details.deleted_at')
                        ->get();
        }
        return DataTables::of($po_details)
        ->addColumn('checkbox', function($row){
            $data = '';
            if($row->id){
                $data = $row->id;
            }

            $result = "";
            $result .= "<center>";
                $result .= "<input class='itemCheckbox' type='checkbox' data-checkbox-id='$row->id' style='width: 25px; height: 25px;  text-align: center;' id='checkBoxId' name='checkbox_id[]' value='".$data."'>";
            $result .= "</center>";
            return $result;
        })
        ->addColumn('row_master_carton_no', function($row){
            $data = '';
            if(isset($row->master_carton_no)){
                $data = $row->master_carton_no;
            }

            $result = "";
            $result .= "<center>";
                $result .= "<input class='master_carton_$row->id classToDisable' data-master_carton-id='$row->id' type='text' style='width: 100px; text-align: center;' id='masterCartonNoId' name='master_carton_no[]' value='".$data."'>";
            $result .= "</center>";
            return $result;
        })
        ->addColumn('item_no', function($row){
            $data = '';
            if(isset($row->item_no)){
                $data = $row->item_no;
            }

            $result = "";
            $result .= "<center>";
            // if($row->status == 1 ){
                $result .= "<input class='item_$row->id classToDisable' data-item_no-id='$row->id' type='text' style='width: 100px; text-align: center;' id='itemNoId' name='item_no[]' value='".$data."'>";
            // }
            $result .= "</center>";
            return $result;
        })
        ->addColumn('lot_numbers', function($row){
            $data = '';
            if($row->lot_no){
                $data = $row->lot_no;
            }

            $result = "";
            $result .= "<center>";
                $result .= "<input class='lot_no_$row->id lockReadOnly' data-lot_no-id='$row->id' type='text' style='width: 100px; text-align: center;' id='LotNoId' name='lot_no[]' value='".$data."' readonly>";
            $result .= "</center>";
            return $result;
        })
        ->addColumn('quantities', function($row){
            $data = '';
            if(isset($row->qty)){
                $data = $row->qty;
            }else{
                $data = $row->actual_so;
            }

            $result = "";
            $result .= "<center>";
                $result .= "<input class='quantity_$row->id lockReadOnly' data-quantity-id='$row->id' type='text' style='width: 100px; text-align: center;' id='QuantityId' name='quantity[]' value='".$data."' readonly>";
            $result .= "</center>";
            return $result;
        })
        ->addColumn('row_package_cat', function($row){
            $data = '';
            if(isset($row->package_category)){
                $data = $row->package_category;
            }

            $result = "";
            $result .= "<center>";
                $result .= "<input class='package_cat_$row->id classToDisable' data-package_cat-id='$row->id' type='text' style='width: 100px; text-align: center;' id='packageCatId' name='package_cat[]' value='".$data."'>";
            $result .= "</center>";
            return $result;
        })
        ->addColumn('row_package_qty', function($row){
            $data = '';
            if(isset($row->package_qty)){
                $data = $row->package_qty;
            }

            $result = "";
            $result .= "<center>";
                $result .= "<input class='package_qty_$row->id classToDisable' data-package_qty-id='$row->id' type='text' style='width: 100px; text-align: center;' id='packageQtyId' name='package_qty[]' value='".$data."'>";
            $result .= "</center>";
            return $result;
        })
        ->addColumn('row_remarks', function($row){
            $data = '';
            if(isset($row->remarks)){
                $data = $row->remarks;
            }

            $result = "";
            $result .= "<center>";
                $result .= "<input class='remarks_$row->id classToDisable' data-remarks-id='$row->id' type='text' style='width: 100px; text-align: center;' id='remarksId' name='remarks[]' value='".$data."'>";
            $result .= "</center>";
            return $result;
        })
        ->addColumn('remove_btn', function($row){
            $result = "";
            $result .= "<center>";
                $result .= "<button class='btn btn-md btn-danger buttonRemoveRow' type='button'><i class='fa fa-times'></i></button>";
            $result .= "</center>";
            return $result;
        })
        ->rawColumns(['checkbox', 'row_master_carton_no', 'lot_numbers', 'quantities', 'item_no', 'row_package_cat', 'row_package_qty', 'row_remarks', 'remove_btn'])
        ->make(true);
    }

    public function viewPreShipmentDetails(Request $request){
            $PreShipDetails = DB::connection('mysql')->select("SELECT preship_details.*,
                                    CONCAT(weighed_by_user.firstname, ' ', weighed_by_user.lastname) AS weighed_by_name,
                                    CONCAT(packed_by_user.firstname, ' ', packed_by_user.lastname) AS packed_by_name,
                                    CONCAT(checked_by_user.firstname, ' ', checked_by_user.lastname) AS checked_by_name
                                    FROM rapid_pre_shipment_details AS preship_details
                                    LEFT JOIN users AS weighed_by_user ON preship_details.weighed_by = weighed_by_user.id
                                    LEFT JOIN users AS packed_by_user ON preship_details.packed_by = packed_by_user.id
                                    LEFT JOIN users AS checked_by_user ON preship_details.checked_by = checked_by_user.id
                                    WHERE preship_details.preshipment_pkid = '$request->pre_shipment_id'
                                    ORDER BY preship_details.id ASC
            ");

            return DataTables::of($PreShipDetails)
            ->addColumn('action', function($detail) use ($request){
                $result = '';
                if($detail->status == 0 || $detail->status == 1){
                    $result .= "<center>
                                    <button class='btn btn-primary btn-sm mr-1 btnUpdatePreShipmentDetails' pre_shipment-id='$request->pre_shipment_id' pre_shipment_details-id='$detail->id'><i class='fa-solid fa-pen-to-square'></i></button>
                                </center>";
                }

                if($detail->status == 2 || $detail->status == 3){
                $result .= "<center>
                                <button class='btn btn-primary btn-sm mr-1 btnViewProdRuncardStationData' pre_shipment-id='$request->pre_shipment_id' pre_shipment_details-id='$detail->id'><i class='fa-solid fa-eye'></i></button>
                            </center>";
                }
                return $result;
            })
            ->addColumn('status', function($station){
                $result = '';
                if($station->status == 0 || $station->status == 1 || $station->status == 2){
                    $result .= "<center>
                                    <span class='badge rounded-pill bg-info'>On-going</span>
                                </center>";
                }

                if($station->status == 3){
                    $result .= "<center>
                                    <span class='badge rounded-pill bg-info'>Done</span>
                                </center>";
                }
                return $result;
            })
            ->rawColumns(['action','status'])
            ->make(true);
    }

    public function addPreShipmentData(Request $request){
        date_default_timezone_set('Asia/Manila');
        $data = $request->all();

        $validate_array = ['date' => 'required', 'destination' => 'required', 'category' => 'required', 'station' => 'required', 'shipment_date' => 'required'];

        $validator = Validator::make($data, $validate_array);

        if ($validator->fails()) {
            return response()->json(['validation' => 'hasError', 'error' => $validator->messages()]);
        }else{
            try{
                if(!isset($request->pre_shipment_id)){
                    //Control Number Generation
                    $lastest_id = RapidPreShipment::whereNull('deleted_at')->latest('id')->first();
                    if($lastest_id == null){
                        $counter = 1;
                    }else{
                        $last_control_no = $lastest_id->control_no;
                        $last_control_no = explode("-", $last_control_no);
                        // return $last_control_no;
                        if($last_control_no[0] == date('ym')){
                            $last_control_no = $last_control_no[1];
                            $counter = $last_control_no;
                            $counter++;
                        }else{
                            $counter = 1;
                        }
                    }

                    if(strlen($counter) == 1){
                        $digit_prefix = '00';
                    }else if(strlen($counter) == 2){
                        $digit_prefix = '0';
                    }

                    $control_number = date('ym').'-'.$digit_prefix.$counter;
                    // return $control_number;

                    RapidPreShipment::insert([
                                    'date'            => $request->date,
                                    'control_no'      => $control_number,
                                    'sales_cutoff'    => $request->sales_cutoff,
                                    'destination'     => $request->destination,
                                    'category'        => $request->category,
                                    'station'         => $request->station,
                                    'shipment_date'   => $request->shipment_date,
                                    'created_by'      => Auth::user()->id,
                                    'last_updated_by' => Auth::user()->id,
                                    'created_at'      => date('Y-m-d H:i:s'),
                                    'updated_at'      => date('Y-m-d H:i:s')
                    ]);

                    DB::commit();
                    return response()->json(['result' => 1]);
                }else{
                    RapidPreShipment::where('id', $request->pre_shipment_id)
                            ->update([
                                    'date'            => $request->date,
                                    // 'control_no'      => $request->control_no,
                                    'sales_cutoff'    => $request->sales_cutoff,
                                    'destination'     => $request->destination,
                                    'category'        => $request->category,
                                    'station'         => $request->station,
                                    'shipment_date'   => $request->shipment_date,
                                    'created_by'      => Auth::user()->id,
                                    'last_updated_by' => Auth::user()->id,
                                    'created_at'      => date('Y-m-d H:i:s'),
                                    'updated_at'      => date('Y-m-d H:i:s')
                            ]);

                    DB::commit();
                    return response()->json(['result' => 1]);
                }
            }catch (\Throwable $th){
                return $th;
            }
        }
    }

    public function getPreShipmentData(Request $request){
        $pre_shipment_data = DB::table('rapid_pre_shipments AS pre_shipmt')->select('pre_shipmt.*')
                            ->leftJoin('rapid_pre_shipment_details AS pre_shipmt_details', 'pre_shipmt.id', '=', 'pre_shipmt_details.preshipment_pkid')
                            ->leftJoin('users AS weighed_by_user', 'pre_shipmt_details.weighed_by', '=', 'weighed_by_user.id')
                            ->leftJoin('users AS packed_by_user', 'pre_shipmt_details.packed_by', '=', 'packed_by_user.id')
                            ->leftJoin('users AS checked_by_user', 'pre_shipmt_details.checked_by', '=', 'checked_by_user.id')
                            ->where('pre_shipmt.id', '=', $request->pre_shipment_id)
                            ->whereNull('pre_shipmt.deleted_at')
                            ->when($request->pre_shipment_details_id, function ($query) use ($request){
                                return $query->addSelect(
                                                        // DB::raw("CONCAT( weighed_by_user.firstname , ' ', weighed_by_user.lastname ) AS weighed_by_name"),
                                                        // DB::raw("CONCAT( packed_by_user.firstname , ' ', packed_by_user.lastname ) AS packed_by_name"),
                                                        // DB::raw("CONCAT( checked_by_user.firstname , ' ', checked_by_user.lastname ) AS checked_by_name"),
                                                        'pre_shipmt_details.id AS preshipdetails_id',
                                                        'pre_shipmt_details.master_carton_no AS master_carton_no',
                                                        'pre_shipmt_details.item_no AS item_no',
                                                        'pre_shipmt_details.po_no AS po_no',
                                                        'pre_shipmt_details.parts_code AS parts_code',
                                                        'pre_shipmt_details.device_name AS device_name',
                                                        'pre_shipmt_details.lot_no AS lot_no',
                                                        'pre_shipmt_details.qty AS qty',
                                                        'pre_shipmt_details.package_category AS package_category',
                                                        'pre_shipmt_details.package_qty AS package_qty',
                                                        'pre_shipmt_details.weighed_by AS weighed_by',
                                                        'pre_shipmt_details.packed_by AS packed_by',
                                                        'pre_shipmt_details.checked_by AS checked_by',
                                                        'pre_shipmt_details.remarks AS remarks',
                                                        'pre_shipmt_details.status AS preshipdetails_status',
                                                        'pre_shipmt_details.deleted_at AS preshipdetails_deleted_at')
                                            ->where('pre_shipmt_details.id', $request->pre_shipment_details_id)
                                            ->whereNull('pre_shipmt_details.deleted_at');
                            })->first();
        return response()->json(['pre_shipment_data' => $pre_shipment_data]);
    }

    public function getPreShipmentForPrint(Request $request){
        $preship_data_to_print = DB::table('rapid_pre_shipments AS pre_shipmt')
                            ->select('pre_shipmt.id AS preship_id',
                                        'pre_shipmt.control_no AS preship_control_no',
                                        'pre_shipmt.destination AS preship_destination',
                                        'pre_shipmt.shipment_date AS preship_shipment_date',
                                        'packed_by_user.firstname',
                                        DB::raw("CONCAT( packed_by_user.firstname , ' ', packed_by_user.lastname ) AS packed_by_name"),
                                        'pre_shipmt_details.*')
                            ->leftJoin('rapid_pre_shipment_details AS pre_shipmt_details', 'pre_shipmt.id', '=', 'pre_shipmt_details.preshipment_pkid')
                            ->leftJoin('users AS weighed_by_user', 'pre_shipmt_details.weighed_by', '=', 'weighed_by_user.id')
                            ->leftJoin('users AS packed_by_user', 'pre_shipmt_details.packed_by', '=', 'packed_by_user.id')
                            ->leftJoin('users AS checked_by_user', 'pre_shipmt_details.checked_by', '=', 'checked_by_user.id')
                            ->where('pre_shipmt.id', '=', $request->pre_shipment_id)
                            ->whereNull('pre_shipmt.deleted_at')
                            // ->when($request->pre_shipment_details_id, function ($query) use ($request){
                            //     return $query->addSelect(
                            //                             // DB::raw("CONCAT( weighed_by_user.firstname , ' ', weighed_by_user.lastname ) AS weighed_by_name"),
                            //                             // DB::raw("CONCAT( packed_by_user.firstname , ' ', packed_by_user.lastname ) AS packed_by_name"),
                            //                             // DB::raw("CONCAT( checked_by_user.firstname , ' ', checked_by_user.lastname ) AS checked_by_name"),
                            //                             'pre_shipmt_details.id AS preshipdetails_id',
                            //                             'pre_shipmt_details.master_carton_no AS master_carton_no',
                            //                             'pre_shipmt_details.item_no AS item_no',
                            //                             'pre_shipmt_details.po_no AS po_no',
                            //                             'pre_shipmt_details.parts_code AS parts_code',
                            //                             'pre_shipmt_details.device_name AS device_name',
                            //                             'pre_shipmt_details.lot_no AS lot_no',
                            //                             'pre_shipmt_details.qty AS qty',
                            //                             'pre_shipmt_details.package_category AS package_category',
                            //                             'pre_shipmt_details.package_qty AS package_qty',
                            //                             'pre_shipmt_details.weighed_by AS weighed_by',
                            //                             'pre_shipmt_details.packed_by AS packed_by',
                            //                             'pre_shipmt_details.checked_by AS checked_by',
                            //                             'pre_shipmt_details.remarks AS remarks',
                            //                             'pre_shipmt_details.status AS preshipdetails_status',
                            //                             'pre_shipmt_details.deleted_at AS preshipdetails_deleted_at')
                            //                 ->where('pre_shipmt_details.id', $request->pre_shipment_details_id)
                            //                 ->whereNull('pre_shipmt_details.deleted_at');
                            // })
                            ->get();

        return response()->json(['print_preship_details' => $preship_data_to_print]);
    }

    public function getPreShipDataForPreview(Request $request){
        // return $request->all();
        #FIRST HALF OF DEVICE NAME
        $device_name = explode(" ",$request->print_device_name);
        $acdcs_data = DB::connection('mysql_rapid_acdcs')
        ->select("SELECT DISTINCT `doc_no`,`doc_type`,`rev_no` FROM tbl_active_docs
                    WHERE `doc_type` = 'B Drawing' AND `doc_title` LIKE '%".$device_name[0]."%' ORDER BY `rev_no` DESC");
        // return $acdcs_data;
        $package_count = $request->print_total_qty / $request->print_qty;

        $collection = [
            'po_no' => $request->print_delivery_key_no,
            'parts_code' => $request->print_parts_code,
            'device_name' => $request->print_device_name,
            'lot_no' => $request->print_lot_no,
            'qty' => $request->print_qty,
            'package_category' => $request->print_package_category,
            'package_count' => $package_count,
            'drawing_no' => $acdcs_data[0]->doc_no,
            'rev_no' => $acdcs_data[0]->rev_no,
        ];

        $preshipment_qr = QrCode::format('png')
            ->size(300)->errorCorrection('H')
            ->generate(json_encode($collection));

        $partcode_qr = QrCode::format('png')
            ->size(300)->errorCorrection('H')
            ->generate(json_encode($request->parts_code));

        $lotno_qr = QrCode::format('png')
            ->size(300)->errorCorrection('H')
            ->generate(json_encode($request->lot_no));

        $lotno_qr_code = "data:image/png;base64," . base64_encode($preshipment_qr);
        $partcode_qr_code = "data:image/png;base64," . base64_encode($partcode_qr);
        $lotno_qr_code = "data:image/png;base64," . base64_encode($lotno_qr);

        // $data[] = array(
        //     'img'  => $qr_code,
        //     'text' =>  "<strong>$runcard->po_number</strong><br>
        //     <strong>$runcard->po_quantity</strong><br>
        //     <strong>$runcard->part_name</strong><br>
        //     <strong>$runcard->part_code</strong><br>
        //     <strong>$runcard->production_lot</strong><br>
        //     <strong>$shipment_output</strong><br>
        //     <strong>$all_operator_names</strong><br>
        //     "
        // );

        // $label = "
        //     <table class='table table-sm table-borderless' style='width: 100%;'>
        //         <tr>
        //             <td>PO No:</td>
        //         </tr>
        //         <tr>
        //             <td>PO No:</td>
        //             <td>$runcard->po_number</td>
        //         </tr>
        //         <tr>
        //             <td>PO Quantity:</td>
        //             <td>$runcard->po_quantity</td>
        //         </tr>
        //         <tr>
        //             <td>Device Name:</td>
        //             <td>$runcard->part_name</td>
        //         </tr>
        //         <tr>
        //             <td>Part Code:</td>
        //             <td>$runcard->part_code</td>
        //         </tr>
        //         <tr>
        //             <td>Production Lot #:</td>
        //             <td>$runcard->production_lot</td>
        //         </tr>
        //         <tr>
        //             <td>Shipment Output:</td>
        //             <td>$shipment_output</td>
        //         </tr>
        //         <tr>
        //             <td>Operator Name:</td>
        //             <td>$all_operator_names</td>
        //         </tr>
        //         <tr>
        //             <td>QR Purpose:</td>
        //             <td>$print_status</td>
        //         </tr>
        //     </table>
        // ";

        return response()->json(['qr_code' => $qr_code, 'label_hidden' => $data, 'label' => $label]);
    }

    public function getPreShipDetailsById(Request $request){
        $preship_details = DB::table('rapid_pre_shipment_details AS preship_details')
                                ->select('preship_details.*')
                                ->where('preship_details.preshipment_pkid', $request->pre_shipment_id)
                                ->whereNull('preship_details.deleted_at')
                                ->get();

        return response()->json(['result' => $preship_details]);
    }

    public function addPreShipmentDetailsData(Request $request){

        date_default_timezone_set('Asia/Manila');
        $data = $request->all();
        // return $data;
        try{
            if(!isset($request->pre_shipment_details_id)){
                // if(RapidPreShipmentDetails::where('preshipment_pkid', $request->pre_shipment_id)->whereNull('deleted_at')->exists()){
                //     return response()->json(['result' => 2]);
                // }else{
                    foreach ($request->checkbox_id as $key => $value) {
                        RapidPreShipmentDetails::insert([
                            'preshipment_pkid' => $request->pre_shipment_id,
                            'master_carton_no' => $request->master_carton_no[$key],
                            'item_no'          => $request->item_no[$key],
                            'po_no'            => $request->po_number,
                            'parts_code'       => $request->parts_code,
                            'device_name'      => $request->device_name,
                            'lot_no'           => $request->lot_no[$key],
                            'qty'              => $request->quantity[$key],
                            'package_category' => $request->package_cat[$key],
                            'package_qty'      => $request->package_qty[$key],
                            'weighed_by'       => $request->weighed_by,
                            'packed_by'        => $request->packed_by,
                            'checked_by'       => $request->checked_by,
                            'remarks'          => $request->remarks[$key],
                            'created_by'       => Auth::user()->id,
                            'last_updated_by'  => Auth::user()->id,
                            'created_at'       => date('Y-m-d H:i:s'),
                            'updated_at'       => date('Y-m-d H:i:s'),
                        ]);
                    }

                    #For Additional Update on Main Table
                    RapidPreShipment::where('id', $request->pre_shipment_id)
                        ->update([
                                'status'  => 1,
                                'last_updated_by'  => Auth::user()->id,
                        ]);
                // }
            }else{
                RapidPreShipmentDetails::where('id', $request->pre_shipment_details_id)
                    ->where('preshipment_pkid', $request->pre_shipment_id)
                    ->update([
                        'master_carton_no' => $request->master_carton_no,
                        'item_no'          => $request->item_no,
                        'po_no'            => $request->po_number,
                        'parts_code'       => $request->parts_code,
                        'device_name'      => $request->device_name,
                        'lot_no'           => $request->lot_no,
                        'qty'              => $request->quantity,
                        'package_category' => $request->package_category,
                        'package_qty'      => $request->package_qty,
                        'weighed_by'       => $request->weighed_by,
                        'packed_by'        => $request->packed_by,
                        'checked_by'       => $request->checked_by,
                        'remarks'          => $request->remarks,
                        'last_updated_by'  => Auth::user()->id,
                        'updated_at'       => date('Y-m-d H:i:s'),
                    ]);

                #For Additional Update on Main Table
                // RapidPreShipment::where('id', $request->pre_shipment_id)
                //     ->update([
                //             'shipment_output'  => $request->output_qty,
                //             'last_updated_by'  => Auth::user()->id,
                //     ]);
            }

            return response()->json(['result' => 1]);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function GetPOFromDeliveryUpdate(Request $request){
        $po_details = DB::connection('mysql')->select('SELECT DISTINCT po_no FROM delivery_updates ORDER BY delivery_updates.id DESC');
        return response()->json(['result' => 1, 'po_details' => $po_details]);
    }

    public function UpdateProdRuncardStatus(Request $request){
        date_default_timezone_set('Asia/Manila');
        // session_start();
        RapidPreShipment::where('id', $request->runcard_id)
                    ->update([
                        'status'              => 1,
                        'last_updated_by'     => Auth::user()->id,
                        'updated_at'          => date('Y-m-d H:i:s'),
                    ]);

        RapidPreShipmentDetails::where('prod_runcards_id', $request->runcard_id)
                    ->update([
                        'status'              => 1,
                        'last_updated_by'     => Auth::user()->id,
                        'updated_at'          => date('Y-m-d H:i:s'),
                    ]);

                    DB::commit();
        return response()->json(['result' => 1]);
    }

    public function SubmitProdRuncard(Request $request){
        date_default_timezone_set('Asia/Manila');
        // session_start();
        RapidPreShipment::where('id', $request->cnfrm_assy_id)
                    ->update([
                        'status'              => 3,
                        'last_updated_by'     => Auth::user()->id,
                        'updated_at'          => date('Y-m-d H:i:s'),
                    ]);

        RapidPreShipmentDetails::where('prod_runcards_id', $request->cnfrm_assy_id)
                    ->update([
                        'status'              => 3,
                        'last_updated_by'     => Auth::user()->id,
                        'updated_at'          => date('Y-m-d H:i:s'),
                    ]);

                    DB::commit();
        return response()->json(['result' => 'Successful']);
    }

    public function CheckExistingStations(Request $request){
        $prod_runcard_details = RapidPreShipment::with('device_details.material_process.station_details.stations', 'runcard_station')->where('id', $request->runcard_id)->whereNull('deleted_at')->first();

        // **CHECK IF ANNEALING IS IN MATERIAL PROCESS
        $is_annealing_station_exist = 'False';
        foreach($prod_runcard_details->device_details->material_process as $process){
            $stations = $process->station_details[0]->stations;
            if($stations->station_name == 'Annealing' && $process->status == 0) {
                $is_annealing_station_exist = 'True';
                break; // Exit all loops once we find the station
            }
        }

        //** CHECK IF THERE IS UD_PTNR_NO
        $is_ud_ptnr_no_exist = 'False';
        if(isset($prod_runcard_details->ud_ptnr_no)){
            $is_ud_ptnr_no_exist = 'True';
        }

        $count_of_steps = count($prod_runcard_details->device_details->material_process);
        $count_of_existing_station = count($prod_runcard_details->runcard_station);

        if($count_of_existing_station > 0){
            $last_index_existing_station = (count($prod_runcard_details->runcard_station) - 1);
            $previous_station_step = $prod_runcard_details->runcard_station[$last_index_existing_station]->station_step;
            $previous_sub_station_step = $prod_runcard_details->runcard_station[$last_index_existing_station]->sub_station_step;
        }else{
            $previous_station_step = 0;
            $previous_sub_station_step = 0;
        }

        $current_step = 0;
        $output_quantity = 0;
        $toadd = 0;
        $tominus = 0;

        if($is_ud_ptnr_no_exist == 'False' && $is_annealing_station_exist == 'False'){ // **W/O ANNEALING & UD
            // return 'true';
            if($previous_station_step == 1){
                $toadd = 1; //skip 1 substation
            }else if($previous_station_step == 3 && $previous_sub_station_step == 5){
                $tominus = 1; //stay to current station
            }
        }else if($is_ud_ptnr_no_exist == 'True' && $is_annealing_station_exist == 'True'){// **WITH ANNEALING & UD
            $toadd = 0;
            // return 'true';
            if($previous_station_step == 3 && $previous_sub_station_step == 3){// **FINISHING SEGREGATION
                $tominus = 1;
            }else if($previous_station_step == 4 && $previous_sub_station_step == 5){
                $tominus = 1; //skip 1 station
            }
        }else if($is_ud_ptnr_no_exist == 'True' && $is_annealing_station_exist == 'False'){ // **W/O ANNEALING BUT W/ UD
            // return 'true';
            if($previous_station_step == 1){
                $toadd = 0;
            }else if($previous_station_step == 2 && $previous_sub_station_step == 3){
                $tominus = 1;
            }else if($previous_station_step == 3 && $previous_sub_station_step == 5){
                $tominus = 1;
            }
        }else if($is_ud_ptnr_no_exist == 'False' && $is_annealing_station_exist == 'True'){ // **W/ ANNEALING BUT W/O UD
            $toadd = 0;
            if($previous_station_step == 2){// **FOR VISUAL AIRBLOWING
                $toadd = 1; //skip 1 station
            }else if($previous_station_step == 4 && $previous_sub_station_step == 5){ // **FOR VISUAL VISUAL
                $tominus = 1; //stay to current station
            }
        }

        //CLARK COMMENT 01/08/2025 Old Code
        // $ud_ptnr = 0; //No UD Document
        // if($prod_runcard_details->ud_ptnr_no != ''){
        //     $ud_ptnr = 1; //UD Document exist
        // }

        // $previous_station_step <= 2 is for checking if previous status is injection or annealing
        // if($ud_ptnr == 0 && ($previous_station_step == 1 || $previous_station_step == 2)){ //if previous station is  injection(step 1) and there is no ud/ptnr, skip to visual inspection
        //     $toadd = 1; //proceed to next station
        // }else if($ud_ptnr == 1 && ($previous_station_step == 1 || $previous_station_step == 2) && $previous_sub_station_step == 2){ //if previous station is finishing and sub station is production, stay in current station and proceed to next substation
        //     $tominus = 1; //stay in current station
        // }else if($ud_ptnr == 1 && ($previous_station_step == 2 || $previous_station_step == 3) && $previous_sub_station_step == 3){ //if previous station is finishing and sub station is production, stay in current station and proceed to next substation
        //     $tominus = 1; //stay in current station
        // }else if(($previous_station_step == 3 || $previous_station_step == 4) && $previous_sub_station_step == 5){ //if previous station is visual inspection and sub station is airblowing, stay in current station and proceed to next substation
        //     $tominus = 1; //stay in current station
        // }
        //CLARK COMMENT 01/08/2025 Old Code

        $previous_station_step = ($previous_station_step + $toadd) - $tominus;

        if($previous_station_step < $count_of_steps){
            $current_step = $previous_station_step + 1; //increment step, proceed to the next station

            $output_qty = RapidPreShipmentDetails::whereNull('deleted_at')
                                                    ->where('prod_runcards_id', $request->runcard_id)
                                                    // ->where('station_step', count($steps))
                                                    ->where('station_step', ($current_step - 1) - $toadd + $tominus)//get the previous station
                                                    // ->where('station_step', $previous_station_step)
                                                    ->where('sub_station_step', $previous_sub_station_step)
                                                    ->first();

            if(isset($output_qty->output_quantity)){
                $output_quantity = $output_qty->output_quantity;
            }else{
                $output_quantity = '';
            }
        }

        $ipqc_status = DB::table('qualification_details AS quali')->select('quali.id', 'runcard.po_number', 'runcard.production_lot', 'runcard.part_name')
                            ->leftJoin('production_runcards AS runcard', 'quali.fk_prod_runcard_id', '=', 'runcard.id')
                            ->where('runcard.po_number', $prod_runcard_details->po_number)
                            ->where('runcard.production_lot', $prod_runcard_details->production_lot)
                            ->where('runcard.part_name', $prod_runcard_details->part_name)
                            ->whereNull('runcard.deleted_at')
                            ->where('quali.logdel', 0)
                            ->get();

        if(count($ipqc_status) > 0){
            $btn_attr = 'true';
        }else{
            $btn_attr = 'false';
        }

        return response()->json(['count_of_existing_station' => $count_of_existing_station, 'count_of_steps' => $count_of_steps, 'current_step' => $current_step, 'output_quantity' => $output_quantity, 'previous_station_step' => $previous_station_step, 'ud_ptnr' => $is_ud_ptnr_no_exist, 'existing_ipqc' => $btn_attr]);
        // return response()->json(['current_step' => $current_step, 'output_quantity' => $output_quantity]);
    }

    public function CheckExistingSubStations(Request $request){
        $prod_runcard_details = RapidPreShipment::with('device_details.material_process.station_details.stations', 'runcard_station')->where('id', $request->runcard_id)->whereNull('deleted_at')->first();

        // **CHECK IF ANNEALING IS IN MATERIAL PROCESS
        $is_annealing_station_exist = 'False';
        foreach($prod_runcard_details->device_details->material_process as $process){
            $stations = $process->station_details[0]->stations;
            if ($stations->station_name == 'Annealing' && $process->status == 0){
                $is_annealing_station_exist = 'True';
                break; // Exit all loops once we find the station
            }
        }

        //** CHECK IF THERE IS UD_PTNR_NO
        $is_ud_ptnr_no_exist = 'False';
        if(isset($prod_runcard_details->ud_ptnr_no)){
            $is_ud_ptnr_no_exist = 'True';
        }

        $sub_station_array = [1,2,3,4,5,6];
        $count_of_steps = count($sub_station_array);
        $count_of_existing_station = count($prod_runcard_details->runcard_station);

        if($count_of_existing_station > 0){
            $last_index_existing_station = (count($prod_runcard_details->runcard_station) - 1);
            $previous_station_step = $prod_runcard_details->runcard_station[$last_index_existing_station]->station_step;
            $previous_sub_station_step = $prod_runcard_details->runcard_station[$last_index_existing_station]->sub_station_step;
        }else{
            $previous_station_step = 0;
            $previous_sub_station_step = 0;
        }

        $current_step = 0;
        $toadd = 0;
        $tominus = 0;

        if($is_ud_ptnr_no_exist == 'False' && $is_annealing_station_exist == 'False' && $previous_station_step == 1){ //if current station is finishing skip to visual inspection
            $toadd = 3;
        }else if($is_ud_ptnr_no_exist == 'True' && $is_annealing_station_exist == 'True' && $previous_station_step == 1){
            $toadd = 0;
        }else if($is_ud_ptnr_no_exist == 'True' && $is_annealing_station_exist == 'False' && $previous_station_step == 1){
            $toadd = 1;
        }else if($is_ud_ptnr_no_exist == 'False' && $is_annealing_station_exist == 'True' && $previous_station_step == 2){
            $toadd = 2;
        }

        $previous_sub_station_step = $previous_sub_station_step + $toadd;

        if($previous_sub_station_step < $count_of_steps){
            $current_step = $previous_sub_station_step + 1;
        }

        return response()->json(['count_of_existing_station' => $count_of_existing_station, 'count_of_steps' => $count_of_steps, 'current_step' => $current_step, 'previous_station_step' => $previous_sub_station_step, 'ud_ptnr' => $is_ud_ptnr_no_exist]);
    }

    public function GetMatrixDataByDevice(Request $request){
        $material_name = [];
        $material_code = [];
        $material_class = [];

        $matrix_data = Devices::with(['material_process.material_details', 'material_process.station_details.stations'])
        ->when($request->device_name, function ($query) use ($request){
            return $query->where('name', $request->device_name);
        })
        ->where('status', 1)
        ->get();

        // return get_object_vars($matrix_data[0]);
        if(count($matrix_data[0]->material_process) > 0){
            // return 'true';
            foreach($matrix_data[0]->material_process[0]->material_details as $material_details){
                $material_name[] = $material_details->material_type;
                $material_code[] = $material_details->material_code;
                $test = DB::connection('mysql_rapid_pps')
                                    ->select('SELECT whs.Classification AS class_id
                                            FROM tbl_Warehouse AS whs WHERE whs.MaterialType = "'.$material_details->material_type.'" LIMIT 1
                                        ');
                $material_class[] = $test[0]->class_id;
            }

            $material_type = implode(',',$material_name);
            $material_codes = implode(',',$material_code);
            $material_class = implode(',',$material_class);

            $station_details = $matrix_data[0]->material_process[0]->station_details;

            return response()->json(['device_details' => $matrix_data, 'material_details' => $material_type, 'material_codes' => $material_codes, 'material_class' => $material_class]);
        }else{
            // return 'false';
            return response()->json(['device_details' => 0, 'material_details' => 0]);
            // return response()->json(['device_details' => $matrix_data, 'material_details' => $material_type, 'material_codes' => $material_codes, 'material_class' => $material_class]);
        }

        // return $material_name;
        // foreach($matrix_data[0]->material_process[0]->material_details as $material_details){
        // }
        // $whs_material_name = DB::connection('mysql_rapid_pps')
        // ->select(' SELECT whs.MaterialType AS mat_name, whs_transaction.Lot_number AS lot_no
        //         FROM tbl_Warehouse AS whs
        //         INNER JOIN tbl_Warehouse_Classification AS whs_class ON whs.Classification = whs_class.id
        //         WHERE whs.MaterialType = "'.$request->mat_lot_number.'"
        //         LIMIT 1
        //     ');
        // return $matrix_data;

        // return $matrix_data;

    }

    public function getPreshipmentQrcode (Request $request){
        $data = RapidPreShipment::select('rapid_pre_shipments.id',
                                            'details.po_no',
                                            'rapid_pre_shipments.shipment_date',
                                            'details.qty',
                                            'details.device_name',
                                            'details.parts_code',
                                            'details.packed_by',
                                            'details.lot_no',
                                            'rapid_pre_shipments.status AS preshipment_status',
                                            DB::raw("CONCAT( firstname, ' ', lastname) AS operator_name"))
                                    ->leftJoin('rapid_pre_shipment_details AS details', function($join){
                                        $join->on('details.preshipment_pkid', '=' ,'rapid_pre_shipments.id');
                                    })
                                    ->leftJoin('users', function($join){
                                        $join->on('users.id', '=', 'rapid_pre_shipments.last_updated_by');
                                    })
                                    ->where('rapid_pre_shipments.id', $request->preshipment_id)
                                    ->whereNull('rapid_pre_shipments.deleted_at')
                                    ->first();
        // return $data;

        $device_name = explode(" ",$data->device_name);

        #FIRST HALF OF DEVICE NAME
        // $device_name[0]
        // $concatted_device_name = $device_name[0].'-'.$device_name[1]; // CLARK 09182024

        $acdcs_data = DB::connection('mysql_rapid_acdcs')
                        ->select("SELECT DISTINCT `doc_no`,`doc_type`,`rev_no` FROM tbl_active_docs
                        WHERE `doc_type` = '".$request->doc_type."' AND `doc_title` LIKE '%".$device_name[0]."%' ORDER BY `rev_no` DESC"
                );

        return $acdcs_data;
        // $op_names_array = [];
        // $operator_name_per_runcard = DB::table('rapid_pre_shipment_details AS stations')
        //                             ->select(DB::raw("CONCAT(LEFT(users.firstname, 1), '.', users.lastname) AS operator_name"))
        //                             ->join('users', 'stations.operator_name', '=', 'users.id')
        //                             ->where('stations.preshipment_pkid', $runcard->id)
        //                             ->whereNull('stations.deleted_at')
        //                             ->distinct()
        //                             ->get();
        //                             // ->pluck('operator_name');

        // // return $operator_name_per_runcard;
        // $all_operator_names = [];
        // foreach ($operator_name_per_runcard as $row) {
        //     $all_operator_names[] = $row->operator_name;  // Add a space or custom separator
        // }
        // $all_operator_names = implode(', ', $all_operator_names);

        // foreach ($operator_name_per_runcard as $items) {
        //     array_push($op_names_array, $items);
        // }

        // $unique_op_names = array_unique($op_names_array);
        // $all_operator_names = implode(', ', $unique_op_names);

        // if($runcard->runcard_status == 3){
        //     $shipment_output = $runcard->shipment_output;
        //     $print_status = 'For OQC';
        // }else if($runcard->runcard_status == 1){
        //     $shipment_output = $runcard->shipment_output;
        //     $print_status = 'For Traceability Only';
        // }else if($runcard->runcard_status == 0){
        //     // $shipment_output = $runcard->shipment_output;
        //     $shipment_output = 'N/A';
        //     $all_operator_names = $runcard->operator_name;
        //     $print_status = 'For IPQC';
        // }else{
        //     $shipment_output = 'N/A';
        // }
        // $print_status

        $qrcode = QrCode::format('png')
        ->size(300)->errorCorrection('H')
        ->generate(json_encode($data));

        $qr_code = "data:image/png;base64," . base64_encode($qrcode);

        // $print_status!='For OQC'?'':$print_status;
        // <strong>$print_status</strong><br>
        $data[] = array(
            'img'  => $qr_code,
            'text' =>  "<strong>$runcard->po_number</strong><br>
            <strong>$runcard->po_quantity</strong><br>
            <strong>$runcard->part_name</strong><br>
            <strong>$runcard->part_code</strong><br>
            <strong>$runcard->production_lot</strong><br>
            <strong>$shipment_output</strong><br>
            <strong>$all_operator_names</strong><br>
            "
            // <strong>$print_status</strong><br> //clark comment 01/08/2025
        );

        $label = "
            <table class='table table-sm table-borderless' style='width: 100%;'>
                <tr>
                    <td>PO No:</td>
                    <td>$runcard->po_number</td>
                </tr>
                <tr>
                    <td>PO Quantity:</td>
                    <td>$runcard->po_quantity</td>
                </tr>
                <tr>
                    <td>Device Name:</td>
                    <td>$runcard->part_name</td>
                </tr>
                <tr>
                    <td>Part Code:</td>
                    <td>$runcard->part_code</td>
                </tr>
                <tr>
                    <td>Production Lot #:</td>
                    <td>$runcard->production_lot</td>
                </tr>
                <tr>
                    <td>Shipment Output:</td>
                    <td>$shipment_output</td>
                </tr>
                <tr>
                    <td>Operator Name:</td>
                    <td>$all_operator_names</td>
                </tr>
                <tr>
                    <td>QR Purpose:</td>
                    <td>$print_status</td>
                </tr>
            </table>
        ";

        return response()->json(['qr_code' => $qr_code, 'label_hidden' => $data, 'label' => $label, 'production_runcard_data' => $runcard]);
    }
}
