<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/send-data', function (Request $request) {
    $data = $request->all();

    // Validation rules
    $validate_array = [
        'print_delivery_key_no' => 'required|string',
        'print_lot_no' => 'required|string',
        'print_package_category' => 'required|string',
        'print_packed_by' => 'required|string',
        'print_delivery_place' => 'required|string',
        'print_shipment_date' => 'required|date',
        'print_delivery_date' => 'required|date'
    ];
    // test
    if($request->print_category == 1) {
        $validate_array['print_normal_qty'] = 'required|string';
        $validate_array['print_normal_total_qty'] = 'required|string';
    }else if($request->print_category == 2){
        $validate_array['print_dynamic_sticker_count'] = 'required|string';
    }else if($request->print_category == 3){
        $validate_array['print_custom_qty'] = 'required|string';
        $validate_array['print_custom_total_qty'] = 'required|string';
        $validate_array['print_custom_package_count'] = 'required|string';
    }
    $validator = Validator::make($data, $validate_array);

    // If validation fails
    if ($validator->fails()) {
        return response()->json([
            'validation' => 'hasError',
            'error' => $validator->messages()
        ], 422); // <-- Set proper HTTP error status
    }

    $receiverUrl = 'http://rapid/PPSF3TicketingSystem/generate_sticker_info_clark.php'; // Change this to your receiver URL
    $response = Http::post($receiverUrl, $request->all());

    if($response->successful()){
        return response()->json([
            'status' => 'success',
            'message' => 'Data sent successfully',
            'response' => $response->body()
        ]);
    }else{
        return 'false';
    }
});
