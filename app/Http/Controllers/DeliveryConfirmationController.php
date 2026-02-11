<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\DeliveryUpdate;
use App\Models\DeliveryConfirmation;
use App\Models\ProductionRuncard;

class DeliveryConfirmationController extends Controller
{
    public function searchPOReceivedDetails(Request $request){
        $shipment_date1 = $request->shipment_date1;
        $shipment_date2 = $request->shipment_date2;
        $pr_number = $request->ps_ctrl_number;

        // return $shipment_date1;

        if (isset($pr_number)) {
            // return 'if';
            $po_received_details = DB::connection('mysql_rapid_pps')
                ->select("SELECT * FROM tbl_POReceived AS PORcv WHERE orderNo = ?", [$pr_number]);

            $delivery_update_details = DeliveryUpdate::where('logdel', 0)
                ->where('po_no', $pr_number)
                ->get();

            $delivery_confirmation_details = DeliveryConfirmation::where('logdel', 0)
            ->where('order_no', $pr_number)
            ->get();


            $merged_data = [];

            foreach ($po_received_details as $po_received) {
                $matching_delivery_updates = $delivery_update_details->where('po_no', $po_received->OrderNo);
                if ($matching_delivery_updates->count() > 0) {
                    $merged_delivery_updates = [];
                    $total_actual_so = 0;

                    foreach ($matching_delivery_updates as $delivery_update) {
                        $merged_delivery_updates[] = [
                            'lot_no' => $delivery_update->lot_no,
                            'remarks' => $delivery_update->remarks,
                            'actual_so' => $delivery_update->actual_so,
                        ];
                        $total_actual_so += $delivery_update->actual_so;
                    }

                     // Get shipment date from DeliveryConfirmation table (if available)
                    $shipment_date = '';
                    $matching_delivery_confirmation = $delivery_confirmation_details->where('order_no', $po_received->OrderNo)->first();

                    if ($matching_delivery_confirmation) {
                        $shipment_date = $matching_delivery_confirmation->shipment_date; // Assuming `shipment_date` is the column in DeliveryConfirmation
                    }

                    $merged_data[] = [
                        'id' => $po_received->id,
                        'item_code' => $po_received->ItemCode,
                        'item_name' => $po_received->ItemName,
                        'order_no' => $po_received->OrderNo,
                        'order_balance' => $po_received->OrderQty,
                        'delivery_updates' => $merged_delivery_updates,
                        'category' => $po_received->Category,
                        'product_po_no' => $po_received->ProductPONo,
                        'order_qty' => $po_received->OrderQty,
                        'shipment_date' => $shipment_date,
                        'variance' => $po_received->OrderQty - $total_actual_so,
                        'wip' => '',
                        'fgs' => '',
                    ];
                }
            }

            // Return to DataTables
            return DataTables::of($merged_data)
            ->addColumn('action', function($merged_data){
                $result = "<center>";
                if (empty($merged_data['shipment_date'])) {
                    $result .= "<input type='checkbox' class='po-checkbox'
                        data-id='{$merged_data['id']}'
                        data-item_code='{$merged_data['item_code']}'
                        data-item_name='{$merged_data['item_name']}'
                        data-order_no='{$merged_data['order_no']}'
                        data-order_balance='{$merged_data['order_balance']}'>";
                }else{
                    $result .= "<input type='checkbox' class='po-checkbox' disabled
                        data-id='{$merged_data['id']}'
                        data-item_code='{$merged_data['item_code']}'
                        data-item_name='{$merged_data['item_name']}'
                        data-order_no='{$merged_data['order_no']}'
                        data-order_balance='{$merged_data['order_balance']}'>";
                }

                $result .= "</center>";
                return $result;
            })
            ->addColumn('lot_no', function ($row) {
                return collect($row['delivery_updates'])->pluck('lot_no')->implode('<br>');
            })
            ->addColumn('remarks', function ($row) {
                return collect($row['delivery_updates'])->pluck('remarks')->implode('<br>');
            })
            ->addColumn('actual_so', function ($row) {
                return collect($row['delivery_updates'])->pluck('actual_so')->implode('<br>');
            })
            ->rawColumns(['action', 'lot_no', 'remarks', 'actual_so'])
            ->make(true);

        }else if($shipment_date1 != '' && $shipment_date2 != ''){
            $po_received_details = DB::connection('mysql_rapid_pps')
            /* OLD */
            // ->select("SELECT * FROM tbl_POReceived AS PORcv WHERE PORcv.DateIssued BETWEEN ? AND ?", [
           /* 05102025 Updated by Nessa */
            ->select("SELECT * FROM tbl_POReceived AS PORcv WHERE (Category = 'Card Connector' OR Category = 'Mounting Sockets Connector' OR Category = 'Flexicon Connectors' OR Category = 'TCDC Connectors') AND PORcv.DateIssued BETWEEN ? AND ?", [
                $shipment_date1, $shipment_date2
            ]);

           // Load all delivery confirmations (index by order_no for fast lookup)
            $delivery_confirmation_details = DeliveryConfirmation::where('logdel', 0)->get()->keyBy('order_no');
            $delivery_update_details = DeliveryUpdate::where('logdel', 0)->get()->groupBy('po_no');

            // return $delivery_update_details;


            return DataTables::of($po_received_details)
            ->addColumn('action', function($row) use ($delivery_confirmation_details) {
                // Check if there's a matching delivery confirmation for the OrderNo
                $matching_delivery_confirmation = $delivery_confirmation_details->where('order_no', $row->OrderNo)->first();

                // Initialize result variable
                $result = "<center>";

                // If no matching delivery confirmation, render checkbox
                if (empty($matching_delivery_confirmation)) {
                    $result .= "<input type='checkbox' class='po-checkbox'
                                    data-id='{$row->id}'
                                    data-item_code='{$row->ItemCode}'
                                    data-item_name=\"{$row->ItemName}\"
                                    data-order_no='{$row->OrderNo}'
                                    data-order_balance='{$row->OrderQty}'>";
                } else {
                    // Optional: You can customize what happens if a matching delivery confirmation is found
                    // For example, you might display a disabled checkbox or another action element
                    $result .= "<input type='checkbox' class='po-checkbox' disabled
                                    data-id='{$row->id}'
                                    data-item_code='{$row->ItemCode}'
                                    data-item_name=\"{$row->ItemName}\"
                                    data-order_no='{$row->OrderNo}'
                                    data-order_balance='{$row->OrderQty}'>";
                }

                $result .= "</center>";

                return $result;
            })
            ->addColumn('item_code', function($row){
                return "<center>{$row->ItemCode}</center>";
            })
            ->addColumn('item_name', function($row){
                return "<center>{$row->ItemName}</center>";
            })
            ->addColumn('order_no', function($row){
                return "<center>{$row->OrderNo}</center>";
            })
            ->addColumn('order_balance', function($row){
                return "<center>{$row->OrderQty}</center>";
            })
            ->addColumn('shipment_date', function($row) use ($delivery_confirmation_details) {
                // Get the matching delivery confirmation for the current PO's OrderNo
                $matching_delivery_confirmation = $delivery_confirmation_details->where('order_no', $row->OrderNo)->first();

                // Return shipment_date if a match is found, otherwise return a default message or empty value
                return $matching_delivery_confirmation ? $matching_delivery_confirmation->shipment_date : 'N/A';
            })
            ->addColumn('lot_no', function($row) use ($delivery_update_details, $delivery_confirmation_details) {
                // Check if there is a shipment date
                $matching_delivery_confirmation = $delivery_confirmation_details->where('order_no', $row->OrderNo)->first();

                // If there's no shipment date, don't show lot_no
                if (!$matching_delivery_confirmation || empty($matching_delivery_confirmation->shipment_date)) {
                    return ''; // or return null;
                }

                // If shipment date exists, show lot_no as before
                $delivery_update = $delivery_update_details->get($row->OrderNo, collect());

                if ($delivery_update->isNotEmpty()) {
                    $lot_no_str = $delivery_update->pluck('lot_no')->implode(', ');
                    return "<center>{$lot_no_str}</center>";
                }

                return ''; // Optional fallback
            })
            ->addColumn('variance', function($row) use ($delivery_update_details, $delivery_confirmation_details) {
                // Check if shipment_date exists
                $matching_delivery_confirmation = $delivery_confirmation_details->where('order_no', $row->OrderNo)->first();

                if (!$matching_delivery_confirmation || empty($matching_delivery_confirmation->shipment_date)) {
                    return ''; // Don't display variance if no shipment date
                }

                $delivery_update = $delivery_update_details->get($row->OrderNo, collect());

                if ($delivery_update->isNotEmpty()) {
                    $variance = $row->OrderQty - array_sum($delivery_update->pluck('actual_so')->toArray());
                    return "<center>{$variance}</center>";
                }

                return '';
            })
            ->addColumn('remarks', function($row) use ($delivery_update_details, $delivery_confirmation_details) {
                // Check if shipment_date exists
                $matching_delivery_confirmation = $delivery_confirmation_details->where('order_no', $row->OrderNo)->first();

                if (!$matching_delivery_confirmation || empty($matching_delivery_confirmation->shipment_date)) {
                    return ''; // Don't display remarks if no shipment date
                }

                $delivery_update = $delivery_update_details->get($row->OrderNo, collect());

                if ($delivery_update->isNotEmpty()) {
                    $remarks = $delivery_update->pluck('remarks')->implode(', ');
                    return "<center>{$remarks}</center>";
                }

                return '';
            })
            ->addColumn('wip', function($row){
                return "<center></center>";
            })
            ->addColumn('fgs', function($row){
                return "<center></center>";
            })
            ->rawColumns(['action','item_code','item_name','order_no','order_balance','shipment_date','lot_no','variance','remarks','wip','fgs'])
            ->make(true);

        }else{
            // return 'else';
            return DataTables::of(collect([]))
            ->addColumn('action', function () {
                return "<center></center>";
            })
            ->rawColumns(['action'])
            ->make(true);

        }

    }

    public function addShipmentDate(Request $request){
        $request->validate([
            'shipment_date' => 'required|date',
            'po_id' => 'required',
        ]);

        $ids = explode(',', $request->po_id);
        $item_names = explode(',', $request->item_name);
        $item_codes = explode(',', $request->item_code);
        $order_nos = explode(',', $request->order_no);
        $order_balances = explode(',', $request->order_balance);

        $insertData = [];
        for ($i = 0; $i < count($ids); $i++) {
            $insertData[] = [
                'POrcv_id'      => $ids[$i],
                'item_name'     => $item_names[$i],
                'item_code'     => $item_codes[$i],
                'order_no'      => $order_nos[$i],
                'order_balance' => $order_balances[$i],
                'shipment_date' => $request->shipment_date,
                'created_at'    => now(),
            ];
        }

        DeliveryConfirmation::insert($insertData);

        return response()->json(['success' => true]);
    }
}
