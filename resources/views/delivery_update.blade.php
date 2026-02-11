@php $layout = 'layouts.admin_layout'; @endphp

@auth
    @extends($layout)

    @section('title', 'Delivery Update')
    @section('content_page')
        <style>
            table.table tbody td{
                padding         : 4px 4px;
                margin          : 1px 1px;
                font-size       : 13px;
                vertical-align  : middle;
                /*text-align      : center; */
            }

            table.table thead th{
                padding         : 4px 4px;
                margin          : 1px 1px;
                font-size       : 15px;
                text-align      : center;
                vertical-align  : middle;
            }

            .pointer{
                pointer-events  : none;
            }

            .scanner{
                position    : absolute;
                opacity     : 0;
            }
        </style>

        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Delivery Update</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item active">Delivery Update</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Delivery Details</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <input type="hidden" class="form-control" id="txtMatDrawingNo" readonly>
                                        <input type="hidden" class="form-control" id="txtMatDrawingRev" readonly>
                                        <div class="col-sm-2">
                                            <label>Order Number</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button type="button" class="btn btn-dark scanning" id="btnSearchPO"
                                                        value="0" title="Click to Scan PO Code" data-bs-toggle="modal" data-bs-target="#modalScanning"><i
                                                            class="fa fa-qrcode"></i></button>
                                                </div>
                                                {{-- <input type="text" class="form-control" id="txtSearchPO" value="PR2410141761" readonly> --}}
                                                <input type="text" class="form-control" id="txtSearchPO" value="" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Item Name</label>
                                            <input type="text" class="form-control" id="txtDeviceName" name=""
                                                readonly="">
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Item Code</label>
                                            <input type="text" class="form-control" id="txtDeviceCode"
                                                readonly="">
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Order Qty</label>
                                            <input type="text" class="form-control" id="txtPoQty" readonly="">
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Shipment Date</label>
                                            <input type="text" class="form-control" id="txtShipmentDate" readonly="">
                                        </div>
                                        {{-- <div class="col-sm-2">
                                            <label>Order Balance</label>
                                            <input type="text" class="form-control" id="txtPoBalance" readonly="">
                                        </div> --}}
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <label>Target for S/O:</label>
                                            <input type="text" class="form-control" id="txtTargetSO" readonly="">
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Variance</label>
                                            <input type="text" class="form-control" id="txtVariance" readonly="">
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Total S/O</label>
                                            <input type="text" class="form-control" id="txtTotalSO" readonly="">
                                        </div>
                                    </div>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Delivery Summary</h3>
                                    <button class="btn btn-dark btn-sm" style="float: right;" id="btnAddDelivery"><i class="fa fa-plus"></i> Add Delivery Update
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-hover w-100" id="tblDeliveryUpdate">
                                            <thead>
                                                <tr>
                                                    <th></th> <!-- DATATABLE ID VISIBLE FALSE -->
                                                    <th>Action</th>
                                                    <th>Actual S/O</th>
                                                    <th>Variance</th>
                                                    <th>Lot Number</th>
                                                    <th>PIC</th>
                                                    <th>Applied Date</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- Modal --}}
        <div class="modal fade" id="modalDeliveryUpdate" tabindex="-1" role="dialog" aria-labelledby=""
            aria-hidden="true" data-bs-backdrop="static" style="overflow-y: auto;">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa fa-info-circle text-info"></i> Delivery Update Details</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="row p-2">
                        <div class="modal-body">
                            <form id="formSaveDeliveryUpdate">
                                @csrf
                                <input type="hidden" class="reset-value" id="txtDeliveryUpdateId" name="delivery_update_id" placeholder="Delivery Update ID">
                                <input type="hidden" id="txtPo" name="po_no" placeholder="PO No.">
                                <input type="hidden" id="txtPoReceivedQuantity" name="po_received_quantity" placeholder="PO Received QTY.">
                                <input type="hidden" id="checkPrevActualSo" name="check_prev_actual_so" placeholder="For edit check previous Actual S/O.">
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id=""><strong>Runcard Number</strong></span>
                                            </div>
                                            <select class="form-control lot-no pointer reset-value" id="slctRuncardNum" name="runcard_no" required></select>
                                            <div class="input-group-append">
                                                <button class="btn btn-info scanning" type="button" title="Scan code" id="btnScanProdnRuncardCode" value="1" data-bs-toggle='modal' data-bs-target='#modalScanning'><i class="fa fa-qrcode"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id=""><strong>Package Category</strong></span>
                                            </div>
                                            <select class="form-control reset-value pointer" id="slctPackageCategory" name="package_category" required>
                                                <option value="" selected disabled> --- Select Package Category --- </option>
                                                <option value="Box">Box</option>
                                                <option value="Bundle">Bundle</option>
                                                <option value="Polybag">Polybag</option>
                                                <option value="Reel">Reel</option>
                                                <option value="Tray">Tray</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend w-50">
                                                <label class="input-group-text w-100 d-flex justify-content-between">
                                                    <span id="titleLotNumber">Lot Number</span>
                                                    <!-- Switch Button -->
                                                    <div class="custom-control custom-switch" title="Special Case">
                                                        <input type="checkbox" class="custom-control-input" id="checkBoxLotCategory" name="lot_category" value="0" disabled>
                                                        <label class="custom-control-label" for="checkBoxLotCategory"></label>
                                                    </div>
                                                </label>
                                            </div>
                                            <input type="text" class="form-control reset-value onchange-reset-value pointer" id="txtDeliveryUpdateLotNum" name="lot_no" required readonly>-
                                            <input type="text" class="form-control reset-value onchange-reset-value" id="txtDeliveryUpdateLotNumExt" name="lot_no_ext" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="row prev">
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id=""><strong>Order Quantity</strong></span>
                                            </div>
                                            <input type="text" class="form-control reset-value pointer" id="txtDeliveryUpdatePreviousOrderQty" name="previous_order_quantity" required disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id=""><strong>Actual S/O</strong></span>
                                            </div>
                                            <input type="text" class="form-control reset-value onchange-reset-value" id="txtDeliveryUpdateActualSO" name="actual_so" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row prev">
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id=""><strong>Variance</strong></span>
                                            </div>
                                            <input type="text" class="form-control reset-value onchange-reset-value" id="txtDeliveryUpdateVariance" name="variance" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id=""><strong>Remarks</strong></span>
                                            </div>
                                            <input type="text" class="form-control reset-value onchange-reset-value" id="txtDeliveryUpdateRemarks" name="remarks" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id=""><strong>Person In-charge</strong></span>
                                            </div>
                                            <select class="form-control select-user pointer reset-value onchange-reset-value" name="scan_by" id="scanBy" required></select>
                                            <div class="input-group-append">
                                                <button class="btn btn-info scanning" type="button" title="Scan Employee ID" id="btnScanEmployeeId" value="2" data-bs-toggle="modal" data-bs-target="#modalScanning"><i class="fa fa-qrcode"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div><hr>
                                <div class="d-none" id="deliveryUpdateFooter">
                                    <button type="button" class="btn btn-default btn-sm" data-bs-dismiss="modal" aria-label="Close">Close</button>
                                    <button type="submit" class="btn btn-dark btn-sm float-right" id="btnSubmitDeliveryUpdate">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalScanning" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <input type="text" class="scanner pointer" id="btnValue">
                    <div class="modal-header border-bottom-0 pb-0">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body pt-0">
                        <input type="text" class="scanner w-100" id="txtScanning" name="scanning" autocomplete="off">
                        <div class="text-center text-secondary"><p class="scanningTitle"></p>
                            <h1>
                                <i class="fa fa-qrcode fa-lg"></i>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.Modal -->
    @endsection

    @section('js_content')
        <script>
            let dtDeliveryUpdate
            let checking
            let poNumber
            let firstRow
            let columnFirstValue
            
            $(document).ready(function(){
                dtDeliveryUpdate = $("#tblDeliveryUpdate").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "order": [ 0, "desc" ],
                    "ajax": {
                        url: "view_delivery_update",
                        data: function (param) {
                            param.po = $("#txtSearchPO").val();
                            param.poQty = $("#txtPoQty").val();
                        }
                    },
                    fixedHeader: true,
                    "columns": [
                        { data: "id", visible:false },
                        { data: "action", orderable:false, searchable:false },
                        { data: "actual_so", orderable:false, searchable:false },
                        { data: "variance", orderable:false, searchable:false },
                        { data: "lot_no", 
                            render: {
                            display: function (data, type, row) {
                                // return row.oqc_stamp;
                                if (row.lot_no_ext != null) {
                                    return row.lot_no+'-'+row.lot_no_ext;
                                }
                                else{
                                    return  row.lot_no;
                                }
                            },
                            },
                            orderable:false, searchable:false 
                        },
                        { data: "created_by", orderable:false, searchable:false },
                        { data: "created_at", orderable:false, searchable:false },
                        { data: "remarks", orderable:false, searchable:false}
                    ],
                    "columnDefs": [
                        {
                        },
                    ],
                    "rowCallback": function(row,data,index ){
                        // console.log('row: ', row);
                        // console.log('data: ', data);
                        // console.log('index: ', index);
                    },
                }); //end of dataTableDevices

                $('#btnAddDelivery').on('click', function(){    
                    if($('#txtSearchPO').val() == '' || $('#txtDeviceName').val() == '' || $('#txtDeviceCode').val() == '' || $('#txtPoQty').val() == ''){
                        toastr.error('Please Scan PO Number!');
                        return;
                    }else{
                        checking = 0
                        GetEmployeeName($('.select-user'))
                        GetLotNo($('.lot-no'), $('#txtSearchPO').val(), checking);
                        $('#txtPo').val($('#txtSearchPO').val());    
                        $('#modalDeliveryUpdate').modal('show');
                        $('.prev').removeClass('d-none')

                        firstRow = $('table tbody tr:first');
                        columnFirstValue = firstRow.find('td:eq(2)').text();

                        $('#txtDeliveryUpdatePreviousOrderQty').val(columnFirstValue);
                    }
                });
                
                $('#modalDeliveryUpdate').on('shown.bs.modal', function () {
                    $('#txtPoReceivedQuantity').val($('#txtPoQty').val());
                });

                $('#modalDeliveryUpdate').on('hidden.bs.modal', function(event){
                    $('.reset-value').val('').trigger('change');
                    $('#checkBoxLotCategory').prop({'checked': false, 'disabled': true}).trigger('change')
                    $('#txtDeliveryUpdateLotNum').addClass('pointer').prop('readonly', true)
                    $('#txtDeliveryUpdateLotNumExt').prop('disabled', false).val('')
                    $('#slctPackageCategory').addClass('pointer')
                    $('#txtPoReceivedQuantity').val('');

                    firstRow = $('table tbody tr:first');
                    columnFirstValue = firstRow.find('td:eq(2)').text();

                    $('#txtVariance').val(columnFirstValue)
                });

                $('#modalScanning').on('shown.bs.modal', function () {
                    $('#txtScannedItem').focus();
                });

                $('.scanning').on('click', function(e){
                    e.preventDefault();
                    let buttonValue = $(this).val();
                    $('#btnValue').val(buttonValue)
                    console.log('buttonValue: ', buttonValue);
                    if(buttonValue == 0){
                        $('.scanningTitle').html('Please scan the Order Number.')
                    }else if(buttonValue == 1){
                        $('.scanningTitle').html('Please scan the Runcard Number.')
                    }else{
                        $('.scanningTitle').html('Please scan the Employee ID.')
                    }
                    $('#modalScanning').on('shown.bs.modal', function () {
                        $('#txtScanning').focus();
                        const mdlScanning = document.querySelector("#modalScanning");
                        const inptScanning = document.querySelector("#txtScanning");
                        let focus = false;

                        mdlScanning.addEventListener("mouseover", () => {
                            if (inptScanning === document.activeElement) {
                                focus = true;
                            } else {
                                focus = false;
                            }
                        });

                        mdlScanning.addEventListener("click", () => {
                            if (focus) {
                                inptScanning.focus()
                            }
                        });
                    });
                });

                $('#txtScanning').on('keypress',function(e){
                    if( e.keyCode == 13 ){
                        if($('#btnValue').val() == 0){
                            try {
                                scanQrCodeDetails = JSON.parse($(this).val())
                                if(typeof scanQrCodeDetails == 'object'){
                                    let scanPO = Object.hasOwn(scanQrCodeDetails, 'po_number');
                                    if(scanPO == true){
                                        loadDeliveryUpdateSearchPo(scanQrCodeDetails['po_number']);
                                    }else{
                                        alert('PO No. is not found!');
                                    }
                                }else{
                                    alert('Scan an incorrect sticker!')
                                }
                            }
                            catch (error) {
                                console.log('error: ', error);
                                alert('Scan QR Sticker is not found!');
                            }
                        }else if($('#btnValue').val() == 1){
                            try {
                                scanQrCodeDetails = JSON.parse($(this).val())
                                if(typeof scanQrCodeDetails == 'object'){
                                    let scanChecking = Object.hasOwn(scanQrCodeDetails, 'production_lot');
                                    let orderNumber = $('#txtSearchPO').val();
                                    if(scanChecking == true){
                                        if(scanQrCodeDetails['po_number'] == orderNumber){
                                            $('#slctRuncardNum').val(scanQrCodeDetails['id']).trigger('change')
                                        }else{
                                            alert('Order Number: ' + orderNumber + ' in Delivery Details\n' +'does not match the sticker with \nOrder Number: ' + scanQrCodeDetails['po_number']);
                                        }
                                    }else{
                                        alert('Lot No. is not found!');
                                    }
                                }else{
                                    alert('Scan an incorrect sticker!')
                                }
                            }
                            catch (error) {
                                console.log('error: ', error);
                                alert('Scan QR Sticker is not found!');
                            }
                        }else{
                            let employeeID = $(this).val();
                            
                            $('#scanBy').val(employeeID).trigger('change')
                        }
                        $('#modalScanning').modal('hide');
                    }
                });

                $('#modalScanning').on('hide.bs.modal', function() {
                    $('#txtScanning').val('');
                });

                // $('#btnScanProdnRuncardCode').click(function (e) { 
                //     e.preventDefault();
                //     console.log('object: ', $('#slctPackageCategory').val());
                //     $('.btnDeliveryUpdate').val('0')
                //     if($('#slctPackageCategory').val() != null){
                //         $('#modalScanning').modal('show')
                //     }else{
                //         alert('Select Package Category')
                //     }
                    
                // });

                $('#slctRuncardNum').change(function (e) {
                    e.preventDefault();
                    let runcardNumber   = $('#slctRuncardNum option:selected').text();
                    poNumber            = $('#txtSearchPO').val()
                    checking            = 1

                    if($(this).val() != null){
                        $('#slctPackageCategory').removeClass('pointer')
                    }else{
                        $('#slctPackageCategory').addClass('pointer')
                    }

                    $.ajax({
                        url: "get_lot_no",
                        method: "get",
                        dataType: "json",
                        data: {
                            "poNumber"      : poNumber,
                            "runcardNumber" : runcardNumber,
                            "checking"      : checking
                        },
                        beforeSend: function(){
                        },
                        success: function(response){
                            let runcardDetails      =   response['runcardDetails']
                            let checkDeliveryUpdate =   response['checkDeliveryUpdate']
                            let previousOrderQty    =   $('#txtDeliveryUpdatePreviousOrderQty');
                            let actualSo            =   $('#txtDeliveryUpdateActualSO')

                            if(runcardDetails.length > 0){
                                actualSo.val(runcardDetails[0].shipment_output)
                                
                                if(runcardDetails[0].delivery_update_details == null){
                                    if(checkDeliveryUpdate == true){
                                        $('#txtDeliveryUpdateVariance').val(previousOrderQty.val() - actualSo.val())
                                    }else{
                                        previousOrderQty.val( $('#txtPoQty').val() ).attr('required', true)
                                        $('#txtDeliveryUpdateVariance').val(previousOrderQty.val() - actualSo.val())
                                    }
                                    $('#deliveryUpdateFooter').removeClass('d-none')
                                }else{
                                    $('#deliveryUpdateFooter').addClass('d-none')
                                    if($('.btnDeliveryUpdate').val() == 0){
                                        alert('Already exists!')
                                    }
                                }
                            }
                        }
                    });

                    setTimeout(() => {
                        if($('#txtVariance').val() == ''){
                        let variance    =   Number($('#txtDeliveryUpdateVariance').val())
                            checker     =   variance < 0 ? "Negative" : variance > 0 ? "Positive" : "Zero";

                            console.log('variance: ', variance);
                            console.log('checker: ', checker);
                        }else{
                            let checkPrevActSO  =   Number($('#checkPrevActualSo').val())
                            let checkNewActSO   =   Number($('#txtDeliveryUpdateActualSO').val())
                            let checkVar        =   Number($('#txtVariance').val())
                            let newVariance     =   checkPrevActSO + checkVar

                            console.log('Check previous "Actual S/O": ', checkPrevActSO);
                            console.log('Check new "Actual S/O": ', checkNewActSO);
                            console.log('Check "Variance": ', checkVar);
                            console.log('New "Variance": ', newVariance);

                            if(newVariance < Number($('#txtDeliveryUpdateActualSO').val())){
                                $('#deliveryUpdateFooter').addClass('d-none')
                                if($('#txtDeliveryUpdateId').val() != ''){
                                    alert('The Actual S/O: '+$('#txtDeliveryUpdateActualSO').val()+' \nis higher than \nThe Variance '+newVariance+'')
                                }else{
                                    alert('The Actual S/O: '+$('#txtDeliveryUpdateActualSO').val()+' \nis higher than \nThe Variance: '+checkVar+'')
                                }
                                $('#modalDeliveryUpdate').modal('hide')
                            }else{
                                $('#deliveryUpdateFooter').removeClass('d-none')
                            }
                        }
                    }, 555);
                });

                $('#slctPackageCategory').change(function (e) { 
                    e.preventDefault();
                    $('#checkBoxLotCategory').prop('disabled', false)
                    let getRuncardNumber = $('#slctRuncardNum option:selected').text();
                    if($('#slctPackageCategory').val() == 'Reel'){
                        $('#txtDeliveryUpdateLotNum').val(getRuncardNumber)
                    }else{
                        if (getRuncardNumber.includes('-')) {
                            let splitValue = getRuncardNumber.split('-');
                            console.log('splitValue: ', splitValue);
                            let LotNo = splitValue[0];
                            let startTime = splitValue[1];
                            let endTime = splitValue[2];
                            let timeRanges = [
                                { start: "0730", end: "0930", count: 1, message: "The current time is between 07:30 AM and 09:30 AM" },
                                { start: "0930", end: "1130", count: 1, message: "The current time is between 09:30 AM and 11:30 AM" },
                                { start: "1130", end: "1330", count: 2, message: "The current time is between 11:30 AM and 01:30 PM" },
                                { start: "1330", end: "1530", count: 2, message: "The current time is between 01:30 PM and 03:30 PM" },
                                { start: "1530", end: "1730", count: 3, message: "The current time is between 03:30 PM and 05:30 PM" },
                                { start: "1730", end: "1930", count: 3, message: "The current time is between 05:30 PM and 07:30 PM" },
                                { start: "1930", end: "2130", count: 4, message: "The current time is between 07:30 PM and 09:30 PM" },
                                { start: "2130", end: "2330", count: 4, message: "The current time is between 09:30 PM and 11:30 PM" },
                                { start: "2330", end: "0130", count: 5, message: "The current time is between 11:30 PM and 01:30 AM" },
                                { start: "0130", end: "0330", count: 5, message: "The current time is between 01:30 AM and 03:30 AM" },
                                { start: "0330", end: "0530", count: 6, message: "The current time is between 03:30 AM and 05:30 AM" },
                                { start: "0530", end: "0730", count: 6, message: "The current time is between 05:30 AM and 07:30 AM" }
                            ];

                            let timeRangeCount = 0;
                            let rangeFound = false;

                            for (let range of timeRanges) {
                                if (startTime === range.start && endTime <= range.end) {
                                    timeRangeCount = range.count;
                                    console.log(range.message);
                                    rangeFound = true;
                                    break;
                                }
                            }

                            if (!rangeFound) {
                                alert('The " ' + startTime + '-'+ endTime +' " in \nRuncard Number: '+getRuncardNumber+' \nis outside the expected range.');
                                console.log("The current time is outside the expected range.");
                                $('#txtDeliveryUpdateLotNum').val('');
                            }else{
                                $('#txtDeliveryUpdateLotNum').val(LotNo + '-' + timeRangeCount);
                            }
                        }
                    }

                    // if($('.btnDeliveryUpdate').val() == 0){
                    // if($('#slctRuncardNum').val() == ''){
                    //     $('.onchange-reset-value').val('').trigger('change')
                    //     $('#checkBoxLotCategory').prop('disabled', false)
                    // }
                });

                $('#checkBoxLotCategory').change(function (e) { 
                    e.preventDefault();
                    if($('#checkBoxLotCategory').is(':checked')){
                        $('#checkBoxLotCategory').val('1')
                        $('#titleLotNumber').text('Lot Number - Special Case')
                        $('#txtDeliveryUpdateLotNum').removeClass('pointer').attr('readonly', false)
                        $('#txtDeliveryUpdateLotNumExt').prop('disabled', true).val('')
                    }else{
                        $('#checkBoxLotCategory').val('0')
                        $('#titleLotNumber').text('Lot Number')
                        $('#txtDeliveryUpdateLotNum').addClass('pointer').attr('readonly', true)
                        $('#txtDeliveryUpdateLotNumExt').prop('disabled', false).val('')
                    }
                });

                $(document).on('click', '.btnDeliveryUpdate', function(e){
                    e.preventDefault();

                    let deliveryUpdateId = $(this).attr('delivery_update-id'); 
                    $("#checkBoxLotCategory").prop('disabled', false);
                    $("#txtDeliveryUpdateId").val(deliveryUpdateId);
                    $('.btnDeliveryUpdate').val('1')
                    $('.prev').addClass('d-none')

                    GetDeliveryUpdateInfoByIdToEdit(deliveryUpdateId); 

                    let row = $(this).closest('tr');  
                    let columnValue = row.find('td:eq(2)').text();
                    
                    $('#txtDeliveryUpdateVariance').val(columnValue)
                });

                $('#formSaveDeliveryUpdate').submit(function (e) { 
                    e.preventDefault();
                    saveDeliveryUpdate($('#formSaveDeliveryUpdate'));
                });
            });
        </script>

        @if (in_array(Auth::user()->position, [0,2]) || in_array(Auth::user()->user_level_id, [1,2]))
            <script>
                $('#txtSearchPO').prop('readonly', false);
                $('#txtSearchPO').on('keyup', function(e){
                    if(e.keyCode == 13){
                        loadDeliveryUpdateSearchPo($(this).val());
                    }
                });
            </script>
        @endif
    @endsection
@endauth
