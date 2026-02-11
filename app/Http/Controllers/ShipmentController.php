<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use DataTables;
use Carbon\Carbon;
use App\Exports\Export;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Shipment;
use App\Models\ShipmentDetails;

class ShipmentController extends Controller
{
    public function viewShipmentData(Request $request){
        $shipmentData = Shipment::with(['shipment_details'])
        ->where('logdel', 0)
        ->get();

        return DataTables::of($shipmentData)
        ->addColumn('action', function($shipmentData){
            $result = "";
            $result .= "<center>";
            $result .= "<button class='btn btn-sm btn-secondary ml-1 btnEditShipmentData' data-id='$shipmentData->id'><i class='fa-solid fa-pen-to-square'></i></button>";
            $result .= "</center>";
            return $result;
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function addShipmentData(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();
        $rapidx_user_id = $_SESSION['rapidx_user_id'];

        $data = $request->all();
        
        // return $data;

        $getShipmentData = Shipment::where('logdel',0)
        ->orderBy('id','desc')
        ->first();

        $counter = 00000;
        $yearMonth = date('Ym');

        if($getShipmentData != null){
            $ctrl_no = $getShipmentData->ctrl_no;
            $number = explode('-', $ctrl_no);
            $counter = intval($number[2]) + 1; 
        }else{
            $counter = 1;
        }

        $ctrl_number = 'PPD-'.$yearMonth.'-'.str_pad($counter, 5, "0", STR_PAD_LEFT);
       

        // return $ctrl_number;

        $shipment_data = array(
            'ctrl_no' => $ctrl_number,
            'ps_ctrl_no' => $request->preShipment_ctrl,
            'shipment_date' => $request->shipment_date,
            'rev_no' => $request->rev_no,
            'sold_to' => $request->sold_to,
            'shipped_by' => $request->shipped_by,
            'cutoff_month' => $request->cut_off_date,
            'grand_total' => $request->grand_total,
            'created_by' => $rapidx_user_id,
            'created_at' => date('Y-m-d H:i:s'),
        );

        $shipment_data_update = array(
            'ctrl_no' => $request->ctrl_number,
            'ps_ctrl_no' => $request->preShipment_ctrl,
            'shipment_date' => $request->shipment_date,
            'rev_no' => $request->rev_no,
            'sold_to' => $request->sold_to,
            'shipped_by' => $request->shipped_by,
            'cutoff_month' => $request->cut_off_date,
            'grand_total' => $request->grand_total,
            'updated_by' => $rapidx_user_id,
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $shipmentDetailsArray = explode(',', $request->shipment_details);

        // DB::beginTransaction(); // Start a new transaction
        // return $request->shipment_id;
        // return 'asd';

        try{
            if(isset($request->shipment_id)){
                // return 'if';
                // return $request->shipment_id;
                Shipment::where('id', $request->shipment_id)
                ->update($shipment_data_update);
    
                // Delete existing shipment details
                $shipmentDetails = json_decode($request->input('shipment_details'), true);

                
                ShipmentDetails::where('shipment_id', $request->shipment_id)->delete();

                foreach ($shipmentDetails as $shipmentDetails) {
                    // return $shipmentDetails['item_code'];
                    ShipmentDetails::insert([
                        'shipment_id' => $request->shipment_id,
                        'fkControlNo' => $request->preShipment_ctrl,
                        'item_code' => $shipmentDetails['item_code'],
                        'item_name' => $shipmentDetails['item_name'],
                        'order_no' => $shipmentDetails['order_no'],
                        'shipout_qty' => $shipmentDetails['shipout_qty'],
                        'unit_price' => $shipmentDetails['unit_price'],
                        'amount' => $shipmentDetails['amount'],
                        'lot_no' => $shipmentDetails['lot_no'],
                        'remarks' => $shipmentDetails['remarks'],
                        'created_by' => $rapidx_user_id,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
                return response()->json(['result' => "2"]);
            }
            else{        
                $shipment_id = Shipment::insertGetId($shipment_data);
                $shipmentDetails = json_decode($request->input('shipment_details'), true);
                // return $shipmentDetails;
                    foreach ($shipmentDetails as $shipmentDetails) {
                        ShipmentDetails::insert([
                            'shipment_id' => $shipment_id,
                            'fkControlNo' => $request->preShipment_ctrl,
                            'item_code' => $shipmentDetails['item_code'],
                            'item_name' => $shipmentDetails['item_name'],
                            'order_no' => $shipmentDetails['order_no'],
                            'shipout_qty' => $shipmentDetails['shipout_qty'],
                            'unit_price' => $shipmentDetails['unit_price'],
                            'amount' => $shipmentDetails['amount'],
                            'lot_no' => $shipmentDetails['lot_no'],
                            'remarks' => $shipmentDetails['remarks'],
                            'created_by' => $rapidx_user_id,
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
                    return response()->json(['result' => "1"]);
                // }
            } 
        }catch(\Exception $e){
            DB::rollback(); // Rollback the transaction
            return response()->json(['result' => "0"]);
        }

    }

    public function getPOReceivedDetails(Request $request){
       
        $po_details = DB::connection('mysql_rapid_pps')->select("SELECT * FROM tbl_POReceived");

        return $po_details;
    
        return response()->json(['result' => 1, 'po_details' => $po_details]);
    }

    public function loadPreshipmentDetails (Request $request){
        // return $request->ps_ctrl_number;
        if($request->ps_ctrl_number != ''){
            preg_match('/^(.*?)-(\d+-\d+)$/', $request->ps_ctrl_number, $matches);
            $packingDestination = trim($matches[1]); // Extracted text part
            $packingListCtrlNo = $matches[2]; // Extracted numbers (with dash)

            $pre_shipment_details = DB::connection('mysql_rapid_pps')->select("SELECT 
            tbl_PreShipmentTransaction.PONo as order_no,
            tbl_PreShipmentTransaction.Partscode as item_code, 
            tbl_PreShipmentTransaction.DeviceName as item_name, 
            tbl_PreShipmentTransaction.Qty as shipout_qty,
            tbl_PreShipmentTransaction.Remarks as remarks,
            tbl_POReceived.Price as unit_price,
            FORMAT(CEIL(tbl_PreShipmentTransaction.Qty * tbl_POReceived.Price * 10000) / 10000, 4) as amount,
            tbl_PreShipmentTransaction.LotNo as lot_no
            FROM tbl_PreShipment 
            INNER JOIN tbl_PreShipmentTransaction ON tbl_PreShipment.Packing_List_CtrlNo = tbl_PreShipmentTransaction.fkControlNo
            INNER JOIN tbl_POReceived ON tbl_PreShipmentTransaction.PONo = tbl_POReceived.OrderNo
            WHERE Destination = '$packingDestination' AND Packing_List_CtrlNo = '$packingListCtrlNo'
            ORDER BY tbl_PreShipmentTransaction.id ASC
            ");
        }else{
            $pre_shipment_details = [];
        }

        // return $pre_shipment_details;

        return response()->json(['result' => 1, 'pre_shipment_details' => $pre_shipment_details]);
    }

    public function getShipmentData(Request $request){
        $shipment_data = Shipment::with([
            'shipment_details'
        ])
        ->where('id', $request->shipment_id)
        ->where('logdel', 0)
        ->get();

        return response()->json(['shipmentData' => $shipment_data]);
    }
}
