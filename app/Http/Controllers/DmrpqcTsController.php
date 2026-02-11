<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use DataTables;

use Carbon\Carbon;
use App\Models\DmrpqcProductIdentification;
use App\Models\DmrpqcDiesetCondition;
use App\Models\DmrpqcDiesetConditionChecking;
use App\Models\DmrpqcMachineSetup;
use App\Models\DmrpqcProductReqChecking;
use App\Models\DmrpqcProductReqCheckingDetails;
use App\Models\DmrpqcMachineSetupSample;
use App\Models\DmrpqcMachineParameterChecking;
use App\Models\DmrpqcSpecification;
use App\Models\DmrpqcCompletionActivity;
use App\Models\User;

class DmrpqcTsController extends Controller
{
    public function GetUsersByPosition(Request $request){
        // return $request->position;
        // $users = User::where('position', $request->position)->where('status', 1)->get();
        //
        // return $users;

        $users = DB::connection('mysql')->select('SELECT * FROM users WHERE position IN ('.$request->position.') AND status = 1');

        // return $users;

        return response()->json(['users' => $users]);
        // $pps_db_details = DB::connection('mysql_rapid_pps')
        // ->select(' SELECT po_receive.ItemName AS part_name, po_receive.ItemCode AS part_code, po_receive.OrderNo AS po_number, po_receive.OrderQty AS po_qty, dieset.DieNo AS die_no, dieset.DrawingNo AS drawing_no, dieset.Rev AS drawing_rev
        //     FROM tbl_POReceived AS po_receive
        //     LEFT JOIN tbl_dieset AS dieset ON po_receive.ItemCode = dieset.R3Code
        //     WHERE OrderNo = "'.$request->po_number.'"
        //     ');
    }

    public function ViewDmrpqc(Request $request){
        session_start();
        date_default_timezone_set('Asia/Manila');
        $monthNow = Carbon::now()->format('m');

        $user_details = User::where('id', Auth::user()->id)->first();
        // return $user_details;
        $position = $user_details->position;

        // return $position;
        if($position == 0 || $position == 17){ //ADMIN
            $process_status_by_position = [1,2,3,4,5,6,7,8,9];
            $prod_req_checking_stat_by_pos = [0,1,2,3,4];
            $mach_param_stat_by_pos = [0,1,2];
        }else if($position == 1 || $position == 4 || $position == 12 || $position == 13 || $position == 14 || $position == 17){ //PRODUCTION
            $process_status_by_position = [1,4,5,6,8,9];
            $prod_req_checking_stat_by_pos = [0];
            $mach_param_stat_by_pos = [0,1];
        }else if($position == 2 || $position == 5){ //QC
            $process_status_by_position = [5,6,7,9];
            $prod_req_checking_stat_by_pos = [2];
            $mach_param_stat_by_pos = [2];
        }else if($position == 9 || $position == 11 || $position == 15 || $position == 16 || $position == 17){ //ENGR
            $process_status_by_position = [2,3,4,5,6,7,8,9];
            $prod_req_checking_stat_by_pos = [1,3,4];
            $mach_param_stat_by_pos = [0,1];
        }else{
            $process_status_by_position = [1,2,3,4,5,6,7,8,9];
            $mach_param_stat_by_pos = [0,1,2];
        }
        // switch($position){
        //     case 0: //ADMIN
        //         $process_status_by_position = [1,2,3,4,5,6,7,8,9];
        //         $prod_req_checking_stat_by_pos = [0,1,2,3,4];
        //         break;
        //     // case 1:
        //     case $position == 1 || $position == 4 || $position == 12 || $position == 13 || $position == 14 || $position == 17: //PRODUCTION
        //         $process_status_by_position = [1,4,5,8,9];
        //         $prod_req_checking_stat_by_pos = [0];
        //         break;
        //     case $position == 2 || $position == 5: //QC
        //         $process_status_by_position = [5,6,7,9];
        //         $prod_req_checking_stat_by_pos = [2];
        //         break;
        //     case $position == 9 || $position == 11 || $position == 15 || $position == 16 || $position == 17: // ENGR
        //         $process_status_by_position = [2,3,4,5,6,7,8,9];
        //         $prod_req_checking_stat_by_pos = [1,3,4];
        //         break;
        //     default:
        //         $process_status_by_position = [1,2,3,4,5,6,7,8,9];
        // }
        // return $process_status_by_position;
        // return $process_status_by_position;

        // $dmrpqc_details = DmrpqcProductIdentification::with(['prod_req_checking' => function($query) use ($prod_req_checking_stat_by_pos) { $query->whereIn('status', $prod_req_checking_stat_by_pos)->where('logdel', 1); }, 'users' => function($query) { $query->select('id', DB::raw("CONCAT(firstname, ' ', lastname) AS full_name")); }])

        // ->when($request->month, function ($query) use ($request) {
        //     return $query ->where('start_date_time', 'like', '%-'.$request->month.'-%');
        //     }, function ($query) use ($monthNow) {
        //         return $query ->where('start_date_time', 'like', '%-'.$monthNow.'-%');
        // })
        // return $prod_req_checking_stat_by_pos;
        $dmrpqc_details = DB::table('dmrpqc_product_identifications as dmrpqc')
                        ->select('dmrpqc.*', DB::raw("CONCAT(users.firstname, ' ', users.lastname) AS full_name"), 'users.user_level_id')
                        ->leftJoin('users', 'dmrpqc.created_by', '=', 'users.id')
                        ->leftJoin('dmrpqc_product_req_checkings AS prod_req_checking', 'dmrpqc.id', '=', 'prod_req_checking.request_id')
                        ->addSelect('prod_req_checking.id AS prod_req_checking_id', 'prod_req_checking.status AS prod_req_checking_status')
                        ->leftJoin('dmrpqc_machine_parameter_checkings AS mach_param_chckng', 'dmrpqc.id', '=', 'mach_param_chckng.request_id')
                        ->addSelect('mach_param_chckng.id AS mach_param_chckng_id', 'mach_param_chckng.status AS mach_param_chckng_status')
                        // ->when((in_array(5, $process_status_by_position)), function ($query){
                        //     return $query ->leftJoin('dmrpqc_product_req_checkings AS prod_req_checking', 'dmrpqc.id', '=', 'prod_req_checking.request_id')
                        //                   ->addSelect('prod_req_checking.id AS prod_req_checking_id', 'prod_req_checking.status AS prod_req_checking_status');
                        //                 //   ->whereIn('prod_req_checking.status', $prod_req_checking_stat_by_pos)
                        //                 //   ->where('prod_req_checking.logdel', 0);
                        //     // return $query ->where('start_date_time', 'like', '%-'.$request->month.'-%');
                        // })
                        // ->when((in_array(6, $process_status_by_position)), function ($query){
                        //     return $query ->leftJoin('dmrpqc_machine_parameter_checkings AS mach_param_chckng', 'dmrpqc.id', '=', 'mach_param_chckng.request_id')
                        //                   ->addSelect('mach_param_chckng.id AS mach_param_chckng_id', 'mach_param_chckng.status AS mach_param_chckng_status');
                        // })
                        ->where('dmrpqc.category', $request->category)
                        ->when($request->month, function ($query) use ($request) {
                            return $query ->where('dmrpqc.start_date_time', 'like', '%-'.$request->month.'-%');
                        })
                        ->when($request->year, function ($query) use ($request) {
                            return $query ->where('dmrpqc.created_at', 'like', '%'.$request->year.'%');
                        })
                        ->when($request->request_type, function ($query) use ($request) {
                            return $query ->where('dmrpqc.request_type', 'like', '%'.$request->request_type.'%');
                        })
                        ->when($request->request_date_from, function ($query) use ($request) {
                            return $query ->where('dmrpqc.created_at', '>=', $request->request_date_from);
                        })
                        ->when($request->request_date_to, function ($query) use ($request) {
                            return $query ->where('dmrpqc.created_at', '<=', $request->request_date_to);
                        })
                        ->when($request->status, function ($query) use ($request) {
                            return $query ->where('dmrpqc.status', 'like', '%'.$request->status.'%');
                        })
                        // ->whereIn('dmrpqc.process_status', $process_status_by_position)
                        ->where('dmrpqc.logdel', 0)->orderBy('dmrpqc.created_at','desc')->get();

                        // return $dmrpqc_details[3]->user_level_id;
                        // return $dmrpqc_details[0]->mach_param_chckng;
        // return $dmrpqc_details;
        return DataTables::of($dmrpqc_details)
            ->addColumn('action', function ($dmrpqc_details) use ($prod_req_checking_stat_by_pos, $process_status_by_position, $mach_param_stat_by_pos){

                // $user_id            = $dmrpqc_details->users->id;
                // $user_position_id   = $dmrpqc_details->users->position;
                // $user_section_id    = $dmrpqc_details->users->section;
                $user_level_id      = $dmrpqc_details->user_level_id;

                        // $result = "";
                        // $result .= '<button class="btn btn-sm btn-outline-info border-0 text-center actionViewBtn" dmrpqc_id="'.$dmrpqc_details->id.'">
                        //             <i class="fas fa-eye fa-lg" title="View"></i></button>&nbsp;';

                $action_btn_view = '<button class="btn btn-sm btn-outline-info border-0 text-center actionViewBtn" process_status="'.$dmrpqc_details->process_status.'"
                                    dmrpqc_id="'.$dmrpqc_details->id.'"><i class="fas fa-eye fa-lg" title="View"></i></button>';

                $action_btn_submit = '<button class="btn btn-sm btn-outline-success border-0 text-center actionChangeStatusBtn" process_status="'.$dmrpqc_details->process_status.'"
                                    dmrpqc_id="'.$dmrpqc_details->id.'"><i class="fa-solid fa-check-to-slot fa-xl" title="Submit"></i></button>';

                $action_btn_delete = '<button class="btn btn-sm btn-outline-danger border-0 text-center actionDeleteBtn" process_status="'.$dmrpqc_details->process_status.'"
                                    dmrpqc_id="'.$dmrpqc_details->id.'"><i class="fa-solid fa-trash-can fa-xl" title="Cancel"></i></button>';

                $action_btn_conform = '<button class="btn btn-sm btn-outline-primary border-0 text-center actionConformBtn" csrf_token="'.csrf_token().'" process_status="'.$dmrpqc_details->process_status.'"
                                    dmrpqc_id="'.$dmrpqc_details->id.'"><i class="fa-solid fa-screwdriver-wrench fa-xl" title="Conform"></i></button>';

                $action_btn_update = '<button class="btn btn-sm btn-outline-primary border-0 text-center actionUpdateBtn" process_status="'.$dmrpqc_details->process_status.'"
                                    dmrpqc_id="'.$dmrpqc_details->id.'"><i class="fa-solid fa-file-pen fa-xl" title="Update"></i></button>';

                            $result = "";
                // CLARK COMMENT 09/24/2024
                // switch($user_level_id){
                    // case 1: //Users
                        if ($dmrpqc_details->status == 0 && in_array(1, $process_status_by_position)){ //Status 0 = For Submission
                                $result .= $action_btn_submit;
                                $result .= $action_btn_delete;
                        }else if($dmrpqc_details->status == 1 && $dmrpqc_details->process_status == 2){ //For Conformance in Part 2
                            if(in_array(2, $process_status_by_position)){
                                $result .= $action_btn_conform;
                            }else{
                                $result .= $action_btn_view;
                            }
                        }else if($dmrpqc_details->status == 2 && $dmrpqc_details->process_status == 2){ //Ongoing in Part 2
                            if(in_array(2, $process_status_by_position)){
                                $result .= $action_btn_update;
                                if(DmrpqcDiesetCondition::where('request_id', $dmrpqc_details->id)->where('status', 1)->where('logdel', 0)->exists()){
                                    $result .= $action_btn_view;
                                    $result .= $action_btn_submit;
                                }
                            }else{
                                $result .= $action_btn_view;
                            }
                        }else if($dmrpqc_details->status == 1 && $dmrpqc_details->process_status == 3 && in_array(3, $process_status_by_position)){ //For Conformance in Part 3
                            if(in_array(3, $process_status_by_position)){
                                $result .= $action_btn_conform;
                            }else{
                                $result .= $action_btn_view;
                            }
                        }else if($dmrpqc_details->status == 2 && $dmrpqc_details->process_status == 3){ //Ongoing in Part 3
                            if(in_array(3, $process_status_by_position)){
                                $result .= $action_btn_update;
                                if(DmrpqcDiesetConditionChecking::where('request_id', $dmrpqc_details->id)->where('status', 1)->where('logdel', 0)->exists()){
                                    $result .= $action_btn_view;
                                    $result .= $action_btn_submit;
                                }
                            }else{
                                $result .= $action_btn_view;
                            }
                        }else if($dmrpqc_details->status == 1 && $dmrpqc_details->process_status == 4 && in_array(4, $process_status_by_position)){ //For Conformance in Part 4
                            if(in_array(4, $process_status_by_position)){
                                $result .= $action_btn_conform;
                            }else{
                                $result .= $action_btn_view;
                            }
                        }else if($dmrpqc_details->status == 2 && $dmrpqc_details->process_status == 4){ //Ongoing in Part 4
                            if(in_array(4, $process_status_by_position)){
                                    $result .= $action_btn_update;
                                if(DmrpqcMachineSetup::where('request_id', $dmrpqc_details->id)->where('status', 2)->where('logdel', 0)->exists()){
                                    $result .= $action_btn_view;
                                    $result .= $action_btn_submit;
                                }
                            }else{
                                $result .= $action_btn_view;
                            }

                        }else if($dmrpqc_details->status == 1 && $dmrpqc_details->process_status == 5 && in_array(5, $process_status_by_position)){ //For Conformance in Part 5
                            if(in_array(5, $process_status_by_position)){
                                $result .= $action_btn_conform;
                            }else{
                                $result .= $action_btn_view;
                            }
                        }else if($dmrpqc_details->status == 2 && $dmrpqc_details->process_status == 5){ //Ongoing in Part 5
                            if(in_array(5, $process_status_by_position)){
                                // $result .= $action_btn_update;
                                if(in_array(0, $prod_req_checking_stat_by_pos) && $dmrpqc_details->prod_req_checking_status == 0){
                                    $result .= $action_btn_update;
                                    // if(DmrpqcProductReqChecking::where('status', 0)->where('logdel', 0)->exists()){
                                    //     $result .= $action_btn_view;
                                    //     $result .= $action_btn_submit;
                                    //     break;
                                    // }
                                }

                                if(in_array(1, $prod_req_checking_stat_by_pos) && $dmrpqc_details->prod_req_checking_status == 1){
                                    $result .= $action_btn_update;
                                    // if(DmrpqcProductReqChecking::where('status', 1)->where('logdel', 0)->exists()){
                                    //     $result .= $action_btn_view;
                                    //     $result .= $action_btn_submit;
                                    //     break;
                                    // }
                                }
                                if(in_array(2, $prod_req_checking_stat_by_pos) && $dmrpqc_details->prod_req_checking_status == 2){
                                    $result .= $action_btn_update;
                                    // if(DmrpqcProductReqChecking::where('status', 2)->where('logdel', 0)->exists()){
                                    //     $result .= $action_btn_view;
                                    //     $result .= $action_btn_submit;
                                    //     break;
                                    // }
                                }
                                if(in_array(3, $prod_req_checking_stat_by_pos) && $dmrpqc_details->prod_req_checking_status == 3){
                                    $result .= $action_btn_update;
                                    // if(DmrpqcProductReqChecking::where('status', 3)->where('logdel', 0)->exists()){
                                    //     $result .= $action_btn_view;
                                    //     $result .= $action_btn_submit;
                                    //     break;
                                    // }
                                }
                                if(in_array(4, $prod_req_checking_stat_by_pos) && $dmrpqc_details->prod_req_checking_status == 4){
                                    $result .= $action_btn_update;
                                    if(DmrpqcProductReqChecking::where('status', 4)->where('logdel', 0)->exists()){
                                        $result .= $action_btn_view;
                                        $result .= $action_btn_submit;
                                        // break;
                                    }
                                }else{
                                    $result .= $action_btn_view;
                                    // break;
                                }
                            }else{
                                $result .= $action_btn_view;
                            }

                        }else if($dmrpqc_details->status == 1 && $dmrpqc_details->process_status == 6 && in_array(6, $process_status_by_position)){ //For Conformance in Part 6
                            $mach_param_status = $dmrpqc_details->mach_param_chckng_status;
                            // $mach_param_status = 1;
                            if(in_array(6, $process_status_by_position) && ((in_array(1, $mach_param_stat_by_pos) && $mach_param_status == 0) || (in_array(2, $mach_param_stat_by_pos) && $mach_param_status == 1))){
                                $result .= $action_btn_conform;
                            }else{
                                $result .= $action_btn_view;
                            }
                        }else if($dmrpqc_details->status == 2 && $dmrpqc_details->process_status == 6){ //Ongoing in Part 6
                            // return $dmrpqc_details->mach_param_chckng_status;
                            $mach_param_status = $dmrpqc_details->mach_param_chckng_status;
                            // $mach_param_status = 1;
                            if(in_array(6, $process_status_by_position)){
                                if((in_array(1, $mach_param_stat_by_pos) && $mach_param_status == 0) || (in_array(2, $mach_param_stat_by_pos) && $mach_param_status == 1)){
                                    $result .= $action_btn_update;
                                }else if((in_array(2, $mach_param_stat_by_pos) && $mach_param_status == 2)){
                                    // if(DmrpqcMachineParameterChecking::where('request_id', $dmrpqc_details->id)->where('status', 2)->where('logdel', 0)->exists()){
                                    $result .= $action_btn_view;
                                    $result .= $action_btn_submit;
                                    // }
                                }else{
                                    $result .= $action_btn_view;
                                }
                            }else{
                                $result .= $action_btn_view;
                            }
                        }else if($dmrpqc_details->status == 1 && $dmrpqc_details->process_status == 7 && in_array(7, $process_status_by_position)){ //For Conformance in Part 7
                            if(in_array(7, $process_status_by_position)){
                                $result .= $action_btn_conform;
                            }else{
                                $result .= $action_btn_view;
                            }
                        }else if($dmrpqc_details->status == 2 && $dmrpqc_details->process_status == 7){ //Ongoing in Part 7
                            if(in_array(7, $process_status_by_position)){
                                    $result .= $action_btn_update;
                                if(DmrpqcSpecification::where('request_id', $dmrpqc_details->id)->where('status', 1)->where('logdel', 0)->exists()){
                                    $result .= $action_btn_view;
                                    $result .= $action_btn_submit;
                                }
                            }else{
                                $result .= $action_btn_view;
                            }
                        }else if($dmrpqc_details->status == 1 && $dmrpqc_details->process_status == 8 && in_array(8, $process_status_by_position)){ //For Conformance in Part 8
                            if(in_array(8, $process_status_by_position)){
                                $result .= $action_btn_conform;
                            }else{
                                $result .= $action_btn_view;
                            }
                        }else if($dmrpqc_details->status == 2 && $dmrpqc_details->process_status == 8){ //Ongoing in Part 8
                            if(in_array(8, $process_status_by_position)){
                                    $result .= $action_btn_update;
                                if(DmrpqcCompletionActivity::where('request_id', $dmrpqc_details->id)->where('status', 1)->where('logdel', 0)->exists()){
                                    $result .= $action_btn_view;
                                    $result .= $action_btn_submit;
                                }
                            }else{
                                $result .= $action_btn_view;
                            }
                        }else if($dmrpqc_details->status == 3 && $dmrpqc_details->process_status == 9){ //Once DMRPQC is Completed enable the EXPORT button
                            $result .= '<button class="btn btn-sm btn-outline-primary border-0 text-center actionExportBtn" process_status="'.$dmrpqc_details->process_status.'"
                                        dmrpqc_id="'.$dmrpqc_details->id.'"><i class="fa-solid fa-file-pdf fa-xl" title="Export"></i></button>';
                            $result .= $action_btn_view;
                        }
                        // break;
                    // case $user_level_id == 2 || $user_level_id == 3: //ADMINISTRATOR || PPS-ADMIN
                    //     if ($dmrpqc_details->status == 0){ //Status 0 = For Submission
                    //             $result .= $action_btn_submit;
                    //             $result .= $action_btn_delete;
                    //     }
                    //     else if($dmrpqc_details->status == 1 && $dmrpqc_details->process_status == 2){ //For Conformance in Part 2
                    //             $result .= $action_btn_conform;
                    //     }else if($dmrpqc_details->status == 2 && $dmrpqc_details->process_status == 2){ //Ongoing in Part 2
                    //             $result .= $action_btn_update;
                    //         if(DmrpqcDiesetCondition::where('request_id', $dmrpqc_details->id)->where('status', 1)->where('logdel', 0)->exists()){
                    //             $result .= $action_btn_view;
                    //             $result .= $action_btn_submit;
                    //         }
                    //     }else if($dmrpqc_details->status == 1 && $dmrpqc_details->process_status == 3){ //For Conformance in Part 3
                    //             $result .= $action_btn_conform;
                    //     }else if($dmrpqc_details->status == 2 && $dmrpqc_details->process_status == 3){ //Ongoing in Part 3
                    //             $result .= $action_btn_update;
                    //         if(DmrpqcDiesetConditionChecking::where('request_id', $dmrpqc_details->id)->where('status', 1)->where('logdel', 0)->exists()){
                    //             $result .= $action_btn_view;
                    //             $result .= $action_btn_submit;
                    //         }
                    //     }else if($dmrpqc_details->status == 1 && $dmrpqc_details->process_status == 4){ //For Conformance in Part 4
                    //         $result .= $action_btn_conform;
                    //     }else if($dmrpqc_details->status == 2 && $dmrpqc_details->process_status == 4){ //Ongoing in Part 4
                    //             $result .= $action_btn_update;
                    //         if(DmrpqcMachineSetup::where('request_id', $dmrpqc_details->id)->where('status', 1)->where('logdel', 0)->exists()){
                    //             $result .= $action_btn_view;
                    //             $result .= $action_btn_submit;
                    //         }
                    //     }else if($dmrpqc_details->status == 1 && $dmrpqc_details->process_status == 5){ //For Conformance in Part 5
                    //         $result .= $action_btn_conform;
                    //     }else if($dmrpqc_details->status == 2 && $dmrpqc_details->process_status == 5){ //Ongoing in Part 5
                    //             $result .= $action_btn_update;
                    //         if(DmrpqcProductReqChecking::where('status', 4)->where('logdel', 0)->exists()){
                    //             $result .= $action_btn_view;
                    //             $result .= $action_btn_submit;
                    //         }
                    //     }else if($dmrpqc_details->status == 1 && $dmrpqc_details->process_status == 6){ //For Conformance in Part 6
                    //         $result .= $action_btn_conform;
                    //     }else if($dmrpqc_details->status == 2 && $dmrpqc_details->process_status == 6){ //Ongoing in Part 6
                    //             $result .= $action_btn_update;
                    //         if(DmrpqcMachineParameterChecking::where('request_id', $dmrpqc_details->id)->whereIn('status', [1,2])->where('logdel', 0)->exists()){
                    //             $result .= $action_btn_view;
                    //             $result .= $action_btn_submit;
                    //         }
                    //     }else if($dmrpqc_details->status == 1 && $dmrpqc_details->process_status == 7){ //For Conformance in Part 7
                    //         $result .= $action_btn_conform;
                    //     }else if($dmrpqc_details->status == 2 && $dmrpqc_details->process_status == 7){ //Ongoing in Part 7
                    //             $result .= $action_btn_update;
                    //         if(DmrpqcSpecification::where('request_id', $dmrpqc_details->id)->where('status', 1)->where('logdel', 0)->exists()){
                    //             $result .= $action_btn_view;
                    //             $result .= $action_btn_submit;
                    //         }
                    //     }else if($dmrpqc_details->status == 1 && $dmrpqc_details->process_status == 8){ //For Conformance in Part 8
                    //         $result .= $action_btn_conform;
                    //     }else if($dmrpqc_details->status == 2 && $dmrpqc_details->process_status == 8){ //Ongoing in Part 8
                    //             $result .= $action_btn_update;
                    //         if(DmrpqcCompletionActivity::where('request_id', $dmrpqc_details->id)->where('status', 1)->where('logdel', 0)->exists()){
                    //             $result .= $action_btn_view;
                    //             $result .= $action_btn_submit;
                    //         }
                    //     }else if($dmrpqc_details->status == 3 && $dmrpqc_details->process_status == 9){ //Once DMRPQC is Completed enable the EXPORT button
                    //         $result .= '<button class="btn btn-sm btn-outline-primary border-0 text-center actionExportBtn" process_status="'.$dmrpqc_details->process_status.'"
                    //                     dmrpqc_id="'.$dmrpqc_details->id.'"><i class="fa-solid fa-file-pdf fa-xl" title="Export"></i></button>';
                    //         $result .= $action_btn_view;
                    //     }
                    //     break;
                // }// CLARK COMMENT 09/24/2024
                return $result;
            })
            ->addColumn('status', function ($dmrpqc_details) {
                $result = "";
                switch($dmrpqc_details->status){
                    case 0: //Default - For Submission (Production)
                        $result .= '<center><span class="badge badge-pill badge-secondary">For Submission</span></center>';
                        $result .= '<center><span class="badge badge-pill badge-primary">By:</span>
                                    <span class="badge badge-pill badge-warning">Production</span></center>';
                        break;
                    case 1: //For Conformance
                        $result .= '<center><span class="badge badge-pill badge-primary">For Conformance</span></center>';
                        $result .= '<center><span class="badge badge-pill badge-primary">By:</span>';
                        $result .= '<br>';
                            switch($dmrpqc_details->process_status){
                                case 1: //Production
                                    $result .= ' <span class="badge badge-pill badge-primary">Production</span></center>';
                                    break;
                                case 2: //Die Maintenance Engr.
                                    $result .= '<span class="badge badge-pill badge-primary">Die Maintenance Engr.</span></center>';
                                    break;
                                case 3: //Die Maintenance Engr.
                                    $result .= '<span class="badge badge-pill badge-primary">Die Maintenance Engr.</span></center>';
                                    break;
                                case 4: //Production/Process Engr.
                                    $result .= '<span class="badge badge-pill badge-primary">Production/Process Engr.</span></center>';
                                    break;
                                case 5: //Production/Process Engr.
                                    $result .= '<span class="badge badge-pill badge-primary">Production/Process/Die Maintenance Engr./QC</span></center>';
                                    break;
                                case 6: //Process Engr.
                                    $result .= '<span class="badge badge-pill badge-primary">Process Engr./QC</span></center>';
                                    break;
                                case 7: //Process Engr/QC.
                                    $result .= '<span class="badge badge-pill badge-primary">Process Engr./QC</span></center>';
                                    break;
                                case 8: //Production
                                    $result .= '<span class="badge badge-pill badge-primary">Production</span></center>';
                                    break;
                                case 9: //Completed
                                    $result .= '<span class="badge badge-pill badge-success">Completed</span></center>';
                                    break;
                            }
                        break;
                    case 2: //Ongoing Activity
                        $result .= '<center><span class="badge badge-pill badge-warning">Ongoing Activity</span></center>';
                        $result .= '<center><span class="badge badge-pill badge-primary">By:</span>';
                        $result .= '<br>';
                            switch($dmrpqc_details->process_status){
                                case 1: //Production
                                    $result .= ' <span class="badge badge-pill badge-primary">Production</span></center>';
                                    break;
                                case 2: //Die Maintenance Engr.
                                    $result .= '<span class="badge badge-pill badge-primary">Die Maintenance Engr.</span></center>';
                                    break;
                                case 3: //Die Maintenance Engr.
                                    $result .= '<span class="badge badge-pill badge-primary">Die Maintenance Engr.</span></center>';
                                    break;
                                case 4: //Production/Process Engr.
                                    $result .= '<span class="badge badge-pill badge-primary">Production/Process Engr.</span></center>';
                                    break;
                                case 5: //Production/Process Engr.
                                    switch ($dmrpqc_details->prod_req_checking_status) {
                                        case 0:
                                            $result .= '<span class="badge badge-pill badge-primary">Production/Process/Die Maintenance Engr./QC</span></center>';
                                            break;
                                        case 1:
                                            $result .= '<span class="badge badge-pill badge-primary">Production</span></center>';
                                            break;
                                        case 2:
                                            $result .= '<span class="badge badge-pill badge-primary">Engineering</span></center>';
                                            break;
                                        case 3:
                                            $result .= '<span class="badge badge-pill badge-primary">Line Quality Control</span></center>';
                                            break;
                                        case 4:
                                            if($dmrpqc_details->max_process_category == 3){
                                                $result .= '<span class="badge badge-pill badge-primary">Line Quality Control</span></center>';
                                            }else{
                                                $result .= '<span class="badge badge-pill badge-primary">Process Engineering</span></center>';
                                            }
                                            break;
                                    }
                                    break;
                                case 6: //Process Engr.
                                    $result .= '<span class="badge badge-pill badge-primary">Process Engr./QC</span></center>';
                                    break;
                                case 7: //Process Engr/QC.
                                    $result .= '<span class="badge badge-pill badge-primary">Process Engr./QC</span></center>';
                                    break;
                                case 8: //Production
                                    $result .= '<span class="badge badge-pill badge-primary">Production</span></center>';
                                    break;
                                case 9: //Completed
                                    $result .= '<span class="badge badge-pill badge-success">Completed</span></center>';
                                    break;
                            }
                        break;
                    case 3: //Completed
                        $result .= '<center><span class="badge badge-pill badge-success">Completed</span></center>';
                        break;
                }
                return $result;
            })
            ->addColumn('process_status', function ($dmrpqc_details) {
                $result = "";

                if($dmrpqc_details->process_status == 1) {
                    $result .= '<center><span class="badge badge-pill badge-info">Product Identification</span></center>';
                }
                else if($dmrpqc_details->process_status == 2){
                    $result .= '<center><span class="badge badge-pill badge-info">Dieset Condition</span></center>';
                }else if($dmrpqc_details->process_status == 3){
                    $result .= '<center><span class="badge badge-pill badge-info">Dieset Condition Checking</span></center>';
                }else if($dmrpqc_details->process_status == 4){
                    $result .= '<center><span class="badge badge-pill badge-info">Machine Setup</span></center>';
                }else if($dmrpqc_details->process_status == 5){
                    $result .= '<center><span class="badge badge-pill badge-info">Product Requirement Checking</span></center>';
                }else if($dmrpqc_details->process_status == 6){
                    $result .= '<center><span class="badge badge-pill badge-info">Machine Parameter Checking</span></center>';
                }else if($dmrpqc_details->process_status == 7){
                    $result .= '<center><span class="badge badge-pill badge-info">Specifications</span></center>';
                }else if($dmrpqc_details->process_status == 8){
                    $result .= '<center><span class="badge badge-pill badge-info">Completion Activity</span></center>';
                }else if($dmrpqc_details->process_status == 9){
                    $result .= '<center><span class="badge badge-pill badge-success">Completed</span></center>';
                }
                return $result;
            })
            // ->addColumn('created_by', function ($dmrpqc_details) {
            //     $result = $dmrpqc_details->firstname.' '.$dmrpqc_details->lastname;
            //     return $result;
            // })
            ->rawColumns(['action','status','process_status'])
            ->make(true);
    }

    public function AddRequest(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        $data = $request->all();
        $password = "pmi12345";

        $validator = Validator::make($data, [
            'user_id' => 'required',
            'po_no' => 'required',
            'request_type' => 'required',
            // 'position_id' => 'required',
            // 'userLevel' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['validation' => 'hasError', 'error' => $validator->messages()]);
        } else {
            if (DmrpqcProductIdentification::where('item_code', $request->item_code)->where('status', 0)->where('logdel', 0)->exists()) {
                return response()->json(['result' => 2]);
            } else {
                DmrpqcProductIdentification::insert([
                    'category'        => $request->form_category,
                    'device_name'     => $request->device_name,
                    'po_number'       => $request->po_no,
                    'item_code'       => $request->item_code,
                    'die_no'          => $request->die_no,
                    'drawing_no'      => $request->drawing_no,
                    'rev_no'          => $request->rev_no,
                    'request_type'    => $request->request_type,
                    'start_date_time' => date('Y-m-d H:i:s'),
                    'created_by'      => $request->user_id,
                    'last_updated_by' => $request->user_id,
                    'created_at'      => date('Y-m-d H:i:s'),
                    'updated_at'      => date('Y-m-d H:i:s'),
                ]);

                DB::commit();
                return response()->json(['result' => 1]);
            }
        }
    }

    public function DeleteRequest(Request $request){
        DmrpqcProductIdentification::where('id', $request->request_id)->update([ 'logdel' => 1 ]);
        return response()->json(['result' => "1"]);
    }

    public function GetDataForDashboard(Request $request){
        $dmrpqc_data = DmrpqcProductIdentification::where('logdel', 0)->get();

        $TotalPendingRequests            = collect($dmrpqc_data)->whereBetween('process_status', [1,8])->count('id');
        $TotalCompletedRequests          = collect($dmrpqc_data)->where('process_status', 9)->count('id');
        $PendingProductIdentification      = collect($dmrpqc_data)->where('process_status', 1)->count('id');
        $PendingDiesetConditionChecking    = collect($dmrpqc_data)->where('process_status', 2)->count('id');
        $PendingMachineSetup               = collect($dmrpqc_data)->where('process_status', 4)->count('id');
        $PendingProductRequirementChecking = collect($dmrpqc_data)->where('process_status', 5)->count('id');
        $PendingMachineParameterChecking   = collect($dmrpqc_data)->where('process_status', 6)->count('id');
        $PendingSpecification              = collect($dmrpqc_data)->where('process_status', 7)->count('id');
        $PendingCompletionActivity           = collect($dmrpqc_data)->where('process_status', 8)->count('id');
        // return $total_pending_requests;

        return response()->json([
            'TotalPendingRequests'       => $TotalPendingRequests,
            'TotalCompletedRequests'     => $TotalCompletedRequests,
            'DmrpqcProductIdentification'      => $PendingProductIdentification,
            'DmrpqcDiesetConditionChecking'    => $PendingDiesetConditionChecking,
            'DmrpqcMachineSetup'               => $PendingMachineSetup,
            'ProductRequirementChecking' => $PendingProductRequirementChecking,
            'DmrpqcMachineParameterChecking'   => $PendingMachineParameterChecking,
            'DmrpqcSpecification'              => $PendingSpecification,
            'DmrpqcCompletionActivity'         => $PendingCompletionActivity
        ]);
    }

    // Get Name by Session
    public function GetNameBySession(Request $request){
        session_start();
        if(isset(Auth::user()->id)){
            // $dmrpqc_user_id = Auth::user()->id;
            $get_requested_by_id = User::where('id', Auth::user()->id)->get();
            return response()->json(["result" => $get_requested_by_id]);
        }else{
            return response()->json(['result' => "1"]);
        }
    }

    // public function get_pps_db_data_by_item_code(Request $request){
    //     $pps_db_details = PpsDbsPoReceived::with(['pps_dieset'])->where('OrderNo', $request->po_number)->get();

    //     if($pps_db_details == null){
    //         return response()->json(['result' => '1']);
    //     }else{
    //         return response()->json(['pps_db_details' => $pps_db_details]);
    //     }
    // }

    public function GetPpsDbDataByItemCode(Request $request){
        $pps_db_details = DB::connection('mysql_rapid_pps')
                    ->select(' SELECT dieset.id AS dieset_id, po_receive.ItemName AS part_name, po_receive.ItemCode AS part_code, po_receive.OrderNo AS po_number, po_receive.OrderQty AS po_qty, dieset.DieNo AS die_no, dieset.DrawingNo AS drawing_no, dieset.Rev AS drawing_rev
                            FROM tbl_POReceived AS po_receive
                            LEFT JOIN tbl_dieset AS dieset ON po_receive.ItemCode = dieset.R3Code
                            WHERE OrderNo = "'.$request->po_number.'"
                    ');

        // return $pps_db_details;
        $device_dmcms = DB::connection('mysql_rapid_stamping_dmcms')
                ->table('tbl_device AS device')
                ->select('device.*')
                ->where('device.logdel', 0)
                ->where('device.device_code', $pps_db_details[0]->part_code)
                ->first();

        if(empty($device_dmcms)){
            return response()->json(['result' => '2']);
        }

        $shots_details_dmcms = DB::connection('mysql_rapid_stamping_dmcms')
                ->table('tbl_shots')
                ->select('approval_status', 'shot', 'machine_no', DB::raw("SUM(`shot`) as ttl_accum_shots"))
                ->where('fkid_device', $device_dmcms->pkid)
                ->where('status', 0)
                // ->where('approval_status', 1)
                ->where('logdel', 0)
                ->groupBy('approval_status', 'machine_no', 'shot')
                ->orderBy('pkid', 'DESC')
                ->get();

        // return $shots_details_dmcms

        if(count($shots_details_dmcms) < 1){
            $shots_details_dmcms = json_decode('{"shot": 0, "machine_no": 0, "ttl_accum_shots": 0}', true);
        }else{
            $shots_details_dmcms = $shots_details_dmcms[0];
        }

        $ttl_shots_accuum = DB::connection('mysql_rapid_stamping_dmcms')
                ->select(' SELECT SUM(`shot`) as ttl_accum_shots
                    FROM tbl_shots WHERE status = 0 AND logdel = 0 AND fkid_device = '.$device_dmcms->pkid.' AND approval_status = 1
                ');

        if(count($ttl_shots_accuum) != 1){
            $ttl_shots_accuum = 0;
        }

        return response()->json(['pps_db_details' => $pps_db_details, 'device_details' => $device_dmcms, 'shots_details' => $shots_details_dmcms, 'shots_accum' => $ttl_shots_accuum]);
    }

    public function UpdatePartsDrawingData(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        // if ($validator->fails()) {
        //     return response()->json(['validation' => 'hasError', 'error' => $validator->messages()]);
        // } else {
            if(isset(Auth::user()->id)){
                if($request->hasFile('uploaded_file')){
                    $original_filename = $request->file('uploaded_file')->getClientOriginalName();

                    if(DmrpqcDiesetCondition::where('parts_drawing', $original_filename)->exists()){
                        return response()->json(['result' => 'File Name Already Exists']);
                    }else{
                        // return $original_filename;
                        Storage::putFileAs('public/PartsDrawingUploadFile', $request->uploaded_file,  $original_filename);
                        DmrpqcDiesetCondition::where('request_id', $request->request_id)
                            ->update([
                                'parts_drawing' => $original_filename,
                                'drawing_specification' => $request->specification,
                                'drawing_actual_measurement' => $request->actual_measurement,
                                'drawing_fabricated_by' => $request->fabricated_by,
                                'drawing_validated_by' => $request->m_validated_by,
                            ]);

                        DB::commit();
                        return response()->json(['result' => 'Success']);
                    }
                }else{
                    DmrpqcDiesetCondition::where('request_id', $request->request_id)
                            ->update([
                                'drawing_specification' => $request->specification,
                                'drawing_actual_measurement' => $request->actual_measurement,
                                'drawing_fabricated_by' => $request->fabricated_by,
                                'drawing_validated_by' => $request->m_validated_by,
                            ]);

                        DB::commit();
                        return response()->json(['result' => 'Success']);
                }
            }else{
                return response()->json(['result' => 'Session Expired']);
            }
        // }
    }

    public function UpdateDiesetConditionData(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        $data = $request->all();

        $parts_no_all = implode(",", $request->PartsNoArr);
        $quantity_all = implode(",", $request->QuantityArr);

        // $PartsDrawingSpecification = implode(",", $request->PartsNoArr);
        // $PartsDrawingActualMeasurement = implode(",", $request->QuantityArr);
        // return $request->parts_no[][];
        // return $parts_no;
        // return $request->all();

        if(isset(Auth::user()->id)){

                // $validator = Validator::make($data, $rules);
                // return $request->all();

                // if ($validator->fails()) {
                //     return response()->json(['validation' => 'hasError', 'error' => $validator->messages()]);
                // }

                if($request->action_1 == NULL && $request->action_2 == NULL &&
                   $request->action_3 == NULL && $request->action_4 == NULL &&
                   $request->action_5 == NULL && $request->action_6 == NULL && $request->action_7 == NULL){

                    return response()->json(['error' => 'Please Select Action Done']);
                }else {
                        DmrpqcDiesetCondition::where('request_id', $request->request_id)
                        ->update([
                            'action_1_mold_cleaned' => $request->action_1,
                            'action_2_mold_check' => $request->action_2,
                            'action_3_device_conversion' => $request->action_3,
                            'action_4_dieset_overhaul' => $request->action_4,
                            'action_4a_fix_side' => $request->action_4a,
                            'action_4b_movement_side' => $request->action_4b,
                            'action_4c_with_parts_marking' => $request->action_4c,
                            'action_4d_without_parts_marking' => $request->action_4d,
                            'action_5_reversible_parts_installed' => $request->action_5,
                            'action_6_repair' => $request->action_6,
                            'action_7_parts_change' => $request->action_7,
                            'action_7a_new' => $request->action_7_a,
                            'action_7b_fabricated' => $request->action_7_b,
                            'action_7c_with_parts_marking' => $request->action_7c,
                            'action_7d_with_parts_change_notice' => $request->action_7d,
                            'details_of_activity' => $request->details_of_activity,
                            'parts_no' => $parts_no_all,
                            'quantity' => $quantity_all,
                            'action_done_date_start' => $request->action_done_date_start,
                            'action_done_start_time' => $request->action_done_start_time,
                            'action_done_date_finish' => $request->action_done_date_finish,
                            'action_done_finish_time' => $request->action_done_finish_time,
                            'in_charged' => $request->action_done_in_charged_id,
                            'check_point_1_marking_check' => $request->check_point_1,
                            'check_point_2_tanshi_pin' => $request->check_point_2,
                            'check_point_2a_crack' => $request->check_point_2a,
                            'check_point_2b_bend' => $request->check_point_2b,
                            'check_point_2c_worn_out' => $request->check_point_2c,
                            'check_point_3_dent' => $request->check_point_3,
                            'check_point_4_porous' => $request->check_point_4,
                            'check_point_5_ejector_pin' => $request->check_point_5,
                            'check_point_6_coma' => $request->check_point_6,
                            'check_point_7_gasvent' => $request->check_point_7,
                            'check_point_8_assy_orientation' => $request->check_point_8,
                            'check_point_9_fs_ms_fitting' => $request->check_point_9,
                            'check_point_10_sub_gate' => $request->check_point_10,
                            'check_point_remarks' => $request->check_point_remarks,
                            'mold_check_1_withdraw_pin_external' => $request->mold_check_1,
                            'mold_check_2_withdraw_pin_internal' => $request->mold_check_2,
                            'mold_check_3_slidecore_stopper' => $request->mold_check_3,
                            'mold_check_4_locator_ring' => $request->mold_check_4,
                            'mold_check_5_bolts_nuts' => $request->mold_check_5,
                            'mold_check_6_stripper_plate' => $request->mold_check_6,
                            'mold_check_remarks' => $request->mold_check_remarks,
                            'mold_check_checked_by' => $request->mold_check_checked_by_id,
                            'mold_check_date' => date('Y-m-d'),
                            'mold_check_time' => date('H:i:s'),
                            'mold_check_status' => $request->mold_check_status,
                            'references_used' => $request->references_used,
                            'final_remarks' => $request->final_remarks,
                            'last_updated_by' => $request->user_id,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => 1 //Change Status to Updated(1)
                        ]);

                        DB::commit();
                        return response()->json(['result' => 'Success']);
                }
            // }
        }else{
            return response()->json(['result' => 'Session Expired']);
        }
    }

    public function UpdateDiesetConditionCheckingData(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        $data = $request->all();
        // return $data;

        if(isset(Auth::user()->id)){

                // CLARK COMMENT 09/27/2024 - Part 3 is optional
                // if($request->good_condition == NULL && $request->under_longevity == NULL && $request->problematic == NULL){
                //     return response()->json(['error' => 'Please Select Condition']);
                // }else {
                        DmrpqcDiesetConditionChecking::where('request_id', $request->request_id)
                        ->update([
                            'good_condition' => $request->good_condition,
                            'under_longevity' => $request->under_longevity,
                            'problematic_die_set' => $request->problematic,
                            'checked_by' => $request->user_id,
                            'date' => date('Y-m-d'),
                            'last_updated_by' => $request->user_id,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => 1 //Change Status to Updated(1)
                        ]);

                        DB::commit();
                        return response()->json(['result' => 'Success']);
                // }
        }else{
            return response()->json(['result' => 'Session Expired']);
        }
    }

    public function UpdateMachineSetupData(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        $data = $request->all();

        if(isset(Auth::user()->id)){

                // if($request->machine_setup_1st_adjustment == NULL){
                //     return response()->json(['error' => 'Please Select Adjustment']);
                // }else {
                if(isset($request->machine_setup_1st_adjustment)){
                    if($request->machine_setup_1st_remarks == 1){ //FOR QUALIFICATION
                        $status = 2;
                    }else if($request->machine_setup_1st_remarks == 3){ //MOLD DOWN
                        $status = 3;
                        $dmrpqc_user_id = Auth::user()->id;
                        echo $this->updateStatusProductIdentification($request->request_id, 1, 8, $dmrpqc_user_id);
                    }else{
                        $status = 1;
                    }

                    DmrpqcMachineSetup::where('request_id', $request->request_id)
                    ->update([
                        'first_adjustment' => $request->machine_setup_1st_adjustment,
                        'first_in_charged' => $request->machine_setup_1st_in_charged,
                        'first_date_time' => date('Y-m-d H:i'),
                        'first_remarks' => $request->machine_setup_1st_remarks,
                        'category' => $request->machine_setup_category,
                        'last_updated_by' => $request->user_id,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'process_status' => 1,
                        'status' => $status //Change Status to Updated(1)
                    ]);
                }else if(isset($request->machine_setup_2nd_adjustment)){
                    DmrpqcMachineSetup::where('request_id', $request->request_id)
                    ->update([
                        'second_adjustment' => $request->machine_setup_2nd_adjustment,
                        'second_in_charged' => $request->machine_setup_2nd_in_charged,
                        'second_date_time' => date('Y-m-d H:i'),
                        'second_remarks' => $request->machine_setup_2nd_remarks,
                        'last_updated_by' => $request->user_id,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'process_status' => 2,
                        'status' => 1 //Change Status to Updated(1)
                    ]);
                }else if(isset($request->machine_setup_3rd_adjustment)){
                    DmrpqcMachineSetup::where('request_id', $request->request_id)
                    ->update([
                        'third_adjustment' => $request->machine_setup_3rd_adjustment,
                        'third_in_charged' => $request->machine_setup_3rd_in_charged,
                        'third_date_time' => date('Y-m-d H:i'),
                        'third_remarks' => $request->machine_setup_3rd_remarks,
                        'category' => $request->machine_setup_category,
                        'last_updated_by' => $request->user_id,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'process_status' => 3,
                        'status' => 2 //Change Status to Done(2)
                    ]);
                }

                        // CLARK 09/17/2024
                        // DmrpqcProductIdentification::insert([
                        //     'device_name' => $request->device_name,
                        //     'po_number' => $request->po_no,
                        //     'item_code' => $request->item_code,
                        //     'die_no' => $request->die_no,
                        //     'drawing_no' => $request->drawing_no,
                        //     'rev_no' => $request->rev_no,
                        //     'request_type' => $request->request_type,
                        //     'start_date_time' => date('Y-m-d H:i:s'),
                        //     'created_by' => $request->user_id,
                        //     'last_updated_by' => $request->user_id,
                        //     'created_at' => date('Y-m-d H:i:s'),
                        //     'updated_at' => date('Y-m-d H:i:s'),
                        // ]);
                        // CLARK 09/17/2024

                        DB::commit();
                        return response()->json(['result' => 'Success']);
                // }
        }else{
            return response()->json(['result' => 'Session Expired']);
        }
    }

    public function UpdateProductReqCheckingData(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();
        $data = $request->all();
        DB::beginTransaction();
        try{
            if(isset(Auth::user()->id)){
                $prod_req_checking_status = DmrpqcProductReqChecking::select('id','status')->where('request_id', $request->request_id)->first();

                if($prod_req_checking_status->status < 2){
                    $status = $prod_req_checking_status->status + 1;
                }else{//Update to status 4 to skip ENGR input
                    $status = $prod_req_checking_status->status + 2;
                }

                switch ($prod_req_checking_status->status){
                    case 0:
                        $test_arr = ['prod_visual_insp_name' => 'required',
                                    'prod_visual_insp_result' => 'required',
                                    'prod_dimension_insp_name' => 'required',
                                    'prod_dimension_insp_result' => 'required',
                                    'pic' => 'required'];
                        break;
                    case 1:
                        $test_arr = ['engr_tech_visual_insp_name' => 'required',
                                    'engr_tech_visual_insp_result' => 'required',
                                    'engr_tech_dimension_insp_name' => 'required',
                                    'engr_tech_dimension_insp_result' => 'required'];
                        break;
                    case 2:
                        $test_arr = ['lqc_visual_insp_name' => 'required',
                                    'lqc_visual_insp_result' => 'required',
                                    'lqc_dimension_insp_name' => 'required',
                                    'lqc_dimension_insp_result' => 'required',
                                    'checked_by_qc' => 'required'];
                        break;
                    case 3:
                        $test_arr = ['process_engr_visual_insp_name' => 'required',
                                    'process_engr_visual_insp_result' => 'required',
                                    'process_engr_dimension_insp_name' => 'required',
                                    'process_engr_dimension_insp_result' => 'required',
                                    'checked_by_engr' => 'required'];
                        break;
                }
                $validator = Validator::make($data, $test_arr);

                if($validator->passes()){
                    DmrpqcProductReqChecking::where('request_id', $request->request_id)
                        ->update([
                            'last_updated_by' => $request->user_id,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'status' => $status //Change Status to Updated(2)
                        ]);

                    if($prod_req_checking_status->status == 0){ //PRODUCTION
                        DmrpqcProductReqCheckingDetails::insert([
                            'prod_req_checking_id' => $prod_req_checking_status->id,
                            'process_category' => ($prod_req_checking_status->status + 1),
                            'eval_sample' => $request->prod_eval_sample,
                            'japan_sample' => $request->prod_japan_sample,
                            'last_prodn_sample' => $request->prod_last_prodn_sample,
                            'dieset_eval_report' => $request->prod_dieset_eval_report,
                            'cosmetic_defect' => $request->prod_cosmetic_defect,
                            'pingauges' => $request->prod_pingauges,
                            'measurescope' => $request->prod_measurescope,
                            'n_a' => $request->prod_na,
                            'visual_insp_name' => $request->prod_visual_insp_name,
                            'visual_insp_datetime' => date('Y-m-d H:i:s'),
                            'visual_insp_result' => $request->prod_visual_insp_result,
                            'dimension_insp_name' => $request->prod_dimension_insp_name,
                            'dimension_insp_datetime' => date('Y-m-d H:i:s'),
                            'dimension_insp_result' => $request->prod_dimension_insp_result,
                            'actual_checking_remarks' => $request->prod_actual_checking_remarks,
                            // 'status' => 1, //Change Status to Updated(1)
                            'created_by' => $request->user_id,
                            'last_updated_by' => $request->user_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                        DmrpqcMachineSetupSample::where('request_id', $request->request_id)
                        ->update([
                            'number_of_shots' => $request->number_of_shots,
                            'actual_quantity' => $request->actual_quantity,
                            'judgement' => $request->judgement,
                            'machine_parts' => $request->machine_parts,
                            'output_path' => $request->output_path,
                            'product_catcher' => $request->product_catcher,
                            'pic' => $request->pic,
                            'pic_datetime' => date('Y-m-d H:i:s'),
                            // 'status' => 1 //Change Status to Updated(1)
                        ]);
                    }else if($prod_req_checking_status->status == 1){ //TECHNICIAN
                        DmrpqcProductReqCheckingDetails::insert([
                            'prod_req_checking_id' => $prod_req_checking_status->id,
                            'process_category' => ($prod_req_checking_status->status + 1),
                            'eval_sample' => $request->engr_tech_eval_sample,
                            'japan_sample' => $request->engr_tech_japan_sample,
                            'last_prodn_sample' => $request->engr_tech_last_prodn_sample,
                            'material_drawing' => $request->engr_tech_material_drawing,
                            'material_drawing_no' => $request->engr_tech_material_drawing_no,
                            'material_rev_no' => $request->engr_tech_material_rev_no,
                            'insp_guide' => $request->engr_tech_insp_guide,
                            'insp_guide_drawing_no' => $request->engr_tech_insp_guide_drawing_no,
                            'insp_guide_rev_no' => $request->engr_tech_insp_guide_rev_no,
                            'dieset_eval_report' => $request->engr_tech_dieset_eval_report,
                            'cosmetic_defect' => $request->engr_tech_cosmetic_defect,
                            'pingauges' => $request->engr_tech_pingauges,
                            'measurescope' => $request->engr_tech_measurescope,
                            'n_a' => $request->engr_tech_na,
                            'visual_insp_name' => $request->engr_tech_visual_insp_name,
                            'visual_insp_datetime' => date('Y-m-d H:i:s'),
                            'visual_insp_result' => $request->engr_tech_visual_insp_result,
                            'dimension_insp_name' => $request->engr_tech_dimension_insp_name,
                            'dimension_insp_datetime' => date('Y-m-d H:i:s'),
                            'dimension_insp_result' => $request->engr_tech_dimension_insp_result,
                            'actual_checking_remarks' => $request->engr_tech_actual_checking_remarks,
                            'created_by' => $request->user_id,
                            'last_updated_by' => $request->user_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);

                        DmrpqcMachineSetupSample::where('request_id', $request->request_id)
                        ->update([
                            'engr' => $request->checked_by_engr,
                            'engr_datetime' => date('Y-m-d H:i:s'),
                            // 'status' => 1 //Change Status to Updated(1)
                        ]);
                    }else if($prod_req_checking_status->status == 2){ //LQC
                        DmrpqcProductReqCheckingDetails::insert([
                            'prod_req_checking_id' => $prod_req_checking_status->id,
                            'process_category' => ($prod_req_checking_status->status + 1),
                            'eval_sample' => $request->lqc_eval_sample,
                            'japan_sample' => $request->lqc_japan_sample,
                            'last_prodn_sample' => $request->lqc_last_prodn_sample,
                            'material_drawing' => $request->lqc_material_drawing,
                            'material_drawing_no' => $request->lqc_material_drawing_no,
                            'material_rev_no' => $request->lqc_material_rev_no,
                            'insp_guide' => $request->lqc_insp_guide,
                            'insp_guide_drawing_no' => $request->lqc_insp_guide_drawing_no,
                            'insp_guide_rev_no' => $request->lqc_insp_guide_rev_no,
                            'dieset_eval_report' => $request->lqc_dieset_eval_report,
                            'cosmetic_defect' => $request->lqc_cosmetic_defect,
                            'pingauges' => $request->lqc_pingauges,
                            'measurescope' => $request->lqc_measurescope,
                            'n_a' => $request->lqc_na,
                            'visual_insp_name' => $request->lqc_visual_insp_name,
                            'visual_insp_datetime' => date('Y-m-d H:i:s'),
                            'visual_insp_result' => $request->lqc_visual_insp_result,
                            'dimension_insp_name' => $request->lqc_dimension_insp_name,
                            'dimension_insp_datetime' => date('Y-m-d H:i:s'),
                            'dimension_insp_result' => $request->lqc_dimension_insp_result,
                            'actual_checking_remarks' => $request->lqc_actual_checking_remarks,
                            'created_by' => $request->user_id,
                            'last_updated_by' => $request->user_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            // 'status' => 3 //Change Status to Updated(1)
                        ]);

                        DmrpqcMachineSetupSample::where('request_id', $request->request_id)
                        ->update([
                            'qc' => $request->checked_by_qc,
                            'qc_datetime' => date('Y-m-d H:i:s'),
                            // 'status' => 1 //Change Status to Updated(1)
                        ]);
                    }else if($prod_req_checking_status->status == 3){ //ENGR
                        DmrpqcProductReqCheckingDetails::insert([
                            'prod_req_checking_id' => $prod_req_checking_status->id,
                            'process_category' => ($prod_req_checking_status->prod_req_checking_status + 1),
                            'eval_sample' => $request->process_engr_eval_sample,
                            'japan_sample' => $request->process_engr_japan_sample,
                            'last_prodn_sample' => $request->process_engr_last_prodn_sample,
                            'material_drawing' => $request->process_engr_material_drawing,
                            'material_drawing_no' => $request->process_engr_material_drawing_no,
                            'material_rev_no' => $request->process_engr_material_rev_no,
                            'insp_guide' => $request->process_engr_insp_guide,
                            'insp_guide_drawing_no' => $request->process_engr_insp_guide_drawing_no,
                            'insp_guide_rev_no' => $request->process_engr_insp_guide_rev_no,
                            'dieset_eval_report' => $request->process_engr_dieset_eval_report,
                            'cosmetic_defect' => $request->process_engr_cosmetic_defect,
                            'pingauges' => $request->process_engr_pingauges,
                            'measurescope' => $request->process_engr_measurescope,
                            'n_a' => $request->process_engr_na,
                            'visual_insp_name' => $request->process_engr_visual_insp_name,
                            'visual_insp_datetime' => date('Y-m-d H:i:s'),
                            'visual_insp_result' => $request->process_engr_visual_insp_result,
                            'dimension_insp_name' => $request->process_engr_dimension_insp_name,
                            'dimension_insp_datetime' => date('Y-m-d H:i:s'),
                            'dimension_insp_result' => $request->process_engr_dimension_insp_result,
                            'actual_checking_remarks' => $request->process_engr_actual_checking_remarks,
                            'created_by' => $request->user_id,
                            'last_updated_by' => $request->user_id,
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                            // 'status' => 4 //Change Status to Updated(1)
                        ]);
                    }

                    DB::commit();
                    return response()->json(['result' => 'Success']);
                }else{
                    return response()->json(['validation' => 'hasError', 'error' => $validator->messages(), 'process_status' => $prod_req_checking_status->status], 422);
                    // return response()->json(['validation' => 'hasError', 'error' => $validator->messages()]);
                }
            }else{
                return response()->json(['result' => 'Session Expired']);
            }
        }catch (\Throwable $th){
            throw $th;
            DB::rollback();
        }
    }

    public function UpdateMachineParamCheckingData(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();
        $data = $request->all();
        // return $request->all();
        if(isset(Auth::user()->id)){
            // $status = DmrpqcMachineParameterChecking::select('status')->where('request_id', $request->request_id)->first();
            // return $status->status;
                // if($request->machine_setup_1st_adjustment == NULL){
                //     return response()->json(['error' => 'Please Select Adjustment']);
                // }else {
                    $machine_parameter_details = DmrpqcMachineParameterChecking::where('request_id', $request->request_id)->first();
                    if($machine_parameter_details->status == 0){
                        DmrpqcMachineParameterChecking::where('request_id', $request->request_id)
                        ->update([
                            'reference' => $request->machine_param_chckng_ref,

                            'pressure_engr_std_specs' => $request->pressure_std_specs,
                            'pressure_engr_actual' => $request->pressure_engr_actual,
                            'pressure_engr_result' => $request->pressure_engr_result,
                            'pressure_engr_name' => $request->pressure_engr_name,
                            'pressure_engr_date' => $request->pressure_engr_date,

                            'temp_nozzle_engr_std_specs' => $request->temp_nozzle_std_specs,
                            'temp_nozzle_engr_actual' => $request->temp_nozzle_engr_actual,
                            'temp_nozzle_engr_result' => $request->temp_nozzle_engr_result,
                            'temp_nozzle_engr_name' => $request->temp_nozzle_engr_name,
                            'temp_nozzle_engr_date' => $request->temp_nozzle_engr_date,

                            'temp_mold_engr_std_specs' => $request->temp_mold_std_specs,
                            'temp_mold_engr_actual' => $request->temp_mold_engr_actual,
                            'temp_mold_engr_result' => $request->temp_mold_engr_result,
                            'temp_mold_engr_name' => $request->temp_mold_engr_name,
                            'temp_mold_engr_date' => $request->temp_mold_engr_date,

                            'cooling_time_engr_std_specs' => $request->ctime_std_specs,
                            'cooling_time_engr_actual' => $request->ctime_engr_actual,
                            'cooling_time_engr_result' => $request->ctime_engr_result,
                            'cooling_time_engr_name' => $request->ctime_engr_name,
                            'cooling_time_engr_date' => $request->ctime_engr_date,
                            'status' => ($machine_parameter_details->status + 1) //Change Status to Updated(1)
                        ]);
                        DB::commit();
                        return response()->json(['result' => 'Success']);
                    }else if($machine_parameter_details->status == 1){
                        DmrpqcMachineParameterChecking::where('request_id', $request->request_id)
                        ->update([
                            'pressure_qc_actual' => $request->pressure_qc_actual,
                            'pressure_qc_result' => $request->pressure_qc_result,
                            'pressure_qc_name' => $request->pressure_qc_name,
                            'pressure_qc_date' => $request->pressure_qc_date,

                            'temp_nozzle_qc_actual' => $request->temp_nozzle_qc_actual,
                            'temp_nozzle_qc_result' => $request->temp_nozzle_qc_result,
                            'temp_nozzle_qc_name' => $request->temp_nozzle_qc_name,
                            'temp_nozzle_qc_date' => $request->temp_nozzle_qc_date,

                            'temp_mold_qc_actual' => $request->temp_mold_qc_actual,
                            'temp_mold_qc_result' => $request->temp_mold_qc_result,
                            'temp_mold_qc_name' => $request->temp_mold_qc_name,
                            'temp_mold_qc_date' => $request->temp_mold_qc_date,

                            'cooling_time_qc_actual' => $request->ctime_qc_actual,
                            'cooling_time_qc_result' => $request->ctime_qc_result,
                            'cooling_time_qc_name' => $request->ctime_qc_name,
                            'cooling_time_qc_date' => $request->ctime_qc_date,
                            'status' => ($machine_parameter_details->status + 1) //Change Status to Updated(1)
                        ]);
                        DB::commit();
                        return response()->json(['result' => 'Success']);
                    }
                // }
        }else{
            return response()->json(['result' => 'Session Expired']);
        }
    }

    public function UpdateSpecificationsData(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();
        $data = $request->all();
        // return $request->all();
        if(isset(Auth::user()->id)){
            // $status = DmrpqcMachineParameterChecking::select('status')->where('request_id', $request->request_id)->first();
            // return $status->status;
                // if($request->machine_setup_1st_adjustment == NULL){
                //     return response()->json(['error' => 'Please Select Adjustment']);
                // }else {
                    // if($status->status == 0){
                        DmrpqcSpecification::where('request_id', $request->request_id)
                        ->update([
                            'ng_issued_ptnr' => $request->ng_issued_ptnr,
                            'ng_coordinate_to_ts_cn_assembly' => $request->ng_coordinate_to_ts_cn_assembly,
                            'ng_discussion_w_tech_adviser' => $request->ng_discussion_w_tech_adviser,
                            'ng_go_production' => $request->ng_go_production,
                            'ng_stop_production' => $request->ng_stop_production,
                            'ng_judged_by' => $request->ng_judged_by,
                            'ng_datetime' => $request->ng_datetime,
                            'ok_go_production' => $request->ok_go_production,
                            'ok_verified_by' => $request->ok_verified_by,
                            'ok_datetime' => $request->ok_datetime,
                            'engr_head_go_production' => $request->engr_head_go_production,
                            'engr_head_stop_production' => $request->engr_head_stop_production,
                            'remarks' => $request->remarks,
                            'signed' => $request->signed_by,
                            'engr_head_datetime' => $request->engr_head_datetime,
                            'status' => 1 //Change Status to Updated(1)
                        ]);
                        DB::commit();
                        return response()->json(['result' => 'Success']);
                    // }
                // }
        }else{
            return response()->json(['result' => 'Session Expired']);
        }
    }

    public function UpdateCompletionData(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();
        $data = $request->all();
        if(isset(Auth::user()->id)){
            DmrpqcCompletionActivity::where('request_id', $request->request_id)
            ->update([
                'trouble_content' => $request->trouble_content,
                'illustration' => $request->illustration,
                'remarks' => $request->completion_remarks,
                'finished_po' => $request->finished_po,
                'with_po_received' => $request->with_po_received,
                'po_not_yet_finished' => $request->po_not_finished,
                'mold_checking' => $request->mold_checking,
                'sample_attachment' => $request->sample_attachment,
                'illustration_attachment' => $request->illustration_attachment,
                'for_repair' => $request->for_repair,
                'mold_clean' => $request->mold_clean,
                'prepared_by' => $request->prepared_by,
                'date' => $request->stop_prod_date,
                'time' => $request->stop_prod_time,
                'shots' => $request->shots,
                'shot_accume' => $request->shots_accume,
                'maintenance_cycle' => $request->maint_cycle,
                'maintenance_no' => $request->machine_no,
                'date_needed' => $request->date_needed,
                'ship_sched' => $request->ship_sched,
                'ptnr_ctrl_no' => $request->ptnr_ctrl_no,
                'checked_by' => $request->checked_by,
                'with_produce_unit' => $request->w_produce_unit,
                'without_produce_unit' => $request->wo_produce_unit,
                'affected_lot' => $request->affected_lot,
                'affected_lot_qty' => $request->affected_lot_qty,
                'backtracking_lot' => $request->backtracking_lot,
                'backtracking_lot_qty' => $request->backtracking_lot_qty,
                'status' => 1 //Change Status to Updated(1)
            ]);
            DB::commit();
            return response()->json(['result' => 'Success']);
        }else{
            return response()->json(['result' => 'Session Expired']);
        }
    }

    public function GetDmrpqcDetailsId(Request $request){
            // $dmrpqc_details = DmrpqcProductIdentification::with(['created_by' => function($query) { $query->select('id', DB::raw("CONCAT(firstname, ' ', lastname) AS full_name")); }])
            $dmrpqc_details = DmrpqcProductIdentification::with(['created_by' => function($query) { $query->select('id', DB::raw("CONCAT(firstname, ' ', lastname) AS full_name")); }])
            ->where('id', $request->id)
            ->where('logdel', 0)->get();

            //  return $dmrpqc_details;

        if($request->process_status >= 2){ //Get Dieset Condition Data if the Product Identification Status is (2)Ongoing
            // $dieset_condition_details = DmrpqcDiesetCondition::with(['in_charged' => function($query) { $query->select('id', DB::raw("CONCAT(firstname, ' ', lastname) AS full_name")); },
            //                                                     'checked_by' => function($query) { $query->select('id', DB::raw("CONCAT(firstname, ' ', lastname) AS full_name")); },
            //                                                     'drawing_fabricated_by' => function($query) { $query->select('id', DB::raw("CONCAT(firstname, ' ', lastname) AS full_name")); },
            //                                                     'drawing_validated_by' => function($query) { $query->select('id', DB::raw("CONCAT(firstname, ' ', lastname) AS full_name")); }])
            //                                                     ->where('request_id', $request->id)
            //                                                     ->where('logdel', 0)->get();

            $dieset_condition_details = DmrpqcDiesetCondition::with(['in_charged' => function($query) { $query->select('id', DB::raw("CONCAT(firstname, ' ', lastname) AS full_name")); },
                                                                'checked_by' => function($query) { $query->select('id', DB::raw("CONCAT(firstname, ' ', lastname) AS full_name")); }])
                                                                ->where('request_id', $request->id)
                                                                ->where('logdel', 0)->get();
            // return $dieset_condition_details;
            if($dieset_condition_details->isEmpty()){
                $dieset_condition_details = '';
                $dieset_condition_checking_details = '';
            }else{

                if($dieset_condition_details[0]->status == 0){ //return blank data if the (p2) status is unchanged(0) not yet updated
                    $dieset_condition_details = '';
                }

                $dieset_condition_checking_details = DmrpqcDiesetConditionChecking::with(['checked_by' => function($query) { $query->select('id', DB::raw("CONCAT(firstname, ' ', lastname) AS full_name")); }])
                                                                                    ->where('request_id', $request->id)
                                                                                    ->where('logdel', 0)->get();

                if($dieset_condition_checking_details[0]->status == 0){ //return blank data if the (p3) status is unchanged(0) not yet updated
                    $dieset_condition_checking_details = '';
                }
            }
        }else{
            $dieset_condition_details = '';
            $dieset_condition_checking_details = '';
        }

        // if($request->process_status >= 3){
        //     $dieset_condition_checking_details = DmrpqcDiesetConditionChecking::with(['checked_by' => function($query) { $query->select('id', DB::raw("CONCAT(firstname, ' ', lastname) AS full_name")); }])
        //                                                                         ->where('request_id', $request->id)
        //                                                                         ->where('logdel', 0)->get();

        //     if($dieset_condition_checking_details[0]->status == 0){ //return blank data if the (p3) status is unchanged(0) not yet updated
        //         $dieset_condition_checking_details = '';
        //     }
        // }else{
        //     $dieset_condition_checking_details = '';
        // }

        if($request->process_status >= 4){
            $machine_setup_details = DmrpqcMachineSetup::where('request_id', $request->id)
                                                    ->where('logdel', 0)->get();
            if($machine_setup_details->isEmpty()){
                $machine_setup_details = '';
            }else{
                if($machine_setup_details[0]->status == 0){ //return blank data if the (p4) status is unchanged(0) not yet updated
                    $machine_setup_details = '';
                }
            }
        }else{
            $machine_setup_details = '';
        }
        // return $machine_setup_details;

        if($request->process_status >= 5 && $machine_setup_details[0]->first_remarks != 3){ //09/26/2024 p4 first is not mold down
            $product_req_checking_details = DmrpqcProductReqChecking::with(['prod_req_checking_details'])->where('request_id', $request->id)->where('logdel', 0)->get();
            // return $product_req_checking_details;
            // if($product_req_checking_details[0]->prod_visual_insp_name == '' || $product_req_checking_details[0]->prod_dimension_insp_name == ''){
            //     $product_req_checking_details = '';
            // }
            if($product_req_checking_details->isEmpty()){
                $product_req_checking_details = '';
            }else{
                if($product_req_checking_details[0]->prod_req_checking_details == ''){
                    $product_req_checking_details = '';
                }
            }
            $machine_setup_sample_details = DmrpqcMachineSetupSample::where('request_id', $request->id)->where('logdel', 0)->get();

            // if($machine_setup_sample_details[0]->pic == '' || $machine_setup_sample_details[0]->pic_datetime == ''){
            //     $machine_setup_sample_details = '';
            // }
        }else{
            $product_req_checking_details = '';
            $machine_setup_sample_details = '';
        }

        if($request->process_status >= 6 && $machine_setup_details[0]->first_remarks != 3){
            $machine_param_checking_details = DmrpqcMachineParameterChecking::where('request_id', $request->id)
                                                    ->where('logdel', 0)->get();
            if($machine_param_checking_details->isEmpty()){
                $machine_param_checking_details = '';
            }
            // else{
            //     if($machine_param_checking_details[0]->reference == ''){ //return blank data if the (p4) status is unchanged(0) not yet updated
            //         $machine_param_checking_details = '';
            //     }
            // }
        }else{
            $machine_param_checking_details = '';
        }

        if($request->process_status >= 7 && $machine_setup_details[0]->first_remarks != 3){
            // $specification_details = DmrpqcSpecification::with(['ng_judged_by' => function($query) { $query->select('id', DB::raw("CONCAT(firstname, ' ', lastname) AS full_name")); },
            //                                               'ok_verified_by' => function($query) { $query->select('id', DB::raw("CONCAT(firstname, ' ', lastname) AS full_name")); },
            //                                               'signed' => function($query) { $query->select('id', DB::raw("CONCAT(firstname, ' ', lastname) AS full_name")); }])
            //                                               ->where('request_id', $request->id)
            //                                               ->where('logdel', 0)->get();

            $specification_details = DmrpqcSpecification::where('request_id', $request->id)->where('logdel', 0)->get();

            if($specification_details->isEmpty()){
                $specification_details = '';
            }else{
                if($specification_details[0]->status == 0){ //return blank data if the (p4) status is unchanged(0) not yet updated
                    $specification_details = '';
                }
            }
        }else{
            $specification_details = '';
        }

        if($request->process_status >= 8){
            $completion_activity_details = DmrpqcCompletionActivity::where('request_id', $request->id)
                                                    ->where('logdel', 0)->get();
            if($completion_activity_details->isEmpty()){
                $completion_activity_details = '';
            }else{
                if($completion_activity_details[0]->status == 0){ //return blank data if the (p4) status is unchanged(0) not yet updated
                    $completion_activity_details = '';
                }
            }
        }else{
            $completion_activity_details = '';
        }

        return response()->json(['dmrpqc_details' => $dmrpqc_details,
                                'dieset_condition_details' => $dieset_condition_details,
                                'dieset_condition_checking_details' => $dieset_condition_checking_details,
                                'machine_setup_details' => $machine_setup_details,
                                'product_req_checking_details' => $product_req_checking_details,
                                'machine_setup_sample_details' => $machine_setup_sample_details,
                                'machine_param_checking_details' => $machine_param_checking_details,
                                'specification_details' => $specification_details,
                                'completion_activity_details' => $completion_activity_details]);
    }

    function updateStatusProductIdentification($id, $status, $process_status, $updated_by){
        DmrpqcProductIdentification::where('id', $id)->update([
            'status' => $status,
            'process_status' => $process_status,
            'last_updated_by' => $updated_by,
            'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    // function update_status_dieset_condition($id, $updated_by){
    //     DmrpqcDiesetCondition::where('request_id', $id)
    //                     ->update(['last_updated_by' => $updated_by,
    //                               'updated_at' => date('Y-m-d H:i:s'),]);
    // }

    // function updateStatusProductIdentification($id, $status, $process_status, $updated_by){
    //     ProductIdentification::where('id', $id)->update([
    //         'status' => $status,
    //         'process_status' => $process_status,
    //         'last_updated_by' => $updated_by,
    //         'updated_at' => date('Y-m-d H:i:s'),
    //         ]);
    // }

    public function UpdateStatusOfDiesetRequest(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();
        if(isset(Auth::user()->id)){
            $dmrpqc_user_id = Auth::user()->id;
            // $get_requested_by_id = User::with(['rapidx_user_details'])->where('rapidx_id', $dmrpqc_user_id)->first();

                if($request->process_status == 1){ //Submit Request (Part 1. Production Identification)
                    // if(DmrpqcDiesetCondition::where('request_id', $request->id)->exists()){
                    //     return response()->json(['result' => "Duplicate"]);
                    // }else{
                    if($request->request_type == 2){
                        $dmrpqc_user_id = Auth::user()->id;
                        echo $this->updateStatusProductIdentification($request->request_id, 1, 4, $dmrpqc_user_id);
                    }else{
                        echo $this->updateStatusProductIdentification($request->request_id, 1, 2, $dmrpqc_user_id);
                    }
                    return response()->json(['result' => "Successful"]);
                    // }
                }
                elseif($request->process_status == 2){ //Part 2. Dieset Condition & Part 3. Checking

                    $dieset_condition = DmrpqcDiesetCondition::where('request_id', $request->request_id)->first();
                    $dieset_condition_checking = DmrpqcDiesetConditionChecking::where('request_id', $request->request_id)->first();
                    if(!isset($dieset_condition->request_id)){ // Conform: if request_id is not existing in dieset condition table
                        echo $this->updateStatusProductIdentification($request->request_id, 2, 2, $dmrpqc_user_id);

                        DmrpqcDiesetCondition::insert(['request_id' => $request->request_id,
                                                'created_by' => $dmrpqc_user_id,
                                                'last_updated_by' => $dmrpqc_user_id,
                                                'created_at' => date('Y-m-d H:i:s'),
                                                'updated_at' => date('Y-m-d H:i:s'),]);

                        if(!isset($dieset_condition_checking->request_id)){
                            DmrpqcDiesetConditionChecking::insert(['request_id' => $request->request_id,
                                    'created_by' => $dmrpqc_user_id,
                                    'last_updated_by' => $dmrpqc_user_id,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),]);
                        }

                        return response()->json(['result' => "Successful"]);
                    }elseif(isset($dieset_condition->request_id) && $dieset_condition->status == 1){ //Submit: if there is request_id exist in dieset condition table and the status is 1(Updated)
                        echo $this->updateStatusProductIdentification($request->request_id, 1, 4, $dmrpqc_user_id);
                        DmrpqcDiesetCondition::where('request_id', $request->request_id)->update(['last_updated_by' => $dmrpqc_user_id,
                                               'updated_at' => date('Y-m-d H:i:s'),]);

                        if(isset($dieset_condition_checking->request_id) && $dieset_condition_checking->status == 1){
                            DmrpqcDiesetConditionChecking::where('request_id', $request->request_id)->update(['last_updated_by' => $dmrpqc_user_id,
                                                'updated_at' => date('Y-m-d H:i:s'),]);
                        }

                        return response()->json(['result' => "Successful"]);
                    }else{
                        return response()->json(['result' => "Error"]);
                    }

                }elseif($request->process_status == 4){ //Part 4. Machine Setup

                    $machine_setup = DmrpqcMachineSetup::where('request_id', $request->request_id)->first();
                    // Conform: if request_id is not existing in machine setup table
                    if(!isset($machine_setup->request_id)){
                        echo $this->updateStatusProductIdentification($request->request_id, 2, 4, $dmrpqc_user_id);

                        DmrpqcMachineSetup::insert(['request_id' => $request->request_id,
                                                        'created_by' => $dmrpqc_user_id,
                                                        'last_updated_by' => $dmrpqc_user_id,
                                                        'created_at' => date('Y-m-d H:i:s'),
                                                        'updated_at' => date('Y-m-d H:i:s'),]);

                        return response()->json(['result' => "Successful"]);
                        //Submit: if there is request_id exist in machine setup table and the status is 1(Updated)
                    }elseif(isset($machine_setup->request_id) && $machine_setup->status == 2){
                        echo $this->updateStatusProductIdentification($request->request_id, 1, 5, $dmrpqc_user_id);
                        DmrpqcMachineSetup::where('request_id', $request->request_id)->update(['last_updated_by' => $dmrpqc_user_id,
                                               'updated_at' => date('Y-m-d H:i:s'),]);

                        return response()->json(['result' => "Successful"]);
                    }
                }elseif($request->process_status == 5){ //Part 5. Product Requirement Checking

                    $product_req_checking = DmrpqcProductReqChecking::select(['request_id', 'status'])->where('request_id', $request->request_id)->first();
                    // return $product_req_checking['request_id'];
                    // Conform: if request_id is not existing in machine setup table
                    if(!isset($product_req_checking['request_id'])){
                        // if(!isset($product_req_checking['status'])){ //Update p1 to next part
                            echo $this->updateStatusProductIdentification($request->request_id, 2, 5, $dmrpqc_user_id);
                        // }
                        DmrpqcProductReqChecking::insert(['request_id' => $request->request_id,
                                                        'created_by' => $dmrpqc_user_id,
                                                        'last_updated_by' => $dmrpqc_user_id,
                                                        'created_at' => date('Y-m-d H:i:s'),
                                                        'updated_at' => date('Y-m-d H:i:s'),]);

                        DmrpqcMachineSetupSample::insert(['request_id' => $request->request_id,
                                                        'created_by' => $dmrpqc_user_id,
                                                        'last_updated_by' => $dmrpqc_user_id,
                                                        'created_at' => date('Y-m-d H:i:s'),
                                                        'updated_at' => date('Y-m-d H:i:s'),]);

                        return response()->json(['result' => "Successful"]);
                        //Submit: if there is request_id exist in machine setup table and the status is 1(Updated)
                    }elseif(isset($product_req_checking['request_id'])){

                        // $product_req_checking['request_id']
                        // DmrpqcProductReqChecking::where('request_id', $request->request_id)
                        // Clark Comment 09162024
                        // if($status->status <= 4){
                            // DmrpqcProductReqChecking::where('request_id', $request->request_id)
                            // ->update([
                            //     'last_updated_by' => $request->user_id,
                            //     'updated_at' => date('Y-m-d H:i:s'),
                            //     'status' => ($status->status + 1) //Change Status to Updated(2)
                            // ]);
                        // }else{

                        // if($product_req_checking['status'] == 5){ //Update p1 to next part
                            echo $this->updateStatusProductIdentification($request->request_id, 1, 6, $dmrpqc_user_id);
                        // }

                            $status = $product_req_checking['status'];
                            DmrpqcProductReqChecking::where('request_id', $request->request_id)->update(['last_updated_by' => $dmrpqc_user_id,
                                                        'status' => $status + 1, 'updated_at' => date('Y-m-d H:i:s'),]);

                            DmrpqcMachineSetupSample::where('request_id', $request->request_id)->update(['last_updated_by' => $dmrpqc_user_id,
                                                        'status' => $status + 1, 'updated_at' => date('Y-m-d H:i:s'),]);
                        // }
                        // Clark Comment 09162024

                        return response()->json(['result' => "Successful"]);
                    }
                }elseif($request->process_status == 6){ //Part 6. Machine Parameter Checkings

                    $machine_param_checking = DmrpqcMachineParameterChecking::select(['request_id', 'status'])->where('request_id', $request->request_id)->first();
                    // Conform: if request_id is not existing in machine setup table
                    if(!isset($machine_param_checking['request_id'])){
                        // if(!isset($machine_param_checking['status'])){ //Update p1 to next part
                            echo $this->updateStatusProductIdentification($request->request_id, 2, 6, $dmrpqc_user_id);
                        // }
                        DmrpqcMachineParameterChecking::insert(['request_id' => $request->request_id,
                                                        'created_by' => $dmrpqc_user_id,
                                                        'last_updated_by' => $dmrpqc_user_id,
                                                        'created_at' => date('Y-m-d H:i:s'),
                                                        'updated_at' => date('Y-m-d H:i:s'),]);

                        return response()->json(['result' => "Successful"]);
                        //Submit: if there is request_id exist in machine setup table and the status is 1(Updated)
                    }elseif(isset($machine_param_checking['request_id'])){
                        if($machine_param_checking['status'] == 2){ //Update p1 to next part
                            echo $this->updateStatusProductIdentification($request->request_id, 1, 7, $dmrpqc_user_id);
                        }
                        $status = $machine_param_checking['status'];
                        DmrpqcMachineParameterChecking::where('request_id', $request->request_id)->update(['last_updated_by' => $dmrpqc_user_id,
                                                'status' => $status + 1, 'updated_at' => date('Y-m-d H:i:s'),]);

                        return response()->json(['result' => "Successful"]);
                    }
                }elseif($request->process_status == 7){ //Part 7. Specifications

                    $specification = DmrpqcSpecification::select(['request_id', 'status'])->where('request_id', $request->request_id)->first();
                    // Conform: if request_id is not existing in machine setup table
                    if(!isset($specification['request_id'])){
                        // if(!isset($specification['status'])){ //Update p1 to next part
                            echo $this->updateStatusProductIdentification($request->request_id, 2, 7, $dmrpqc_user_id);
                        // }
                        DmrpqcSpecification::insert(['request_id' => $request->request_id,
                                                        'created_by' => $dmrpqc_user_id,
                                                        'last_updated_by' => $dmrpqc_user_id,
                                                        'created_at' => date('Y-m-d H:i:s'),
                                                        'updated_at' => date('Y-m-d H:i:s'),]);

                        return response()->json(['result' => "Successful"]);
                        //Submit: if there is request_id exist in machine setup table and the status is 1(Updated)
                    }elseif(isset($specification['request_id'])){
                        // if($specification['status'] == 3){ //Update p1 to next part
                            echo $this->updateStatusProductIdentification($request->request_id, 1, 8, $dmrpqc_user_id);
                        // }
                        DmrpqcSpecification::where('request_id', $request->request_id)->update(['last_updated_by' => $dmrpqc_user_id,
                                                    'updated_at' => date('Y-m-d H:i:s'),]);

                        return response()->json(['result' => "Successful"]);
                    }
                }elseif($request->process_status == 8){ //Part 8. Completion Activity
                    $completion_activity = DmrpqcCompletionActivity::select(['request_id', 'status'])->where('request_id', $request->request_id)->first();
                    // Conform: if request_id is not existing in machine setup table
                    if(!isset($completion_activity['request_id'])){
                        // if(!isset($completion_activity['status'])){ //Update p1 to next part
                            echo $this->updateStatusProductIdentification($request->request_id, 2, 8, $dmrpqc_user_id);
                        // }
                        DmrpqcCompletionActivity::insert(['request_id' => $request->request_id,
                                                        'created_by' => $dmrpqc_user_id,
                                                        'last_updated_by' => $dmrpqc_user_id,
                                                        'created_at' => date('Y-m-d H:i:s'),
                                                        'updated_at' => date('Y-m-d H:i:s'),]);

                        return response()->json(['result' => "Successful"]);
                        //Submit: if there is request_id exist in machine setup table and the status is 1(Updated)
                    }elseif(isset($completion_activity['request_id'])){
                        // if($completion_activity['status'] == 3){ //Update p1 to next part
                            echo $this->updateStatusProductIdentification($request->request_id, 3, 9, $dmrpqc_user_id);
                        // }
                        DmrpqcCompletionActivity::where('request_id', $request->request_id)->update(['last_updated_by' => $dmrpqc_user_id,
                                                    'updated_at' => date('Y-m-d H:i:s'),]);

                        return response()->json(['result' => "Successful"]);
                    }
                }
                else{
                    return response()->json(['result' => "Error"]);
                }
        }else{
            return response()->json(['result' => "Session Expired"]);
        }

    }

    //====================================== DOWNLOAD FILE ======================================
    public function DownloadFile(Request $request, $id){
        $dieset_condition = DmrpqcDiesetCondition::where('request_id', $id)->first();
        $file =  storage_path() . "/app/public/PartsDrawingUploadFile/" . $dieset_condition->parts_drawing;
        // $headers = array(
        //     'Content-Type: application/octet-stream',
        //   );
        return Response::download($file, $dieset_condition->parts_drawing);
    }
}
