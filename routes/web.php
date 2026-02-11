<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\MimfController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MimfV2Controller;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\DmrpqcTsController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\UserLevelController;
use App\Http\Controllers\AssemblyFviController;
use App\Http\Controllers\DefectsInfoController;
use App\Http\Controllers\DhdMonitoringController;
use App\Http\Controllers\IqcInspectionController;
use App\Http\Controllers\OQCInspectionController;
use App\Http\Controllers\QualificationController;
use App\Http\Controllers\MaterialProcessController;
use App\Http\Controllers\ProductionRuncardController;
use App\Http\Controllers\PreShipmentController;
use App\Http\Controllers\DeliveryUpdateController;
use App\Http\Controllers\DeliveryConfirmationController;
use App\Http\Controllers\LottraceabilityController;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/link', function () {
    return 'link';
})->name('link');

Route::view('/','index')->name('login');
Route::view('/login','index')->name('login');
Route::view('/dashboard','dashboard')->name('dashboard');
Route::view('/ipqc_inspection_assembly','ipqc_inspection_assembly')->name('ipqc_inspection_assembly');

//QC Routes
Route::view('/iqc_inspection','iqc_inspection')->name('iqc_inspection');

/* ADMIN VIEW */
Route::view('/user','user')->name('user');
Route::view('/defectsinfo','defectsinfo')->name('defectsinfo');
Route::view('/change_pass_view','change_password')->name('change_pass_view');
Route::view('/materialprocess','materialprocess')->name('materialprocess');
Route::view('/process','process')->name('process');

/* QUALIFICATION VIEW */
Route::view('/qualifications','qualification')->name('qualifications');

/* PRODUCTION RUNCARD VIEW */
Route::view('/production_runcard','production_runcard')->name('production_runcard');
Route::view('/oqc_inspection','oqc_inspection')->name('oqc_inspection');

/* DEFECTS INFO */
Route::controller(DefectsInfoController::class)->group(function () {
    Route::get('/view_defectsinfo', 'view_defectsinfo')->name('view_defectsinfo');
    Route::post('/add_defects', 'add_defects')->name('add_defects');
    Route::post('/update_defects_status', 'UpdateDefectsStatus')->name('update_defects_status');
    Route::get('/get_defects_by_id', 'get_defects_by_id')->name('get_defects_by_id');

});

/* DMRPQC INFO */
Route::view('/dmrpqc_ts','dmrpqc_ts')->name('dmrpqc_ts');
// Route::view('/dmrpqc_f3','dmrpqc_f3')->name('dmrpqc_f3');

// Route::get('/dmrpqc_ts/{factory}', function ($factory) {
//     // Optional: Validate the factory type
//     if (!in_array($factory, ['F1', 'F3'])) {
//         abort(404); // or redirect to default
//     }
    
//     // You can pass other default data as needed
//     return view('dmrpqc_ts', compact('factory'));
// })->name('dmrpqc_ts');

/* MACHINE PARAMETER */
Route::view('/machine_parameter','machine_parameter')->name('machine_parameter');

Route::view('/dhd_checksheet','dhd_checksheet')->name('dhd_checksheet');
Route::view('/dhd_monitoring','dhd_monitoring')->name('dhd_monitoring');
Route::view('/dhd1_monitoring','dhd1_monitoring')->name('dhd1_monitoring');
// Route::post('/add_dhd_monitoring', [DhdMonitoringController::class,'add_dhd_monitoring'])->name('add_dhd_monitoring');
Route::post('/add_dhd_monitoring', [DhdMonitoringController::class,'add_dhd_monitoring'])->name('add_dhd_monitoring');

/* DHD Monitoring */
Route::controller(DhdMonitoringController::class)->group(function () {
    Route::get('/view_dhd_monitoring', 'viewDhdMonitoring')->name('view_dhd_monitoring');
    Route::get('/get_dhd_monitoring', 'get_dhd_monitoring')->name('get_dhd_monitoring');
});

/* MIMF */
Route::view('/Material_Issuance_Monitoring_Form','mimf')->name('Material_Issuance_Monitoring_Form');
Route::view('/Material_Issuance_Monitoring_Form_V2','mimf_v2')->name('Material_Issuance_Monitoring_Form_V2');


/* FINAL VISUAL VIEW */
Route::view('/shipment','shipment')->name('shipment');
Route::view('/assy_fvi','assembly_fvi')->name('assy_fvi');

/* DELIVERY UPDATE */
Route::view('/delivery_update','delivery_update')->name('delivery_update');
Route::controller(DeliveryUpdateController::class)->group(function () {
    Route::get('/search_po_delivery_update', 'search_po_delivery_update')->name('search_po_delivery_update');
    Route::get('/view_delivery_update', 'view_delivery_update')->name('view_delivery_update');
    Route::get('/get_employee_name', 'get_employee_name')->name('get_employee_name');
    Route::get('/get_lot_no', 'get_lot_no')->name('get_lot_no');
    Route::post('/save_delivery_update', 'save_delivery_update')->name('save_delivery_update');
    Route::get('/get_delivery_update_by_id', 'get_delivery_update_by_id')->name('get_delivery_update_by_id');

});
/* * PPTS VIEW */
Route::view('/ppts_user','ppts_user')->name('ppts_user');
Route::view('/ppts_matrix','ppts_matrix')->name('ppts_matrix');
Route::view('/ppts_oqc_inspection','ppts_oqc_inspection')->name('ppts_oqc_inspection');
Route::view('/ppts_packing_and_shipping','ppts_packing_and_shipping')->name('ppts_packing_and_shipping');
Route::view('/ppts_export_packing_and_shipping','ppts_export_packing_and_shipping')->name('ppts_export_packing_and_shipping');

/* PRE-SHIPMENT */
Route::view('/rapid_pre_shipment','rapid_pre_shipment')->name('rapid_pre_shipment');
Route::view('/rapidx_pre_shipment','rapidx_pre_shipment_validation')->name('rapidx_pre_shipment');

// ASSEMBLY FVI
Route::controller(AssemblyFviController::class)->group(function () {
    Route::get('/view_visual_inspection', 'view_visual_inspection')->name('view_visual_inspection');
    Route::get('/view_fvi_runcards', 'view_fvi_runcards')->name('view_fvi_runcards');
    Route::get('/get_fvi_doc', 'get_fvi_doc')->name('get_fvi_doc');
    Route::get('/get_assembly_line', 'get_assembly_line')->name('get_assembly_line');
    Route::post('/save_visual_details', 'save_visual_details')->name('save_visual_details');
    Route::get('/get_visual_details', 'get_visual_details')->name('get_visual_details');
    Route::get('/get_runcard_details', 'get_runcard_details')->name('get_runcard_details');
    Route::post('/save_runcard', 'save_runcard')->name('save_runcard');
    Route::get('/get_fvi_details_by_id', 'get_fvi_details_by_id')->name('get_fvi_details_by_id');
    Route::get('/validate_runcard_output', 'validate_runcard_output')->name('validate_runcard_output');
    Route::post('/submit_to_oqc_lot_app', 'submit_to_oqc_lot_app')->name('submit_to_oqc_lot_app');
    Route::get('/search_po', 'search_po')->name('search_po');
});

/* DMRPQC CONTROLLER*/
Route::controller(DmrpqcTsController::class)->group(function () {
    Route::get('/view_dmrpqc', 'ViewDmrpqc')->name('view_dmrpqc');
    Route::post('/add_request', 'AddRequest')->name('add_request');
    Route::post('/delete_request', 'DeleteRequest')->name('delete_request');
    Route::get('/get_data_for_dashboard', 'GetDataForDashboard')->name('get_data_for_dashboard');
    Route::post('/update_dieset_conditon_data', 'UpdateDiesetConditionData')->name('update_dieset_conditon_data');
    Route::post('/update_dieset_conditon_checking_data', 'UpdateDiesetConditionCheckingData')->name('update_dieset_conditon_checking_data');
    Route::post('/update_machine_setup_data', 'UpdateMachineSetupData')->name('update_machine_setup_data');
    Route::post('/update_product_req_checking_data', 'UpdateProductReqCheckingData')->name('update_product_req_checking_data');
    Route::post('/update_machine_param_checking_data', 'UpdateMachineParamCheckingData')->name('update_machine_param_checking_data');
    Route::post('/update_specifications_data', 'UpdateSpecificationsData')->name('update_specifications_data');
    Route::post('/update_completion_data', 'UpdateCompletionData')->name('update_completion_data');
    Route::post('/update_parts_drawing_data', 'UpdatePartsDrawingData')->name('update_parts_drawing_data');
    Route::post('/update_status_of_dieset_request', 'UpdateStatusOfDiesetRequest')->name('update_status_of_dieset_request');
    Route::get('/get_name_by_session', 'GetNameBySession')->name('get_name_by_session');
    Route::get('/get_pps_db_data_by_item_code', 'GetPpsDbDataByItemCode')->name('get_pps_db_data_by_item_code');
    Route::get('/get_dmrpqc_details_id', 'GetDmrpqcDetailsId')->name('get_dmrpqc_details_id');
    Route::get('/download_file/{id}', 'DownloadFile')->name('download_file');
    Route::get('/get_users_by_position', 'GetUsersByPosition')->name('get_users_by_position');
});

/* PROCESS */
Route::controller(ProcessController::class)->group(function(){
    Route::get('/view_process', 'view_process');
    Route::post('/add_process', 'add_process');
    Route::post('/update_status', 'update_status');
    Route::get('/get_process_by_id', 'get_process_by_id');
});

// DEVICE CONTROLLER
Route::controller(DeviceController::class)->group(function(){
    Route::get('/view_devices','view_devices');
    Route::post('/add_device','add_device');
    Route::get('/get_device_by_id','get_device_by_id');
    Route::post('/change_device_stat','change_device_stat');
});

/* STATION */
Route::controller(StationController::class)->group(function(){
    Route::get('view_station', 'view_station')->name('view_station');
    Route::post('save_station', 'save_station')->name('save_station');
    Route::get('get_station_details_by_id', 'get_station_details_by_id')->name('get_station_details_by_id');
    Route::get('update_status', 'update_status')->name('update_status');

});

// MATERIAL PROCESS CONTROLLER
Route::controller(MaterialProcessController::class)->group(function () {
    Route::get('/view_material_process_by_device_id', 'view_material_process_by_device_id');
    Route::get('/get_mat_proc_for_add', 'get_mat_proc_for_add');
    Route::get('/get_step', 'get_step');
    Route::get('/get_mat_proc_data', 'get_mat_proc_data');
    Route::post('/add_material_process', 'add_material_process');
    Route::post('/change_mat_proc_status', 'change_mat_proc_status');
});

/* USER CONTROLLER */
Route::controller(UserController::class)->group(function () {
    Route::post('/sign_in', 'sign_in')->name('sign_in');
    Route::post('/rapidx_sign_in_admin', 'rapidxAutoSignIn')->name('rapidx_sign_in_admin');
    Route::post('/sign_out', 'sign_out')->name('sign_out');
    Route::post('/change_pass', 'change_pass')->name('change_pass');
    Route::post('/change_user_stat', 'change_user_stat')->name('change_user_stat');
    Route::get('/view_users', 'view_users');
    Route::post('/add_user', 'add_user');
    Route::get('/get_user_by_id', 'get_user_by_id');
    Route::get('/get_user_by_en', 'get_user_by_en');
    Route::get('/get_user_list', 'get_user_list');
    Route::get('/get_user_by_batch', 'get_user_by_batch');
    Route::get('/get_user_by_stat', 'get_user_by_stat');
    Route::post('/edit_user', 'edit_user');
    Route::post('/reset_password', 'reset_password');
    Route::get('/generate_user_qrcode', 'generate_user_qrcode');
    Route::post('/import_user', 'import_user');

    Route::get('/get_emp_details_by_id', 'get_emp_details_by_id')->name('get_emp_details_by_id');
});

/* PRODUCTION RUNCARD Controller */
Route::controller(ProductionRuncardController::class)->group(function(){
    Route::get('/view_production_runcard', 'viewProdRuncard')->name('view_production_runcard');
    Route::get('/view_prod_runcard_station', 'viewProdRuncardStations')->name('view_prod_runcard_station');
    Route::get('/get_po_from_ppsdb', 'GetPOFromPPSDB')->name('get_po_from_ppsdb');
    Route::get('/get_machine_no_from_matrix', 'GetMachineNoFromMatrix')->name('get_machine_no_from_matrix');
    Route::get('/search_po_from_ppsdb', 'searchPoFromPpsDb')->name('search_po_from_ppsdb');
    Route::get('/validate_material_lot_number', 'ValidateMatLotNumber')->name('validate_material_lot_number');
    Route::post('/add_production_runcard_data', 'addProdRuncardData')->name('add_production_runcard_data');
    Route::get('/get_prod_runcard_data', 'getProdRuncardData')->name('get_prod_runcard_data');
    Route::post('/add_runcard_station_data', 'addProdRuncardStationData')->name('add_runcard_station_data');
    Route::get('/get_data_from_matrix', 'GetMatrixDataByDevice')->name('get_data_from_matrix');
    Route::get('/chck_existing_stations', 'CheckExistingStations')->name('chck_existing_stations');
    Route::get('/chck_existing_sub_stations', 'CheckExistingSubStations')->name('chck_existing_sub_stations');
    Route::get('/get_prod_runcard_qr_code', 'GetProdRuncardQrCode')->name('get_prod_runcard_qr_code');
    Route::post('/update_prod_runcard_status', 'UpdateProdRuncardStatus')->name('update_prod_runcard_status');
    Route::post('/submit_prod_runcard', 'SubmitProdRuncard')->name('submit_prod_runcard');
    Route::get('/get_mode_of_defect_for_prod', 'GetModeOfDefect')->name('get_mode_of_defect_for_prod');
});

//Qualification Controller
Route::controller(QualificationController::class)->group(function () {
    Route::get('/validate_user', 'Validateuser')->name('validate_user');
    Route::get('/get_devices_from_quali', 'GetDevicesFromQualifications')->name('get_devices_from_quali');
    Route::get('/verify_production_lot', 'VerifyProductionLot')->name('verify_production_lot');
    Route::get('/view_qualification_data', 'ViewIpqcData')->name('view_qualification_data');
    Route::get('/get_qualification_data', 'GetQualificationsData')->name('get_qualification_data');
    Route::post('/add_qualification_details', 'AddQualificationDetails')->name('add_qualification_details');
    Route::post('/update_qualification_details_status', 'UpdateQualificationDetailsStatus')->name('update_qualification_details_status');
    Route::get('/download_quali_file/{id}', 'DownloadFile')->name('download_quali_file');
});

/* USER LEVEL CONTROLLER */
Route::get('/get_user_levels',  [UserLevelController::class, 'get_user_levels']);

//IQC Inspection
Route::controller(IqcInspectionController::class)->group(function () {

    Route::get('/load_iqc_inspection', 'loadIqcInspection')->name('load_iqc_inspection');
    Route::get('/get_iqc_inspection_by_judgement', 'getIqcInspectionByJudgement')->name('get_iqc_inspection_by_judgement');
    Route::get('/load_whs_transaction', 'loadWhsTransaction')->name('load_whs_transaction');
    Route::get('/load_whs_details', 'loadWhsDetails')->name('load_whs_details');
    Route::get('/get_iqc_inspection_by_id', 'getIqcInspectionById')->name('get_iqc_inspection_by_id');
    Route::get('/get_whs_receiving_by_id', 'getWhsReceivingById')->name('get_whs_receiving_by_id');
    Route::get('/get_family', 'getFamily')->name('get_family');
    Route::get('/get_inspection_level', 'getInspectionLevel')->name('get_inspection_level');
    Route::get('/get_aql', 'getAql')->name('get_aql');
    Route::get('/get_lar_dppm', 'getLarDppm')->name('get_lar_dppm');
    Route::get('/get_mode_of_defect', 'getModeOfDefect')->name('get_mode_of_defect');
    Route::get('/view_coc_file_attachment/{id}', 'viewCocFileAttachment')->name('view_coc_file_attachment');

    Route::post('/save_iqc_inspection', 'saveIqcInspection')->name('save_iqc_inspection');
});

//OQC Inspection
Route::controller(OQCInspectionController::class)->group(function () {
    Route::get('/production_runcard_details', 'ProdnRuncardDetails')->name('production_runcard_details');
    Route::get('/view_oqc_inspection', 'viewOqcInspection')->name('view_oqc_inspection');
    Route::get('/view_oqc_inspection_history', 'viewOqcInspectionHistory')->name('view_oqc_inspection_history');
    // Route::get('/view_oqc_inspection_second_stamping', 'viewOqcInspectionSecondStamping')->name('view_oqc_inspection_second_stamping');
    Route::post('/update_oqc_inspection', 'updateOqcInspection')->name('update_oqc_inspection');
    Route::get('/get_oqc_inspection_by_id', 'getOqcInspectionById')->name('get_oqc_inspection_by_id');
    Route::get('/get_oqc_family', 'getFamily')->name('get_oqc_family');
    Route::get('/get_oqc_inspection_type', 'getInspectionType')->name('get_oqc_inspection_type');
    Route::get('/get_oqc_inspection_level', 'getInspectionLevel')->name('get_oqc_inspection_level');
    Route::get('/get_oqc_severity_inspection', 'getSeverityInspection')->name('get_oqc_severity_inspection');
    Route::get('/get_oqc_aql', 'getAQL')->name('get_oqc_aql');
    Route::get('/get_oqc_inspection_customer', 'getCustomer')->name('get_oqc_inspection_customer');
    Route::get('/get_oqc_inspection_mod', 'getMOD')->name('get_oqc_inspection_mod');
    Route::get('/scan_user_id', 'scanUserId')->name('scan_user_id');
});

Route::controller(MimfController::class)->group(function () {
    // Route::get('/view_mimf', 'viewMimf')->name('view_mimf');
    // Route::get('/get_control_no', 'getControlNo')->name('get_control_no');
    // Route::get('/get_pmi_po', 'getPmiPoFromPoReceived')->name('get_pmi_po');
    // Route::post('/update_mimf', 'updateMimf')->name('update_mimf');
    // Route::get('/get_mimf_by_id', 'getMimfById')->name('get_mimf_by_id');

    // Route::get('/view_mimf_pps_request', 'viewMimfPpsRequest')->name('view_mimf_pps_request');
    // Route::get('/get_ppd_material_type', 'getPpdMaterialType')->name('get_ppd_material_type');
    // Route::get('/get_pps_warehouse_inventory', 'getPpsWarehouseInventory')->name('get_pps_warehouse_inventory');
    // Route::get('/get_pps_request_partial_quantity', 'getPpsRequestPartialQuantity')->name('get_pps_request_partial_quantity');
    // Route::get('/check_request_qty_for_issuance', 'checkRequestQtyForIssuance')->name('check_request_qty_for_issuance');
    // Route::post('/create_update_mimf_pps_request', 'createUpdateMimfPpsRequest')->name('create_update_mimf_pps_request');
    // Route::get('/get_mimf_pps_request_by_id', 'getMimfPpsRequestById')->name('get_mimf_pps_request_by_id');
});

Route::controller(MimfV2Controller::class)->group(function () {
    // IMPORT P.O
    Route::post('/import', 'import');

    // REQUEST MIMF
    Route::get('/view_mimf_v2', 'viewMimfV2')->name('view_mimf_v2');
    Route::get('/get_control_no_v2', 'getControlNoV2')->name('get_control_no_v2');
    Route::get('/get_pmi_po', 'getPmiPoFromPoReceived')->name('get_pmi_po');
    Route::post('/update_mimf_v2', 'createUpdateMimfV2')->name('update_mimf_v2');
    Route::get('/get_mimf_by_id_v2', 'getMimfByIdV2')->name('get_mimf_by_id_v2');
    // Route::get('/employee_id', 'employeeID')->name('employee_id');

    // REQUEST MIMF ISSUANCE
    Route::get('/view_mimf_pps_request', 'viewMimfPpsRequest')->name('view_mimf_pps_request');
    Route::get('/get_ppd_material_type', 'getPpdMaterialType')->name('get_ppd_material_type');
    Route::get('/get_pps_warehouse_inventory', 'getPpsWarehouseInventory')->name('get_pps_warehouse_inventory');
    Route::get('/get_pps_request_partial_quantity', 'getPpsRequestPartialQuantity')->name('get_pps_request_partial_quantity');
    Route::get('/check_request_qty_for_issuance', 'checkRequestQtyForIssuance')->name('check_request_qty_for_issuance');
    Route::post('/create_update_mimf_pps_request', 'createUpdateMimfPpsRequest')->name('create_update_mimf_pps_request');
    Route::get('/get_mimf_pps_request_by_id', 'getMimfPpsRequestById')->name('get_mimf_pps_request_by_id');
});

Route::controller(ShipmentController::class)->group(function() {
// viewShipmentData
    Route::get('/view_shipment_data', 'viewShipmentData')->name('view_shipment_data');
    Route::post('/add_shipment_data', 'addShipmentData')->name('add_shipment_data');
    Route::get('/get_po_received_details', 'getPOReceivedDetails')->name('get_po_received_details');
    Route::get('/get_shipment_data', 'getShipmentData')->name('get_shipment_data');
    Route::get('/load_preshipment_details', 'loadPreshipmentDetails')->name('load_preshipment_details');
});


/* PRESHIPMENT Controller */
Route::controller(PreShipmentController::class)->group(function(){
    Route::get('/get_preshipment_destination', 'getPreShipmentDestination')->name('get_preshipment_destination');
    Route::get('/get_control_numbers', 'getControlNumbers')->name('get_control_numbers');
    Route::get('/view_pre_shipment', 'viewPreShipment')->name('view_pre_shipment');
    Route::get('/view_pre_shipment_details', 'viewPreShipmentDetails')->name('view_pre_shipment_details');
    Route::get('/view_search_po_result', 'viewSearchPoResult')->name('view_search_po_result');
    Route::post('/add_pre_shipment_data', 'addPreShipmentData')->name('add_pre_shipment_data');
    Route::get('/get_pre_shipment_data', 'getPreShipmentData')->name('get_pre_shipment_data');
    Route::post('/add_preshipmt_details_data', 'addPreShipmentDetailsData')->name('add_preshipmt_details_data');
    Route::get('/get_users_by_pos', 'getUsersByPos')->name('get_users_by_pos');
    Route::get('/get_preshipment_by_id', 'getPreShipDetailsById')->name('get_preshipment_by_id');
    Route::get('/get_preshipment_to_print', 'getPreShipmentForPrint')->name('get_preshipment_to_print');
    Route::post('/get_preview_qr_code', 'getPreShipDataForPreview')->name('get_prod_runcard_qr_code');
    Route::get('/get_po_from_delivery_update', 'GetPOFromDeliveryUpdate')->name('get_po_from_delivery_update');
    Route::get('/get_data_from_delivery_update', 'searchPoDeliveryUpdate')->name('get_data_from_delivery_update');

    // Route::get('/get_po_from_ppsdb', 'GetPOFromPPSDB')->name('get_po_from_ppsdb');
    // Route::get('/get_machine_no_from_matrix', 'GetMachineNoFromMatrix')->name('get_machine_no_from_matrix');
    // Route::get('/search_po_from_ppsdb', 'searchPoFromPpsDb')->name('search_po_from_ppsdb');
    // Route::get('/validate_material_lot_number', 'ValidateMatLotNumber')->name('validate_material_lot_number');
    // Route::get('/get_data_from_matrix', 'GetMatrixDataByDevice')->name('get_data_from_matrix');
    // Route::get('/chck_existing_stations', 'CheckExistingStations')->name('chck_existing_stations');
    // Route::get('/chck_existing_sub_stations', 'CheckExistingSubStations')->name('chck_existing_sub_stations');
    // Route::post('/update_prod_runcard_status', 'UpdateProdRuncardStatus')->name('update_prod_runcard_status');
    // Route::post('/submit_prod_runcard', 'SubmitProdRuncard')->name('submit_prod_runcard');
    // Route::get('/get_mode_of_defect_for_prod', 'GetModeOfDefect')->name('get_mode_of_defect_for_prod');
});

Route::controller(DeliveryConfirmationController::class)->group(function(){
    Route::get('/search_po_received_details', 'searchPOReceivedDetails')->name('search_po_received_details');
    Route::post('/add_shipment_date', 'addShipmentDate')->name('add_shipment_date');
});

/* DELIVERY UPDATE */
Route::view('/delivery_confirmation','delivery_confirmation')->name('delivery_confirmation');

// Route::post('/send-data-via-web', function (Request $request) {
//     $apiUrl = url('/api/send-data'); // This calls the API route

//     $response = Http::post($apiUrl, $request->all());

//     return response()->json([
//         'status' => 'success',
//         'message' => 'Request forwarded via web.php',
//         'api_response' => $response->json()
//     ]);
// });

/* TRACEABILITY REPORT */
Route::view('/lot_traceability_report','lot_traceability_report')->name('lot_traceability_report');

Route::controller(LottraceabilityController::class)->group(function () {
    Route::get('/export_lot_traceability_report/{date_from}/{date_to}', 'exportLotTraceabilityReport')->name('export_lot_traceability_report');
});
