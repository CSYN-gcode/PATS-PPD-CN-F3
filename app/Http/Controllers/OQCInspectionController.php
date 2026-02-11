<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

use Auth;
use DataTables;

use App\Models\User;
use App\Models\Devices;
use App\Models\OqcInspection;
use App\Models\DropdownOqcAql;
use App\Models\AcdcsActiveDocs;
use App\Models\DropdownOqcFamily;
use App\Models\ProductionRuncard;
use App\Models\OqcInspectionReelLot;
use App\Models\OqcInspectionPrintLot;
use App\Models\DropdownOqcStampingLine;
use App\Models\DropdownOqcInspectionMod;
use App\Models\DropdownOqcInspectionType;
use App\Models\OqcInspectionModeOfDefect;
use App\Models\DropdownOqcInspectionLevel;
use App\Models\DropdownOqcInspectionCustomer;
use App\Models\DropdownOqcSeverityInspection;

class OQCInspectionController extends Controller
{
    //============================== VIEW FIRST STAMPING ==============================
    public function viewOqcInspection(Request $request){
        date_default_timezone_set('Asia/Manila');

        $prod_details = ProductionRuncard::with([
            'oqc_inspection_info'
        ])
        ->where('po_number', $request->poNo)
        ->where('status', '3')
        ->orderBy('id', 'DESC')
        ->get();

        switch ($request->getStatus) {
            case '0':
                $prod_details = collect($prod_details)->whereNotNull('oqc_inspection_info.lot_accepted')->where('oqc_inspection_info.lot_accepted', 0);
                break;
            case '1':
                $prod_details = collect($prod_details)->whereNotNull('oqc_inspection_info.lot_accepted')->where('oqc_inspection_info.lot_accepted', 1);
                break;
            default:
                $prod_details = collect($prod_details)->whereNull('oqc_inspection_info.lot_accepted');
                break;
        }
        // return $prod_details;
        return DataTables::of($prod_details)
        ->addColumn('action', function($prod_info){
            $result = '<center>';

            if($prod_info->oqc_inspection_info == null){
                $oqc_inspection_id = '0';
            }else{
                $oqc_inspection_id = $prod_info->oqc_inspection_info['id'];
            }

            $get_oqc_inspection_per_row = OqcInspection::where('production_runcard_id', $prod_info->id)->where('logdel', 0)->orderBy('id', 'DESC')->get();
            if(count($get_oqc_inspection_per_row) > 1){
                if($get_oqc_inspection_per_row[0]->lot_accepted == 0){
                    $result .= '
                    <button disabled class="btn btn-dark btn-sm text-center
                        actionEditOqcInspection"
                        oqc_inspection-id="' . $oqc_inspection_id . '"
                        prod-id="' . $prod_info->id . '"
                        prod-po="' . $prod_info->po_number . '"
                        prod-material_name="' . $prod_info->part_name . '"
                        prod-po_qty="' . $prod_info->po_quantity . '"
                        prod-lot_no="' . $prod_info->production_lot . '"
                        prod-ship_output="' . $prod_info->shipment_output . '"
                        data-bs-toggle="modal"
                        data-bs-target="#modalOqcInspection"
                        data-bs-keyboard="false"
                        title="Edit">
                        <i class="nav-icon fa fa-edit"></i>
                    </button >&nbsp;';
                }

                $result .= '
                    <button class="btn btn-warning btn-sm text-center
                        actionEditOqcInspectionHistory"
                        oqc_inspection-id="' . $oqc_inspection_id . '"
                        prod-id="' . $prod_info->id . '"
                        prod-po="' . $prod_info->po_number . '"
                        prod-material_name="' . $prod_info->part_name . '"
                        prod-po_qty="' . $prod_info->po_quantity . '"
                        prod-lot_no="' . $prod_info->production_lot . '"
                        prod-ship_output="' . $prod_info->shipment_output . '"
                        data-bs-toggle="modal"
                        data-bs-target="#mdlOqcInspectionFirstStampingHistory"
                        data-bs-keyboard="false"
                        title="History">
                        <i class="fa-solid fa-book-bookmark"></i>
                    </button>&nbsp;';
            }else{
                if(count($get_oqc_inspection_per_row) > 0 && $get_oqc_inspection_per_row[0]->lot_accepted == 1){
                    $result .= '
                        <button class="btn btn-info btn-sm text-center
                            actionViewOqcInspection"
                            oqc_inspection-id="' . $oqc_inspection_id . '"
                            prod-id="' . $prod_info->id . '"
                            prod-po="' . $prod_info->po_number . '"
                            prod-material_name="' . $prod_info->part_name . '"
                            prod-po_qty="' . $prod_info->po_quantity . '"
                            prod-lot_no="' . $prod_info->production_lot . '"
                            prod-ship_output="' . $prod_info->shipment_output . '"
                            data-bs-toggle="modal"
                            data-bs-target="#modalOqcInspection"
                            data-bs-keyboard="false"
                            title="View">
                            <i class="nav-icon fa fa-eye"></i>
                        </button>&nbsp;';
                }else{
                    $result .= '
                    <button disabled class="btn btn-dark btn-sm text-center
                        actionEditOqcInspection"
                        oqc_inspection-id="' . $oqc_inspection_id . '"
                        prod-id="' . $prod_info->id . '"
                        prod-po="' . $prod_info->po_number . '"
                        prod-material_name="' . $prod_info->part_name . '"
                        prod-po_qty="' . $prod_info->po_quantity . '"
                        prod-lot_no="' . $prod_info->production_lot . '"
                        prod-ship_output="' . $prod_info->shipment_output . '"
                        data-bs-toggle="modal"
                        data-bs-target="#modalOqcInspection"
                        data-bs-keyboard="false"
                        title="Edit">
                        <i class="nav-icon fa fa-edit"></i>
                    </button>&nbsp;';
                }
            }
            return $result;
        })

        ->addColumn('status', function($prod_info){
            $result = '<center>';
            if($prod_info->oqc_inspection_info != null){
                $get_oqc_inspection_per_row = OqcInspection::where('production_runcard_id', $prod_info->id)->where('logdel', 0)->orderBy('id', 'DESC')->get();
                switch($get_oqc_inspection_per_row[0]->lot_accepted)
                {
                    case 0: // LOT ACCEPTED
                    {
                        $result .= '<span class="badge badge-pill badge-danger"> Lot <br> Rejected</span>';
                        break;
                    }
                    case 1:  // LOT REJECTED
                    {
                        $result .= '<span class="badge badge-pill badge-success"> Lot <br> Accepted</span>';
                        break;
                    }
                    default: // SESTEM IRROR
                    {
                        $result .= 'N/A';
                        break;
                    }
                }
            }else{
                $result .= '<span class="badge badge-pill badge-info"> For <br> Inspection</span>';
            }
            $result .= '</center>';
            return $result;
        })

        ->addColumn('date_inspected', function($prod_info){
            $result = '<center>';
            if($prod_info->oqc_inspection_info != null){
                $result .= $prod_info->oqc_inspection_info->date_inspected;
            }
            $result .= '</center>';
            return $result;
        })

        ->addColumn('sample_size', function($prod_info){
            $result = '<center>';
            if($prod_info->oqc_inspection_info != null){
                $result .= $prod_info->oqc_inspection_info->sample_size;
            }
            $result .= '</center>';
            return $result;
        })

        ->addColumn('judgement', function($prod_info){
            $result = '<center>';
            if($prod_info->oqc_inspection_info != null){
                $result .= $prod_info->oqc_inspection_info->judgement;
            }
            $result .= '</center>';
            return $result;
        })

        ->addColumn('ud_ptnr', function($prod_info){
            $result = '<center>';
            $result .= $prod_info->ud_ptnr_no;
            $result .= '</center>';
            return $result;
        })

        ->addColumn('inspector', function($prod_info){
            $result = '<center>';
            if($prod_info->oqc_inspection_info != null){
                $result .= $prod_info->oqc_inspection_info->inspector;
            }
            $result .= '</center>';
            return $result;
        })

        ->rawColumns([
            'action',
            'status',
            'sample_size',
            'judgement',
            'ud_ptnr',
            'inspector',
            'date_inspected',
        ])
        ->make(true);
    }

    //  ============================== VIEW OQC INSPECTION HISTORY ==============================
    public function viewOqcInspectionHistory(Request $request){
        date_default_timezone_set('Asia/Manila');

        $oqc_details = OqcInspection::with([
            'production_runcard_info',
            'mod_oqc_inspection_details'
        ])
        ->where('production_runcard_id', $request->poNoById)
        ->where('logdel', 0)
        ->orderBy('id', 'DESC')
        ->get();

        return DataTables::of($oqc_details)
        ->addColumn('action', function($oqc_info){
            $result = '<center>';
            $result .= '
                <button class="btn btn-info btn-sm text-center
                    actionViewOqcInspection"
                    oqc_inspection-id="'. $oqc_info->id .'"
                    prod-id="'. $oqc_info->production_runcard_info->id .'"
                    prod-po="'. $oqc_info->production_runcard_info->po_number .'"
                    prod-material_name="'. $oqc_info->production_runcard_info->material_name .'"
                    prod-po_qty="'. $oqc_info->production_runcard_info->po_quantity .'"
                    prod-lot_no="'. $oqc_info->production_runcard_info->production_lot .'"
                    prod-ship_output="'. $oqc_info->production_runcard_info->shipment_output .'"
                    data-bs-toggle="modal"
                    data-bs-target="#modalOqcInspection"
                    data-bs-keyboard="false" title="View">
                    <i class="nav-icon fa fa-eye"></i>
                </button>';
            $result .= '</center>';
            return $result;
        })

        ->addColumn('fy_ww', function($oqc_info){
            $result = '<center>';
            $result .= $oqc_info->fy.'-'.$oqc_info->ww;
            $result .= '</center>';
            return $result;
        })

        ->addColumn('mod', function($oqc_info){
            $result = '<center>';
                if($oqc_info->judgement == 'Reject'){
                    for ($i=0; $i < count($oqc_info->mod_oqc_inspection_details); $i++) {
                        $result .= $oqc_info->mod_oqc_inspection_details[$i]->mod." \n ";
                    }
                }else{
                    $result .= 'N/A';
                }
            $result .= '</center>';
            return $result;
        })

        ->addColumn('update_user', function($prod_info){
            $get_oqc_inspection = OqcInspection::with(['user_info'])->where('id', $prod_info->id)->where('logdel', 0)->orderBy('id', 'DESC')->get();
            $result = '<center>';
            if(count($get_oqc_inspection) > 0){
                $result .= $get_oqc_inspection[0]->user_info->firstname.' '.$get_oqc_inspection[0]->user_info->lastname;
            }
            $result .= '</center>';
            return $result;
        })

        ->addColumn('created_at', function($oqc_info){
            $result = '<center>';
            $result .= $oqc_info->created_at;
            $result .= '</center>';
            return $result;
        })

        ->rawColumns([
            'action',
            'fy_ww',
            'mod',
            'update_user',
            'created_at'
        ])
        ->make(true);
    }

    public function updateOqcInspection(Request $request){
        date_default_timezone_set('Asia/Manila');
        $data = $request->all();
        $total = 0;
        if($request->oqc_inspection_lot_accepted == 1){
            $yield = '100%';
        }else{
            $get_ship_output = ProductionRuncard::where('id', $request->prod_id)->get();
            for($mod_counter = 0; $mod_counter <= $request->mod_counter; $mod_counter++) {
                $add_mod_quantity = $request->input("mod_qty_$mod_counter");
                $total += $add_mod_quantity;
            }
            $yield = number_format((($get_ship_output[0]->shipment_output-$total)/$get_ship_output[0]->shipment_output*100),2).'%';
        }
        $validator = Validator::make($data, [
            'oqc_inspection_machine_no'          => 'required',
            'oqc_inspection_application_date'       => 'required',
            'oqc_inspection_application_time'       => 'required',
            'oqc_inspection_product_category'       => 'required',
            'oqc_inspection_po_no'                  => 'required',
            'oqc_inspection_material_name'          => 'required',
            'oqc_inspection_customer'               => 'required',
            'oqc_inspection_po_qty'                 => 'required',
            'oqc_inspection_family'                 => 'required',
            'oqc_inspection_inspection_type'        => 'required',
            'oqc_inspection_inspection_severity'    => 'required',
            'oqc_inspection_inspection_level'       => 'required',
            'oqc_inspection_aql'                    => 'required',
            'oqc_inspection_sample_size'            => 'required',
            'oqc_inspection_accept'                 => 'required',
            'oqc_inspection_reject'                 => 'required',
            'oqc_inspection_date_inspected'         => 'required',
            'oqc_inspection_work_week'              => 'required',
            'oqc_inspection_fiscal_year'            => 'required',
            'oqc_inspection_time_inspected_from'    => 'required',
            'oqc_inspection_time_inspected_to'      => 'required',
            'oqc_inspection_shift'                  => 'required',
            'oqc_inspection_inspector'              => 'required',
            'oqc_inspection_submission'             => 'required',
            'oqc_inspection_coc_requirement'        => 'required',
            'oqc_inspection_judgement'              => 'required',
            'oqc_inspection_lot_inspected'          => 'required',
            'oqc_inspection_lot_accepted'           => 'required',
            'oqc_inspection_remarks'                => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['validationHasError' => 1, 'error' => $validator->messages()]);
        } else {
            DB::beginTransaction();
            try {
                $add_update_oqc_inspection =[
                    'production_runcard_id'     => $request->prod_id,
                    'po_no'                     => $request->oqc_inspection_po_no,
                    'ww'                        => $request->oqc_inspection_work_week,
                    'fy'                        => $request->oqc_inspection_fiscal_year,
                    'date_inspected'            => $request->oqc_inspection_date_inspected,
                    'time_ins_from'             => $request->oqc_inspection_time_inspected_from,
                    'time_ins_to'               => $request->oqc_inspection_time_inspected_to,
                    'submission'                => $request->oqc_inspection_submission,
                    'sample_size'               => $request->oqc_inspection_sample_size,
                    'num_of_defects'            => $total,
                    'yield'                     => $yield,
                    'judgement'                 => $request->oqc_inspection_judgement,
                    'inspector'                 => $request->oqc_inspection_inspector,
                    'remarks'                   => $request->oqc_inspection_remarks,
                    'shift'                     => $request->oqc_inspection_shift,
                    'machine_no'             => $request->oqc_inspection_machine_no,
                    'app_date'                  => $request->oqc_inspection_application_date,
                    'app_time'                  => $request->oqc_inspection_application_time,
                    'prod_category'             => $request->oqc_inspection_product_category,
                    'customer'                  => $request->oqc_inspection_customer,
                    'family'                    => $request->oqc_inspection_family,
                    'type_of_inspection'        => $request->oqc_inspection_inspection_type,
                    'severity_of_inspection'    => $request->oqc_inspection_inspection_severity,
                    'inspection_lvl'            => $request->oqc_inspection_inspection_level,
                    'aql'                       => $request->oqc_inspection_aql,
                    'accept'                    => $request->oqc_inspection_accept,
                    'reject'                    => $request->oqc_inspection_reject,
                    'coc_req'                   => $request->oqc_inspection_coc_requirement,
                    'lot_inspected'             => $request->oqc_inspection_lot_inspected,
                    'lot_accepted'              => $request->oqc_inspection_lot_accepted,
                    'update_user'               => $request->employee_no,
                    'created_at'               => date('Y-m-d H:i:s'),
                ];
                $getID = OqcInspection::insertGetId(
                    $add_update_oqc_inspection
                );

                if ($request->print_lot_no_0 != null && $request->print_lot_qty_0 != null) {
                    for($print_lot_counter = 0; $print_lot_counter <= $request->print_lot_counter; $print_lot_counter++) {
                        $add_print_lot['oqc_inspection_id'] = $getID;
                        $add_print_lot['counter']  = $print_lot_counter;
                        $add_print_lot['print_lot_no']  = $request->input("print_lot_no_$print_lot_counter");
                        $add_print_lot['print_lot_qty'] = $request->input("print_lot_qty_$print_lot_counter");

                        OqcInspectionPrintLot::insert(
                            $add_print_lot
                        );
                    }
                }

                if ($request->reel_lot_no_0 != null && $request->reel_lot_qty_0 != null) {
                    for($reel_lot_counter = 0; $reel_lot_counter <= $request->reel_lot_counter; $reel_lot_counter++) {
                        $add_reel_lot['oqc_inspection_id'] = $getID;
                        $add_reel_lot['counter']  = $reel_lot_counter;
                        $add_reel_lot['reel_lot_no']  = $request->input("reel_lot_no_$reel_lot_counter");
                        $add_reel_lot['reel_lot_qty'] = $request->input("reel_lot_qty_$reel_lot_counter");

                        OqcInspectionReelLot::insert(
                            $add_reel_lot
                        );
                    }
                }

                if ($request->mod_0 != null && $request->mod_qty_0 != null) {
                    for($mod_counter = 0; $mod_counter <= $request->mod_counter; $mod_counter++) {
                        $add_mod['oqc_inspection_id'] = $getID;
                        $add_mod['counter']  = $mod_counter;
                        $add_mod['mod']  = $request["mod_$mod_counter"];
                        $add_mod['mod_qty'] = $request->input("mod_qty_$mod_counter");

                        OqcInspectionModeOfDefect::insert(
                            $add_mod
                        );
                    }
                }

                DB::commit();
                return response()->json(['hasError' => 0]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['hasError' => 1, 'exceptionError' => $e->getMessage()]);
            }
        }
    }

    public function prodnRuncardDetails(Request $request){
        date_default_timezone_set('Asia/Manila');

        $get_production_runcard_details = ProductionRuncard::where('po_number', $request->getPoNo)->whereNull('deleted_at')->orderBy('id', 'DESC')->first();

        return response()->json([
            'getProductionRuncardDetails'    =>  $get_production_runcard_details,
        ]);
    }

    public function getFamily(){
        $collect_family = DropdownOqcFamily::orderBy('family', 'ASC')->where('logdel', 0)->get();
        return response()->json(['collectFamily' => $collect_family]);
    }

    public function getInspectionType(){
        $collect_inspection_type = DropdownOqcInspectionType::orderBy('inspection_type', 'ASC')->where('logdel', 0)->get();
        return response()->json(['collectInspectionType' => $collect_inspection_type]);
    }

    public function getInspectionLevel(){
        $collect_inspection_level = DropdownOqcInspectionLevel::orderBy('inspection_level', 'ASC')->where('logdel', 0)->get();
        return response()->json(['collectInspectionLevel' => $collect_inspection_level]);
    }

    public function getSeverityInspection(){
        $collect_severity_inspection = DropdownOqcSeverityInspection::orderBy('severity_inspection', 'ASC')->where('logdel', 0)->get();
        return response()->json(['collectSeverityInspection' => $collect_severity_inspection]);
    }

    public function getAQL(){
        $collect_aql = DropdownOqcAql::orderBy('aql', 'ASC')->where('logdel', 0)->get();
        return response()->json(['collectAql' => $collect_aql]);
    }

    public function getMOD(){
        $collect_mod = DropdownOqcInspectionMod::orderBy('mode_of_defect', 'ASC')->where('logdel', 0)->get();
        return response()->json(['collectMod' => $collect_mod]);
    }

    public function getCustomer(){
        $collect_customer = DropdownOqcInspectionCustomer::orderBy('customer', 'ASC')->where('logdel', 0)->get();
        return response()->json(['collectCustomer' => $collect_customer]);
    }

    public function getOqcInspectionById(Request $request){
        date_default_timezone_set('Asia/Manila');

        $get_inspector = Auth::user();
        $date_today = Carbon::today();

        $get_production_runcard_data = ProductionRuncard::where('id', $request->getProdId)
        ->get();

        $get_oqc_inspection_data = OqcInspection::with([
            'reel_lot_oqc_inspection_details',
            'print_lot_oqc_inspection_details',
            'mod_oqc_inspection_details'
        ])
        ->where('id', $request->getOqcId)
        ->where('logdel', 0)
        ->get();

        if ($date_today->month >= 4) {
            $start_fiscal_year = Carbon::create($date_today->year, 4, 1);
            $end_fiscal_year = Carbon::create($date_today->year + 1, 3, 31);
            $fiscal_year = $date_today->year;
        } else {
            $start_fiscal_year = Carbon::create($date_today->year - 1, 4, 1);
            $end_fiscal_year = Carbon::create($date_today->year, 3, 31);
            $fiscal_year = $date_today->year - 1;
        }
    
        $fy_ww = $date_today->diffInDays($start_fiscal_year);
        $work_week = intdiv($fy_ww, 7);

        return response()->json([
            'getInspector'              => $get_inspector,
            'getOqcInspectionData'      => $get_oqc_inspection_data,
            'getProductionRuncardData'  => $get_production_runcard_data,
            'fiscalYear'                => $fiscal_year,
            'workWeek'                  => $work_week
        ]);
    }

    public function scanUserId(Request $request){
        date_default_timezone_set('Asia/Manila');

        $user_details = User::where('employee_id', $request->user_id)->whereIn('position', [0,2,5])->first();
        return response()->json(['userDetails' => $user_details]);
    }

}
