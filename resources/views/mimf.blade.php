@php $layout = 'layouts.admin_layout'; @endphp
@auth
    @extends($layout)
    @section('title', 'Material Issuance Monitoring Form')
    @section('content_page')
        <style type="text/css">
            table.table tbody td{
                padding: 4px 4px;
                margin: 1px 1px;
                font-size: 13px;
                vertical-align: middle;
            }

            table.table thead th{
                padding: 4px 4px;
                margin: 1px 1px;
                font-size: 13px;
                text-align: center;
                vertical-align: middle;
            }

            .scanQrBarCode{
                position: absolute;
                opacity: 0;
            }
            .input_hidden{
                position: absolute;
                opacity: 0;
            }

            .slct{
                pointer-events: none;
            }
        </style>
        @php
            date_default_timezone_set('Asia/Manila');
        @endphp

        <div class="content-wrapper"> <!-- Content Wrapper. Contains page content -->
            <section class="content-header"> <!-- Content Header (Page header) -->
                <div class="container-fluid"><!-- Container-fluid -->
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Material Issuance Monitoring Form</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Material Issuance Monitoring Form</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.Container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content"><!-- Content -->
                <div class="container-fluid"><!-- Container-fluid -->
                    <div class="row"><!-- Row -->
                        <div class="col-12"><!-- Col -->
                            <div class="card"><!-- General form elements -->
                                <div class="card-header">
                                    Material Issuance Monitoring Table
                                </div>

                                <div class="card-body"><!-- Start Page Content -->
                                    <div class="text-right">
                                        <button button 
                                            type="button" class="btn btn-dark mb-3" 
                                            id="buttonAddMimf" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalMimf" 
                                            data-bs-keyboard="false">
                                            <i class="fa fa-plus fa-md"></i>
                                            New Request
                                        </button>
                                    </div>
                                    <div class="table-responsive"><!-- Table responsive -->
                                        <table id="tblMimf" class="table table-sm table-bordered table-striped table-hover"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Action</th>
                                                    <th>CTRL No.</th>
                                                    <th>Date Issuance</th>
                                                    <th>YEC P.O No</th>
                                                    <th>PMI P.O No</th>
                                                    <th>Prod'n Qty.</th>
                                                    <th>Device Code</th>
                                                    <th>Device Name</th>
                                                    <th>PO Bal.</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div><!-- /.Table responsive -->
                                </div><!-- /.End Page Content -->
                            </div><!-- /.Card -->
                        </div><!-- /.Col -->
                    </div><!-- /.Row -->
                </div><!-- /.Container-fluid -->
            </section><!-- /.Content -->
        </div>

        <!-- Start MIMF Modal -->
        <div class="modal fade" id="modalMimf" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-edit"></i>Material Issuance Monitoring Form</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="post" id="formMimf" autocomplete="off">
                        @csrf
                        <input type="text" class="col-2 input_hidden mimfClass" id="txtMimfId" name="mimf_id" placeholder="For MIMF ID" readonly>
                        <input type="text" class="col-2 input_hidden mimfClass clearReceivedPo" id="txtPpsPoReceivedId" name="pps_po_rcvd_id" placeholder="For PO Received ID" readonly>
                        <input type="text" class="col-2 input_hidden mimfClass" id="txtCreateEdit" name="create_edit" placeholder="Check if CREATE or EDIT" readonly>
                        <div class="modal-body">
                            <div class="row"><!-- Start Row MIMF Data -->
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100"><strong class="ml-4">Date of Issuance</strong></span>
                                        </div>
                                        <input type="date" class="form-control" id="dateMimfDateOfInssuance" name="mimf_date_issuance" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" readonly>
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="Reset Every Month&#013;Format: Year-Month-000.">&nbsp;</i>
                                                <strong>
                                                    Control No.
                                                </strong>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="txtMimfControlNo" name="mimf_control_no" readonly>
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <strong>
                                                    <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="PO Received Order No.">&nbsp;</i>
                                                    PMI PO No.
                                                </strong>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control mimfClass" id="txtMimfPmiPoNo" name="mimf_pmi_po_no">
                                    </div>

                                    <div class="input-group mb-3 d-none">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100"><strong class="ml-4">Created By</strong></span>
                                        </div>
                                        <input type="text" class="form-control" id="txtMimfCreatedBy" name="mimf_created_by" value="@php echo Auth::user()->firstname.' '.Auth::user()->lastname; @endphp" readonly>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <strong>
                                                    <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="PO Received Order Quantity.">&nbsp;</i>
                                                    Prod'n Quantity
                                                </strong>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control mimfClass clearReceivedPo" id="txtMimfProdnQuantity" name="mimf_prodn_quantity" readonly>
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <strong>
                                                    <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="PO Received Item Code.">&nbsp;</i>
                                                    Device Code
                                                </strong>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control mimfClass clearReceivedPo" id="txtMimfDeviceCode" name="mimf_device_code" readonly>
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <strong>
                                                    <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="PO Received Item Name.">&nbsp;</i>
                                                    Device Name
                                                </strong>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control mimfClass clearReceivedPo" id="txtMimfDeviceName" name="mimf_device_name" readonly>
                                    </div>
                                </div>
                            </div><!-- /.End Row MIMF Data -->

                            <div class="col-12 input-group border-top save-button">
                                <div class="col-6 mt-3">
                                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                                </div>
                                <div class="col-6 d-flex justify-content-end mt-3">
                                    <button type="submit" id="btnMimf" class="btn btn-dark">
                                        <i id="iBtnMimfIcon" cPpsRequestlass="fa fa-save"></i> Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive p-3 d-none" id="tblPpsRequest">
                        <div class="card shadow">
                            <div class="modal-body">
                                <div class="col-12 d-flex justify-content-end">
                                    <button button 
                                        type="button" class="btn btn-dark mb-3" 
                                        id="buttonAddMimfPpsRequest" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalMimfPpsRequest" 
                                        data-bs-keyboard="false">
                                        <i class="fa fa-plus fa-md"></i>
                                        New PPS Request
                                    </button>
                                </div>
                                <table id="tblMimfPpsRequest"
                                    class="table table-bordered table-hover"
                                    style="width: 100%; font-size: 85%">
                                    <thead>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>PPS Request Control No.</th>
                                            <th>Material Type</th>
                                            <th>Material Code</th>
                                            <th>Quantity <br> from Inventory</th>
                                            <th>Request Qty.</th>
                                            {{-- <th>Request Pins</th> --}}
                                            <th>Needed KGS</th>
                                            <th>Virgin Material</th>
                                            <th>Recycled</th>
                                            <th>Prod'n</th>
                                            <th>Delivery</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div><!-- /.End MIMF Modal -->

        <!-- /.Start MIMF PPS Request Modal -->
        <div class="modal fade" id="modalMimfPpsRequest" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-edit"></i>Material Issuance Monitoring Issuance Request</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form method="post" id="formMimfPpsRequest" autocomplete="off">
                        @csrf
                        <div class="row p-3 input_hidden">
                            <input type="text" class="col-2" id="getMimfId" name="mimf_id" placeholder="For MIMF ID" readonly>
                            <input type="text" class="col-2 reset-value" id="txtPpsWhseId" name="pps_whse_id" placeholder="For PPS Warehouse ID" readonly>
                            <input type="text" class="col-2 reset-value" id="txtMimfPpsRequestId" name="pps_request_id" placeholder="MIMF PPS Request ID" readonly>
                            <input type="text" class="col-2 reset-value" id="txtGetMimfDeviceCode" name="get_mimf_device_name" placeholder="MIMF PPS Request ID" readonly>
                        </div>
                        <div class="modal-body">
                            <div class="row"><!-- Start Row MIMF Data -->
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <strong>
                                                    <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="Device -> Process on Matrix VS. PPS Warehouse Material Type.">&nbsp;</i>
                                                    Material Type
                                                </strong>
                                            </span>
                                        </div>
                                        <select class="form-control get-mimf-device" id="slctMimfMaterialType" name="mimf_material_type" required></select>    
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <strong>
                                                    <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="PPS Warehouse Part Number.">&nbsp;</i>
                                                    Material Code
                                                </strong>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control reset-value" id="txtMimfMaterialCode" name="mimf_material_code" readonly required>
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <strong>
                                                    <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="PPS Warehouse Transaction.">&nbsp;</i>
                                                    Quantity from Inventory
                                                </strong>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control reset-value" id="txtMimfQuantityFromInventory" name="mimf_quantity_from_inventory" readonly>
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100"><strong class="ml-4">Request Quantity</strong></span>
                                        </div>
                                        <input type="text" class="form-control reset-value" id="txtRequestQuantity" name="request_quantity">
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <strong>
                                                    <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="Prodn Qty(PO Received Order Quantity) * Shot Weight(Die-Set) / No. of Cavity(Die-Set) / 1000.">&nbsp;</i>
                                                    Needed KGS
                                                </strong>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control reset-value" id="txtMimfNeededKgs" name="mimf_needed_kgs" readonly>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="input-group mb-3 moldingOnly">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <strong>
                                                    <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="Needed KGS(MIMF) * Virgin Material(Matrix).">&nbsp;</i>
                                                    Virgin Material
                                                </strong>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control reset-value" id="txtMimfVirginMaterial" name="mimf_virgin_material" readonly>
                                    </div>

                                    <div class="input-group mb-3 moldingOnly">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <strong>
                                                    <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="Needed KGS(MIMF) * Recycle(Matrix).">&nbsp;</i>
                                                    Recycled
                                                </strong>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control reset-value" id="txtMimfRecycled" name="mimf_recycled" readonly>
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100"><strong class="ml-4">Prod'n</strong></span>
                                        </div>
                                        <input type="date" class="form-control reset-value" id="dateMimfProdn" name="date_mimf_prodn">
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100"><strong class="ml-4">Delivery</strong></span>
                                        </div>
                                        <input type="date" class="form-control reset-value" id="dateMimfDelivery" name="mimf_delivery">
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100"><strong class="ml-4">Remarks</strong></span>
                                        </div>
                                        <input type="text" class="form-control reset-value" id="txtMimfRemark" name="mimf_remark">
                                    </div>

                                    <div class="input-group mb-3 d-none">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100"><strong class="ml-4">Created By</strong></span>
                                        </div>
                                        <input type="text" class="form-control" id="txtCreatedBy" name="created_by" value="@php echo Auth::user()->firstname.' '.Auth::user()->lastname; @endphp" readonly>
                                    </div>
                                </div>
                            </div><!-- /.End Row MIMF Data -->

                            <div class="col-12 input-group">
                                <div class="col-6 mt-3">
                                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                                </div>
                                <div class="col-6 d-flex justify-content-end mt-3">
                                    <button type="submit" id="btnMimfPpsRequest" class="btn btn-dark">
                                        <i id="iBtnMimfPpsRequestIcon" class="fa fa-save"></i> Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.End MIMF PPS Request Modal -->


    @endsection

    @section('js_content')
        <script type="text/javascript">
            let dataTableMimf
            let dataTableMimfPpsRequest
            let mimfID
            let getMimfPoNo

            $(document).ready(function() {
                $('.select2bs5').select2({
                    theme: 'bootstrap-5'
                })          
            })

            // ================================================================================
            // ================================= MIMF REQUEST =================================
            // ================================================================================
            // ============================= START MIMF DATA TABLE ============================
            // ================================================================================
            dataTableMimf = $("#tblMimf").DataTable({
                "processing"    : false,
                "serverSide"    : true,
                "destroy"       : true,
                "ajax" : {
                    url: "view_mimf",
                    // data: function(param){
                        // param.  =  
                    // }
                },

                "columns":[
                    /*0*/{ "data" : "action", orderable:false, searchable:false }, 
                    /*1*/{ "data" : "control_no" },
                    /*3*/{ "data" : "date_issuance" },
                    /*4*/{ "data" : "yec_po_no" },
                    /*5*/{ "data" : "pmi_po_no" },
                    /*6*/{ "data" : "prodn_qty" },
                    /*7*/{ "data" : "device_code" },
                    /*8*/{ "data" : "device_name" },
                    /*9*/{ "data" : "po_balance" }
                ],
                "columnDefs": [
                    // "targets": 'invis',
                ],
                // drawCallback: function (data) {
                //     const apiDataTable= this.api()
                //     if(data.oAjaxData.mimfCategory == 1){
                //         apiDataTable.columns([13,14]).visible(false)
                //     }else{
                //         apiDataTable.columns([13,14]).visible(true)
                //     }
                // }
            })

            $('#buttonAddMimf').click(function(event){
                event.preventDefault()
                $('#txtCreateEdit').val('create')
                $.ajax({
                    url: 'get_control_no',
                    method: 'get',
                    beforeSend: function(){
                    
                    },
                    success: function (response) {
                        let getNewControlNo = response['newControlNo']
                        $('#txtMimfControlNo').val(getNewControlNo)

                    }
                })
            })

            $("#txtMimfPmiPoNo").keypress(function(){
                $(this).val($(this).val().toUpperCase())
            })

            $("#txtMimfPmiPoNo").keyup(function() {
                let getValue = $(this).val()
                $.ajax({
                    url: 'get_pmi_po',
                    method: 'get',
                    data: {
                        'getValue'      : getValue,
                        'mimfCategory'  : $('#txtMimfStatus').val()
                    },
                    beforeSend: function(){
                    },
                    success: function(response){
                        let getPoReceivedPmiPo = response['getPoReceivedPmiPo']

                        if(getPoReceivedPmiPo.length > 0){
                            $('#txtPpsPoReceivedId').val(getPoReceivedPmiPo[0].id)
                            $('#txtMimfProdnQuantity').val(getPoReceivedPmiPo[0].OrderQty)
                            $('#txtMimfDeviceCode').val(getPoReceivedPmiPo[0].ItemCode)
                            $('#txtMimfDeviceName').val(getPoReceivedPmiPo[0].ItemName)
                        }else{
                            $('.clearReceivedPo').val('')
                        }
                    }
                })
            })

            $(document).on('click', '.actionEditMimf', function(e){
                e.preventDefault()
                mimfID = $(this).attr('mimf-id')
                poReceivedID = $(this).attr('po_received-id')
                $('#txtCreateEdit').val('edit')

                $('#txtMimfId').val(mimfID)
                $('#txtPpsPoReceivedId').val(poReceivedID)
                $('#txtMimfPmiPoNo').prop('readonly', false)
                GetMimfById(mimfID)
            })

            $('#formMimf').submit(function (e) { 
                e.preventDefault()
                UpdateMimf()
            })

            $('#modalMimf').on('hidden.bs.modal', function() {
                $('.save-button').removeClass('d-none')
                $('.mimfClass').val('')
                $('#tblPpsRequest').addClass('d-none')
                $('#txtMimfPmiPoNo').attr('readonly', false)
            })

             // ================================================================================
            // =============================== MIMF PPS REQUEST ===============================
            // ================================================================================
            $('#modalMimfPpsRequest').on('shown.bs.modal', function(event){
                let getID       = $('#txtMimfId').val()
                getMimfPoNo     = $('#txtMimfPmiPoNo').val()
                $('#txtGetMimfDeviceCode').val($('#txtMimfDeviceCode').val())

                $('#getMimfId').val(getID)
                GetPpdMaterialType($('.get-mimf-device'))

                if($('#slctMimfMaterialType').val() != '') {
                    setTimeout(() => {
                        GetQtyFromInventory($('#slctMimfMaterialType').val());
                    }, 300);
                }
            })

            $('#modalMimfPpsRequest').on('hidden.bs.modal', function() {
                $('.reset-value').val('')
                $('#txtRequestQuantity').val('')
                $('#slctMimfMaterialType').val('').trigger('change')
            })

            $(document).on('click', '.actionMimfPpsRequest', function(e){
                e.preventDefault()
                mimfID = $(this).attr('mimf-id')
                let stampingMatrix = $(this).attr('mimf_stamping_matrix-id')

                $('#txtMimfId').val(mimfID)

                $('#tblPpsRequest').removeClass('d-none')
                $('#txtMimfPmiPoNo').prop('readonly', true)
                $('.save-button').addClass('d-none')

                GetMimfById(mimfID)

                // ======================= START DLABEL DATA TABLE =======================
                dataTableMimfPpsRequest = $("#tblMimfPpsRequest").DataTable({
                    "processing"    : false,
                    "serverSide"    : true,
                    "destroy"       : true,
                    "ajax" : {
                        url: "view_mimf_pps_request",
                        data: function (pamparam){
                            pamparam.mimfID = mimfID;
                        },
                    },

                    "columns":[
                        /*0*/{ "data" : "action", orderable:false, searchable:false },
                        /*1*/{ "data" : "pps_control_no"},
                        /*2*/{ "data" : "material_type" },
                        /*3*/{ "data" : "material_code" },
                        /*4*/{ "data" : "qty_invt" },
                        /*5*/{ "data" : "request_qty" },
                        // /*6*/{ "data" : "request_pins_pcs" },
                        /*7*/{ "data" : "needed_kgs" },
                        /*8*/{ "data" : "virgin_material" },
                        /*9*/{ "data" : "recycled" },
                        /*10*/{ "data" : "prodn" },
                        /*11*/{ "data" : "delivery" },
                        /*12*/{ "data" : "remarks" },
                    ],
                    "columnDefs": [
                        // { visible: false, targets: 0 }                
                    ],
                    // drawCallback: function (data,mimfStatus) {
                    //     const apiDataTable= this.api()
                        
                    //     if(data.json.input.status == 1){
                    //         if(data.json.input.category == 1){
                    //             apiDataTable.column(6).visible(false)
                    //             apiDataTable.columns([5,7]).visible(true)
                    //         }else{
                    //             apiDataTable.column(6).visible(true)
                    //             apiDataTable.columns([5,7]).visible(false)
                    //         }
                    //         apiDataTable.columns([8,9]).visible(false)
                    //     }else{
                    //         apiDataTable.column(6).visible(false)
                    //         apiDataTable.columns([5,7]).visible(true)
                    //         apiDataTable.columns([8,9]).visible(true)
                    //     }
                    // }
                });// END DLABEL DATA TABLE
            })

            $('#slctMimfMaterialType').change(function (e) { 
                e.preventDefault();

                if($(this).val() != null){
                    GetQtyFromInventory($(this).val())
                }
            });

            $("#txtRequestQuantity").keypress(function(){
                $(this).val()
                CheckRequestQtyForIssuance(getMimfPoNo)
            })

            $("#txtRequestQuantity").keyup(function() {
                let getPartialQuantity = $(this).val()
                CheckRequestQtyForIssuance(getMimfPoNo)
                if(getPartialQuantity != ''){
                    $.ajax({
                        url: 'get_pps_request_partial_quantity',
                        method: 'get',
                        data: {
                            'getPartialQuantity'        : getPartialQuantity,
                            'getMimfMatrixItemCode'     : $('#txtMimfDeviceCode').val(),
                            'getPpsRequestMaterialType' : $('#slctMimfMaterialType').val(),
                        },
                        beforeSend: function(){
                        },
                        success: function(response){
                            let calculate = response['calculate'];
                            let calcualateDieset = response['calcualateDieset'];                                
                            let calculated = calculate.toFixed(2)
                            let virginMaterialComputation = (calculated*calcualateDieset[0].ppd_matrix_info.virgin_percent)/100
                            let recyledComputation = (calculated*calcualateDieset[0].ppd_matrix_info.recycle_percent)/100

                            $('#txtMimfNeededKgs').val(calculated)
                            $('#txtMimfVirginMaterial').val(virginMaterialComputation.toFixed(2))
                            $('#txtMimfRecycled').val(recyledComputation.toFixed(2))
                        }
                    })
                }else{
                    $('#txtMimfNeededKgs').val('')
                }
            })

            $(document).on('click', '.actionEditMimfPpsRequest', function(e){
                e.preventDefault()
                let mimfPpsRequestID = $(this).attr('mimf_pps_request-id')
                $('#txtMimfPpsRequestId').val(mimfPpsRequestID)

                GetMimfPpsRequestById(mimfPpsRequestID)
            })

            $('#formMimfPpsRequest').submit(function (e) { 
                e.preventDefault()
                CreateUpdateMimfPpsRequest()
            })


        </script>
    @endsection
@endauth
