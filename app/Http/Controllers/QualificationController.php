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

use App\Models\ProductionRuncard;
use App\Models\ProductionRuncardStation;
use App\Models\QualificationDetail;
use App\Models\QualificationDetailsMod;
use App\Models\User;

class QualificationController extends Controller
{
    //================================== VIEW IPQC DATA IN DATATABLES =====================================
    public function ViewIpqcData(Request $request){

        $user_details = User::where('id', Auth::user()->id)->first();
        $position = $user_details->position;
        $auth_prod = [1,4,12,13,14];
        $auth_qc = [2,5];
        $auth_engr = [9,11,17,18,19];

        if($position == 0){
            $process_status = [1,2,3,4];
        }else if(in_array($position,$auth_prod)){
            $process_status = [1,4];
        }else if(in_array($position,$auth_qc)){
            $process_status = [2,4];
        }else if(in_array($position,$auth_engr)){
            $process_status = [3,4];
        }else{
            $process_status = [4];
        }

        $view_quali_data = DB::table('qualification_details AS quali')->select('quali.*', 'prod_user.firstname AS prod_fname', 'prod_user.lastname AS prod_lname', 'qc_user.firstname AS qc_fname', 'qc_user.lastname AS qc_lname', 'runcard.po_number', 'runcard.production_lot', 'runcard.part_name')
                            ->leftJoin('production_runcards AS runcard', 'quali.fk_prod_runcard_id', '=', 'runcard.id')
                            ->leftJoin('users AS prod_user', 'quali.prod_name', '=', 'prod_user.id')
                            ->leftJoin('users AS qc_user', 'quali.prod_name', '=', 'qc_user.id')
                            // ->when($request->part_name, function ($query) use ($request){
                            //     return $query ->where('runcard.part_name', $request->part_name)
                            //                   ->whereNull('runcard.deleted_at');
                            // })
                            ->where('runcard.part_name', $request->part_name)
                            ->whereNull('runcard.deleted_at')
                            ->whereIn('quali.status', $request->status)
                            // ->whereIn('quali.process_status', $process_status)
                            ->where('quali.logdel', 0)
                            ->get();

        return DataTables::of($view_quali_data)
        ->addColumn('action', function($view_quali_data) use($position,$auth_prod,$auth_qc,$auth_engr){

            $action_btn_update = "<button class='btn btn-primary btn-sm btnUpdateQualiData' ipqc_data-id='$view_quali_data->id'>
                                  <i class='fa-solid fa-microscope' data-bs-html='true' title='Proceed to IPQC Inspection'></i></button>";

            $action_btn_submit = "<button class='btn btn-success btn-sm btnSubmitIPQCData' ipqc_data-id='$view_quali_data->id'>
                                  <i class='fa-solid fa-circle-check' data-bs-html='true' title='Proceed to Mass Production'></i></button>";

            $action_btn_view = "<button class='btn btn-info btn-sm btnViewQualiData' ipqc_data-id='$view_quali_data->id'>
                                  <i class='fa-solid fa-eye' data-bs-html='true' title='View IPQC Inspection'></i></button>";

            $result = "";
            $result .= "<center>";
            // $result .= "<button class='btn btn-info btn-sm btnViewQualiData' ipqc_data-id='$view_quali_data->id'>
            //             <i class='fa-solid fa-eye' data-bs-html='true' title='View IPQC Inspection'></i></button>";

            // switch ($view_quali_data->process_status) {
            //     case '1':
                // if($position == 0 || in_array($position,$auth_prod)){
                    $result .= $action_btn_view;
                    $proc_status = $view_quali_data->process_status;
                    if($view_quali_data->status < 3){ //Not Exsisting IPQC ID or Status less than 3(0 - Pending, 1,2 - Updated): Enabled Updating
                        if($position == 0 || ($proc_status == 1 && in_array($position,$auth_prod)) ||
                        ($proc_status == 2 && in_array($position,$auth_qc)) || ($proc_status == 3 && in_array($position,$auth_engr))){
                            $result .= "&nbsp";
                            $result .= $action_btn_update;
                        }
                    }else if($view_quali_data->status == 5){ //Exsisting IPQC ID & Status 5(For Resetup): Enabled Updating
                        if($position == 0 || ($proc_status == 1 && in_array($position,$auth_prod)) ||
                        ($proc_status == 2 && in_array($position,$auth_qc)) || ($proc_status == 3 && in_array($position,$auth_engr))){
                            $result .= "&nbsp";
                            $result .= $action_btn_update;
                        }
                    }

                    if($view_quali_data->status == 1 || $view_quali_data->status == 2){ //Exsisting IPQC ID & Status 1(Accepted): Ready to Submit
                        if($position == 0 || ($proc_status == 1 && $view_quali_data->prod_actual_sample_result != NULL && in_array($position,$auth_prod)) ||
                        ($proc_status == 2 && $view_quali_data->qc_actual_sample_result != NULL && in_array($position,$auth_qc)) ||
                        ($proc_status == 3 && $view_quali_data->engr_ct_height_data != NULL && in_array($position,$auth_engr))){
                            $result .= "&nbsp";
                            $result .= $action_btn_submit;
                        }
                    }
                    // else if($view_quali_data->status == 2){ //Exsisting IPQC ID & Status 2(Rejected): Ready to Submit
                    //     if($position == 0 || ($proc_status == 1 && in_array($position,$auth_prod)) ||
                    //     ($proc_status == 2 && in_array($position,$auth_qc)) ||($proc_status == 3 && in_array($position,$auth_engr))){
                    //         $result .= "&nbsp";
                    //         $result .= $action_btn_submit;
                    //     }
                    // }
                // }else{
                //     $result .= $action_btn_view;
                // }

            //         break;
            //     case '2':
            //         # code...
            //         break;
            //     case '3':
            //         # code...
            //         break;
            //     default:
            //         # code...
            //         break;
            // }

            $result .= "</center>";
            return $result;
        })
        ->addColumn('quali_status', function ($view_quali_data) {
            $result = "";

            switch($view_quali_data->status){
                case 0: //Default Value: Not Yet Inpected or Inserted Data But Not Updated = Not Ready
                    $result .= '<center><span class="badge badge-pill badge-info">For Qualification</span></center>';
                    break;
                case $view_quali_data->status == 1 || $view_quali_data->status == 2: //Updated:(J)Accepted
                    switch($view_quali_data->process_status){
                        case 1:
                            $result .= '<center><span class="badge badge-pill badge-primary">For Submission</span></center>';
                            break;
                        case 2:
                            $result .= '<center><span class="badge badge-pill badge-primary">For QC Input</span></center>';
                            break;
                        case 3:
                            if($view_quali_data->qc_ct_height_data == 3){
                                $result .= '<center><span class="badge badge-pill badge-primary">For Submission</span></center>';
                            }else{
                                $result .= '<center><span class="badge badge-pill badge-primary">For ENGR Input</span></center>';
                            }
                            break;
                    }
                    break;
                // case 2: //Updated:(J)Rejected
                //     $result .= '<center><span class="badge badge-pill badge-warning">Updated:NG, for Submission</span></center>';
                //     break;
                case 3: //Completed IPQC Inspection
                    $result .= '<center><span class="badge badge-pill badge-success">Done Qualification</span></center>';
                    break;
                case 4: //Completed IPQC Inspection
                    $result .= '<center><span class="badge badge-pill badge-warning">For Re-Setup</span></center>';
                    break;
                case 5: //Completed IPQC Inspection
                    $result .= '<center><span class="badge badge-pill badge-info">For Re-Qualification</span></center>';
                    break;
            }
            return $result;
        })
        ->addColumn('process_status', function ($view_quali_data) {
            $result = "";

            switch($view_quali_data->process_status){
                case 1:
                    $result .= '<center><span class="badge badge-pill badge-info">Production</span></center>';
                    break;
                case 2:
                    $result .= '<center><span class="badge badge-pill badge-info">QC</span></center>';
                    break;
                case 3:
                    if($view_quali_data->qc_ct_height_data == 3){
                        $result .= '<center><span class="badge badge-pill badge-info">QC2</span></center>';
                    }else{
                        $result .= '<center><span class="badge badge-pill badge-info">ENGR</span></center>';
                    }
                    break;
                case 4:
                    $result .= '<center><span class="badge badge-pill badge-info">Completed</span></center>';
                    break;
            }
            return $result;
        })
        ->addColumn('request_created_at', function ($view_quali_data) {
            $result = "";
            $result = Carbon::parse($view_quali_data->created_at);
            return $result;
        })
        ->addColumn('judgement', function ($view_quali_data) {
            $result = "";
                if($view_quali_data->qc_actual_sample_result == 1){
                    $result .= "<center><span class='badge badge-pill badge-success'>OK</span></center>";
                }else if($view_quali_data->qc_actual_sample_result == 2){
                    $result .= "<center><span class='badge badge-pill badge-warning'>NG</span></center>";
                }else{
                    $result .= "<center><span class='badge badge-pill badge-primary'>---</span></center>";
                }
            return $result;
        })
        // ->addColumn('ipqc_inspector_name', function ($view_quali_data) {
        //     $result = "";
        //         $result = $view_quali_data->ipqc_insp_name->firstname.' '.$view_quali_data->ipqc_insp_name->lastname;
        //     return $result;
        // })
        // ->addColumn('ipqc_document_no', function ($view_quali_data) {
        //     $result = "";
        //         $result = $view_quali_data->document_no;
        //     return $result;
        // })
        // ->addColumn('ipqc_measdata_attachment', function ($view_quali_data) {
        //     $result = "";
        //         $result = $view_quali_data->measdata_attachment;
        //     return $result;
        // })
        ->addColumn('inspected_date', function ($view_quali_data) {
            $result = "";
                $result = $view_quali_data->updated_at;
            return $result;
        })
        // ->rawColumns(['action','quali_status','request_created_at','judgement','ipqc_inspector_name','ipqc_document_no','ipqc_measdata_attachment','ipqc_inspected_date'])
        ->rawColumns(['action','quali_status','process_status','request_created_at','judgement','inspected_date'])
        ->make(true);
    }

    // ###################################### COMMON FUNCTIONS FOR IPQC #################################### //
    //================================= GET ASSEMBLY DEVICE NAME FOR FILTERING =========================
    public function GetDevicesFromQualifications(Request $request){
        $quali_part_name = DB::connection('mysql')
                            ->table('qualification_details AS quali')
                            ->select('runcard.part_name')
                            ->join('production_runcards AS runcard', 'quali.fk_prod_runcard_id', '=', 'runcard.id')
                            ->whereNull('runcard.deleted_at')
                            ->whereNull('quali.deleted_at')
                            ->where('quali.logdel', 0)
                            ->distinct()
                            ->get();

        // return $quali_part_name;
        // $quali_part_name = QualificationDetail::with('prod_runcard_details')
        //                                 // ->distinct()
        //                                 // ->where('process_category', $request->process_category)
        //                                 ->where('logdel', 0)
        //                                 ->get();

        // $collection = collect($quali_part_name)->distinct();
        // return $quali_part_name;

        // $part_name = '';
        // if(isset($quali_part_name[0]->prod_runcard_details)){
        //     $part_name = $quali_part_name;
        // }

        return response()->json(['quali_part_name' => $quali_part_name]);
    }

    //================================= VERIFY PRODUCTION LOT =========================
    public function VerifyProductionLot(Request $request){
        $result = ProductionRuncard::select('id', 'production_lot')->where('part_name', $request->part_name)
                                    ->where('production_lot', $request->production_lot)
                                    ->whereNull('deleted_at')
                                    ->get();

        if($result->isEmpty()){
        // if($result->isEmpty()){
            $production_lot = '';
        }else{
            $id = $result[0]->id;
            $production_lot = $result[0]->production_lot;
        }

        // else if($request->process_category == 1){
        //     $production_lot = $result[0]->production_lot .''. $result[0]->production_lot_extension;
        // }else{
        //     $production_lot = $result[0]->production_lot;
        // }

        return response()->json(['prod_runcard_id' => $id, 'production_lot' => $production_lot]);
    }

    //================================= GET IPQC BY MATERIAL NAME/ID DATA ==================================
    public function GetQualificationsData(Request $request){

        $quali_data = DB::table('qualification_details AS quali')->select('quali.*', 'prod_user.firstname AS prod_fname', 'prod_user.lastname AS prod_lname', 'qc_user.firstname AS qc_fname', 'qc_user.lastname AS qc_lname', 'runcard.po_number', 'runcard.po_quantity', 'runcard.production_lot', 'runcard.part_name', 'runcard.part_code')
                                ->leftJoin('production_runcards AS runcard', 'quali.fk_prod_runcard_id', '=', 'runcard.id')
                                ->leftJoin('users AS prod_user', 'quali.prod_name', '=', 'prod_user.id')
                                ->leftJoin('users AS qc_user', 'quali.qc_name', '=', 'qc_user.id')
                                // ->leftJoin('qualification_details_mods AS quali_mod', 'quali.id', '=', 'quali_mod.quali_details_id')
                                ->when($request->part_name, function ($query) use ($request){
                                    return $query ->where('runcard.part_name', $request->part_name)
                                                ->whereNull('runcard.deleted_at');
                                })
                                ->when($request->quali_id, function ($query) use ($request){
                                    return $query ->where('quali.id', $request->quali_id);
                                })
                                ->where('quali.logdel', 0)
                                ->get();
        // return $quali_data;

        // $quali_data = QualificationDetail::with('ipqc_insp_name')
        //                                     ->when($request->part_name, function ($query) use ($request){
        //                                         return $query ->where('part_name', $request->part_name);
        //                                     })
        //                                     ->when($request->ipqc_id, function ($query) use ($request){
        //                                         return $query ->where('id', $request->ipqc_id);
        //                                     })
        //                                     ->where('logdel', 0)
        //                                     ->get();
        if(isset($request->quali_id)){
            $quali_mod_prod =  QualificationDetailsMod::with(['mode_of_defect'])->where('quali_details_id', $request->quali_id)
            ->whereNull('deleted_at')
            ->where('process_status', 1)
            ->get();

            $quali_mod_qc =  QualificationDetailsMod::with(['mode_of_defect'])->where('quali_details_id', $request->quali_id)
            ->whereNull('deleted_at')
            ->where('process_status', 2)
            ->get();

            // $quali_mod_prod = $mode_of_defect_data->where('process_status', 1);
            // $quali_mod_qc = $mode_of_defect_data->where('process_status', 2);
        }else{
            $mode_of_defect_data = [];
            $quali_mod_prod = [];
            $quali_mod_qc = [];
        }

        return response()->json(['quali_data' => $quali_data, 'quali_mod_prod' => $quali_mod_prod, 'quali_mod_qc' => $quali_mod_qc,]);
    }

    //====================================== ADD/UPDATE IPQC DATA FOR FIRST MOLDING =========================
    public function AddQualificationDetails(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();
        $data = $request->all();
        // return $data;
        if($request->quali_details_id == 0){
            $validator = Validator::make($data, [
                    'production_lot' => 'required',
                    'category' => 'required',
                    'quali_prod_date' => 'required',
                    'quali_prod_input_qty' => 'required',
                    'quali_prod_output_qty' => 'required',
                    'quali_prod_judgement' => 'required',
                    'quali_prod_actual_sample' => 'required',
                //     'uploaded_file' => 'required',
            ]);

            if ($validator->fails()){
                return response()->json(['validation' => 'hasError', 'error' => $validator->messages()]);
            }else{
                // $original_filename = $request->file('uploaded_file')->getClientOriginalName();
                // clark comment 09052024
                // if($request->quali_prod_judgement == "1"){
                //     $status = 1;
                // }else if($request->quali_prod_judgement == "2"){
                //     $status = 2;
                // }
                // clark comment 09052024

                // Storage::putFileAs('public/molding_assy_ipqc_insp_files', $request->uploaded_file,  $original_filename);

                // if($request->process_status == '1' && $request->sub_station_step == '2'){
                    $inserted_quali_id = QualificationDetail::insertGetId([
                        'fk_prod_runcard_id'         => $request->prod_runcard_id,
                        'prod_date'                  => $request->quali_prod_date,
                        'prod_name'                  => Auth::user()->id,
                        'prod_category'              => $request->category,
                        'prod_input_qty'             => $request->quali_prod_input_qty,
                        'prod_output_qty'            => $request->quali_prod_output_qty,
                        'prod_ng_qty'                => $request->quali_prod_ng_qty,
                        'prod_mode_of_defect'        => $request->prod_mode_of_defect,
                        'prod_actual_sample_result'  => $request->quali_prod_judgement,
                        'prod_actual_sample_used'    => $request->quali_prod_actual_sample,
                        'prod_actual_sample_remarks' => $request->quali_prod_remarks,
                        // 'process_status'             => $request->process_status, // clark old status 01/08/2025
                        // 'status'                     => $request->quali_prod_judgement, // clark old status 01/08/2025
                        'process_status'             => 2, //clark new status 01/08/2025
                        'status'                     => 1, //clark new status 01/08/2025
                        'created_by'                 => Auth::user()->id,
                        'last_updated_by'            => Auth::user()->id,
                        'created_at'                 => date('Y-m-d H:i:s'),
                        'updated_at'                 => date('Y-m-d H:i:s')
                    ]);
                // }

                $process_status = $request->process_status;
                // DB::commit();
                // return response()->json(['result' => 'Insert Successful']);
            }
        }else{
            if($request->process_status == 1){
                QualificationDetail::where('id', $request->quali_details_id)
                ->update([
                    'fk_prod_runcard_id'         => $request->prod_runcard_id,
                    'prod_category'              => $request->category,
                    'prod_date'                  => $request->quali_prod_date,
                    'prod_name'                  => Auth::user()->id,
                    'prod_input_qty'             => $request->quali_prod_input_qty,
                    'prod_output_qty'            => $request->quali_prod_output_qty,
                    'prod_ng_qty'                => $request->quali_prod_ng_qty,
                    'prod_mode_of_defect'        => $request->prod_mode_of_defect,
                    'prod_actual_sample_result'  => $request->quali_prod_judgement,
                    'prod_actual_sample_used'    => $request->quali_prod_actual_sample,
                    'prod_actual_sample_remarks' => $request->quali_prod_remarks,
                    'process_status'             => $request->process_status,
                    // 'status'                     => $status,
                    'created_by'                 => Auth::user()->id,
                    'last_updated_by'            => Auth::user()->id,
                    'created_at'                 => date('Y-m-d H:i:s'),
                    'updated_at'                 => date('Y-m-d H:i:s')
                ]);

                $process_status = $request->process_status;

            }else if($request->process_status == 2){

                if(isset($request->defect_checkpoint)){
                    $defect_checkpoint = [];
                    foreach ($request->defect_checkpoint as $defects) {
                        array_push($defect_checkpoint, $defects);
                    }
                    $defect_checkpoint = implode(',', $defect_checkpoint);
                }else{
                    $defect_checkpoint = '';
                }

                // if($request->ct_height_data_qc == 3){
                //     $process_status = 3;
                // }else{
                //     $process_status = $request->process_status;
                // }

                QualificationDetail::where('id', $request->quali_details_id)
                ->update([
                    'qc_date'                  => $request->quali_qc_date,
                    'qc_name'                  => Auth::user()->id,
                    'qc_input_qty'             => $request->quali_qc_input_qty,
                    'qc_output_qty'            => $request->quali_qc_output_qty,
                    'qc_ng_qty'                => $request->quali_qc_ng_qty,
                    'qc_mode_of_defect'        => $request->qc_mode_of_defect,
                    'qc_actual_sample_result'  => $request->quali_qc_judgement,
                    'qc_actual_sample_used'    => $request->quali_qc_actual_sample,
                    'qc_actual_sample_remarks' => $request->quali_qc_remarks,

                    'qc_ct_height_data'        => $request->ct_height_data_qc,
                    'defect_checkpoints'       => $defect_checkpoint,
                    'defect_remarks'           => $request->defect_checkpoint_remarks,

                    // 'process_status'           => $process_status,
                    'process_status'           => $request->process_status,

                    // 'status'                     => $status,
                    'created_by'                 => Auth::user()->id,
                    'last_updated_by'            => Auth::user()->id,
                    'created_at'                 => date('Y-m-d H:i:s'),
                    'updated_at'                 => date('Y-m-d H:i:s')
                ]);
            }else{

                // if($request->ct_height_data_qc == 3){
                //     $process_status = 3;
                // }else{
                //     $process_status = 2;
                // }

                QualificationDetail::where('id', $request->quali_details_id)
                ->update([
                    'engr_ct_height_data' => $request->ct_height_data_engr,
                    'engr_ct_height_data_remarks' => $request->ct_height_data_remarks,

                    // 'process_status' => $process_status,

                    // 'status'                     => $status,
                    'created_by'                 => Auth::user()->id,
                    'last_updated_by'            => Auth::user()->id,
                    'created_at'                 => date('Y-m-d H:i:s'),
                    'updated_at'                 => date('Y-m-d H:i:s')
                ]);

                    $process_status = $request->process_status;
            }

            // QualificationDetail::where('id', $request->ipqc_id)
            //         ->update([
            //             'po_number'               => $request->po_number,
            //             'part_code'               => $request->part_code,
            //             'material_name'           => $request->material_name,
            //             'production_lot'          => $request->production_lot,
            //             'judgement'               => $request->judgement,
            //             'qc_samples'              => $request->qc_samples,
            //             'ok_samples'              => $request->ok_samples,
            //             'ipqc_inspector_name'     => $request->inspector_id,
            //             'keep_sample'             => $request->keep_sample,
            //             'doc_no_b_drawing'        => $request->doc_no_b_drawing,
            //             'doc_no_insp_standard'    => $request->doc_no_inspection_standard,
            //             'doc_no_urgent_direction' => $request->doc_no_ud,
            //             'measdata_attachment'     => $original_filename,
            //             'status'                  => $status,
            //             'remarks'                 => $request->remarks,
            //             'last_updated_by'         => Auth::user()->id,
            //             'updated_at'              => date('Y-m-d H:i:s'),
            //         ]);
        }

        // if($process_status != 3 && $process_status != 4){

            if($request->quali_details_id == 0){
                $qualifications_id = $inserted_quali_id;
            }else{
                $qualifications_id = $request->quali_details_id;
            }

            if(isset($request->mod_id)){
                $is_id_deleted = QualificationDetailsMod::where('quali_details_id', $qualifications_id)->where('process_status', $request->process_status)->delete();

                // return $request->mod_id;
                foreach ($request->mod_id as $key => $value) {
                    QualificationDetailsMod::insert([
                        'quali_details_id'             => $qualifications_id,
                        // 'prod_runcard_stations_id' => $request->frmstations_runcard_station_id,
                        // 'mode_of_defects'              => $request->mod_id[$key],
                        'process_status'               => $request->process_status,
                        'mod_id'                       => $request->mod_id[$key],
                        'mod_quantity'                 => $request->mod_quantity[$key],
                        'created_by'                   => Auth::user()->id,
                        'last_updated_by'              => Auth::user()->id,
                        'created_at'                   => date('Y-m-d H:i:s'),
                        'updated_at'                   => date('Y-m-d H:i:s'),
                    ]);
                }

            }else{
                //
                if(QualificationDetailsMod::where('quali_details_id', $request->quali_details_id)->exists()){
                    $is_id_deleted = QualificationDetailsMod::where('quali_details_id', $request->quali_details_id)->where('process_status', $process_status)->delete(); //returns true/false
                }
            }
        // }

        DB::commit();
        return response()->json(['result' => 'Successful!']);
    }

    //====================================== UPDATE IPQC STATUS FOR FIRST MOLDING =========================
    public function UpdateQualificationDetailsStatus(Request $request){
        date_default_timezone_set('Asia/Manila');
            if($request->cnfrm_quali_process_status == 1){
                //For Mass Production
                $quali_status = 1;
                $quali_process_status = 2;
            }else if($request->cnfrm_quali_process_status == 2){
                $quali_data = QualificationDetail::where('id', $request->cnfrm_quali_id)
                                                ->whereNull('deleted_at')
                                                ->first();
                // return $quali_data;

                if($quali_data->qc_ct_height_data == 3){
                    $quali_status = 3;
                    $quali_process_status = 4;

                    QualificationDetail::where('id', $request->cnfrm_quali_id)
                                ->update([
                                    'engr_ct_height_data'           => 3, // N/A
                                    'engr_ct_height_data_remarks'   => 'N/A',
                                ]);

                }else{
                    $quali_status = 1;
                    $quali_process_status = 3;
                }

            }else if($request->cnfrm_quali_process_status == 3){
                $quali_status = 3;
                $quali_process_status = 4;
            }

            QualificationDetail::where('id', $request->cnfrm_quali_id)
                    ->update([
                        'status'                        => $quali_status,
                        'process_status'                => $quali_process_status,
                        'last_updated_by'               => Auth::user()->id,
                        'updated_at'                    => date('Y-m-d H:i:s'),
                    ]);

            DB::commit();
        return response()->json(['result' => 'Successful']);
    }

    public function ValidateUser(Request $request){
        $user = User::where('employee_id', $request->id)
                    ->where('status', 1)
                    ->whereIn('position', $request->pos)
                    ->first();

        if(isset($user)){
            return response()->json(['result' => 1]);
        }
        else{
            return response()->json(['result' => 2]);
        }
    }

    //====================================== DOWNLOAD FILE ======================================
    // public function DownloadFile(Request $request, $id){
    //     $ipqc_data_for_download = QualificationDetail::where('id', $id)->first();
    //     $file =  storage_path() . "/app/public/molding_assy_ipqc_insp_files/" . $ipqc_data_for_download->measdata_attachment;
    //     return Response::download($file, $ipqc_data_for_download->measdata_attachment);
    // }

}
