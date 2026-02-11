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
                /* text-align: center; */
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
                            <div class="card card-dark"><!-- General form elements -->
                                {{-- <ul class="nav nav-tabs p-2" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#mimfRequest" type="button" role="tab">MIMF Table</button>
                                    </li>
                                </ul> --}}

                                <div class="card-body"><!-- Start Page Content -->
                                    <div class="tab-content" id="myTabContent"> <!-- tab-content -->
                                        <div class="tab-pane fade show active" id="mimfRequest" role="tabpanel">
                                            <div class="col-12 input-group mb-3">
                                                <div class="col-12 d-flex justify-content-between">
                                                    <button button
                                                        type="button" class="btn btn-info mb-3"
                                                        id="buttonImportPO"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalImportPo"
                                                        data-bs-keyboard="false">
                                                        <i class="fa fa-file fa-md"></i>
                                                        Import P.O
                                                    </button>

                                                    <button button
                                                        type="button" class="btn btn-dark mb-3"
                                                        id="buttonAddMimf"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalMimf"
                                                        data-bs-keyboard="false">
                                                        <i class="fa fa-plus fa-md"></i>
                                                        New MIMF Request
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="table-responsive"><!-- Table responsive -->
                                                <table id="tblMimf" class="table table-sm table-bordered table-striped table-hover w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>Action</th>
                                                            <th>Control Number</th>
                                                            <th>Date<br>Issuance</th>
                                                            <th>YEC P.O<br> Number</th>
                                                            <th>PMI P.O<br> Number</th>
                                                            <th>Device<br>Code</th>
                                                            <th>Device<br>Name</th>
                                                            <th>Prod'n<br>Quantity</th>
                                                            <th>PO<br>Balance</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div><!-- /.Table responsive -->
                                        </div>
                                    </div> <!-- /.tab-content -->
                                </div><!-- /.End Page Content -->
                            </div><!-- /.Card -->
                        </div><!-- /.Col -->
                    </div><!-- /.Row -->
                </div><!-- /.Container-fluid -->
            </section><!-- /.Content -->
        </div><!-- /.Content-wrapper -->

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
                            {{-- <div class="row">    
                                <div class="col-6 sticker">
                                    <div class="form-check form-check-inline mb-3">
                                        <input class="form-check-input uncheck" type="radio" name="ul_sticker" id="radioBtnWithUl" value="1" required disabled>
                                        <label class="form-check-label" for="inlineRadio1">With UL</label>
                                    </div>
                                    <div class="form-check form-check-inline mb-3">
                                        <input class="form-check-input uncheck" type="radio" name="ul_sticker" id="radioBtnWithoutUl" value="2" required disabled>
                                        <label class="form-check-label" for="inlineRadio2">Without UL</label>
                                    </div>
                                </div>
                            </div> --}}

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
                                                    P.O Quantity
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

                            <div class="modal-footer justify-content-between save-button">
                                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                                <button type="submit" id="btnMimf" class="btn btn-dark">
                                    <i id="iBtnMimfIcon" class="fa fa-save"></i> Save
                                </button>
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
                                    class="table table-bordered table-hover w-100"
                                    style="font-size: 85%">
                                    <thead>
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>Rapid PPS Request Control No.</th>
                                            <th>Material Type</th>
                                            <th>Material Code</th>
                                            <th>Quantity <br> from Inventory</th>
                                            <th>Request Qty.</th>
                                            <th>Needed <br> KGS/Quantity</th>
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
                            <input type="text" class="col-2" id="getMimfId" name="get_mimf_id" placeholder="For MIMF ID" readonly>
                            <input type="text" class="col-2" id="getMimfDeviceCode" name="get_device_code" placeholder="For MIMF ID" readonly>
                            <input type="text" class="col-2 reset-value" id="txtPpsWhseId" name="pps_whse_id" placeholder="For PPS Warehouse ID" readonly>
                            <input type="text" class="col-2 reset-value" id="txtMimfPpsRequestId" name="mimf_pps_request_id" placeholder="MIMF PPS Request ID" readonly>
                            <input type="number" class="col-2 reset-value" id="txtMimfPpsRequestPreviousAllowedQuantity" name="mimf_pps_request_updated_allowed_quantity" placeholder="Updated Allowed Quantity" readonly>
                            <input type="number" class="col-2 reset-value" id="txtMimfPpsRequestPreviousBalance" name="mimf_pps_request_updated_balance" placeholder="Updated Balance" readonly>
                            <input type="text" class="col-2 reset-value" id="txtMimfPpsRequestCreateUpdateStatus" name="mimf_pps_request_create_update_status" placeholder="STATUS: 0 - Create / 1 - Update" readonly>
                        </div>
                        <div class="modal-body">
                            <div class="row"><!-- Start Row MIMF Data -->
                                <div class="col-6">
                                    <div class="input-group mb-3 moldingOnly">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100"><strong class="ml-4">Product Category</strong></span>
                                        </div>
                                        <select class="form-control" id="slctMoldingProductCategory" name="molding_product_category" required>
                                            <option selected disabled value="">-----</option>
                                            <option value="1">Resin</option>
                                            <option value="2">Contact</option>
                                            <option value="3">ME</option>
                                        </select>
                                    </div>
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
                                        <input type="text" class="form-control reset-value molding-reset-value" id="txtMimfQuantityFromInventory" name="mimf_quantity_from_inventory" readonly>
                                    </div>

                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100"><strong class="ml-4">Request Quantity</strong></span>
                                        </div>
                                        <input type="text" class="form-control reset-value molding-reset-value auto-compute" id="txtRequestQuantity" name="request_quantity" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="9" readonly>
                                        <span class="input-group-text moldingMultiplier d-none">x</span>
                                        <input type="text" class="form-control reset-value molding-reset-value moldingMultiplier d-none auto-compute" id="multiplier" name="multiplier" placeholder="multiplier" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="7" readonly>
                                    </div>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <strong id="exceptContactMe">
                                                    <i class="fa-solid fa-circle-question moldingNeededKGS d-none" data-bs-toggle="tooltip" data-bs-html="true" title="Request Quantity * Shot Weight(Die-Set) / No. of Cavity(Die-Set) / 1000.">&nbsp;</i>
                                                    Needed KGS
                                                </strong>
                                                <strong class="d-none" id="contactMeOnly">
                                                    <i class="fa-solid fa-circle-question moldingMultiplier d-none" data-bs-toggle="tooltip" data-bs-html="true" title="Request Quantity * multiplier.">&nbsp;</i>
                                                    Needed Quantity
                                                </strong>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control reset-value molding-reset-value" id="txtMimfNeededKgs" name="mimf_needed_kgs" readonly>
                                    </div>

                                    <div class="input-group second-stamping-pins-pcs mb-3 d-none">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <strong>
                                                    <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="Manually encode.">&nbsp;</i>
                                                    Request PINS/PCS
                                                </strong>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control reset-value" id="txtMimfRequestPinsPcs" name="mimf_request_pins_pcs">
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="input-group mb-3 moldingOnly">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <strong>
                                                    Allowed Quantity
                                                </strong>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control reset-value molding-reset-value" id="txtMimfMoldingAllowedQuantity" name="mimf_molding_allowed_quantity" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="6" readonly required>
                                        <span class="input-group-text moldingOnly d-none"><strong>Balance:</strong></span>
                                        <input type="text" class="form-control reset-value molding-reset-value" id="leftQty" name="left_quantity" placeholder="Balance" onkeypress="return event.charCode >= 48 && event.charCode <= 57" maxlength="6" value=".0" readonly>
                                    </div>

                                    <div class="input-group mb-3 moldingOnly resinOnly d-none">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <strong>
                                                    <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="Needed KGS(MIMF) * Virgin Material(Matrix).">&nbsp;</i>
                                                    Virgin Material
                                                </strong>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control reset-value molding-reset-value" id="txtMimfVirginMaterial" name="mimf_virgin_material" readonly>
                                    </div>

                                    <div class="input-group mb-3 moldingOnly resinOnly d-none">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">
                                                <strong>
                                                    <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="Needed KGS(MIMF) * Recycle(Matrix).">&nbsp;</i>
                                                    Recycled
                                                </strong>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control reset-value molding-reset-value" id="txtMimfRecycled" name="mimf_recycled" readonly>
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

                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                                <button type="submit" id="btnMimfPpsRequest" class="btn btn-dark d-none">
                                    <i id="iBtnMimfPpsRequestIcon" class="fa fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="modalImportPo" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" id="formMimfImportPo" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel">Import P.O from Excel</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
    
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="file" class="form-label">Select Excel File</label>
                                <input class="form-control" type="file" name="import_po" id="file" accept=".xlsx, .xls, .csv" required>
                            </div>
                        </div>
    
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-dark">Import</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @section('js_content')
        <script type="text/javascript">
            let dataTableMimf
            let dataTableMimfPpsRequest
            let mimfID
            let getMimfPoNo
            let updateStatusQty

            $(document).ready(function() {
                $('.select2bs5').select2({
                    theme: 'bootstrap-5'
                })

                setTimeout(() => {
                    // GetPpsPoReceivedItemCode($('.ppsPoReceived'))
                }, 500);

                // =====================================================================
                // =========================== MIMF REQUEST ============================
                // =====================================================================
                // ======================= START MIMF DATA TABLE =======================
                dataTableMimf = $("#tblMimf").DataTable({
                    "processing"    : false,
                    "serverSide"    : true,
                    "destroy"       : true,
                    "order": [[ 1, "desc" ]],
                    "ajax" : {
                        url: "view_mimf_v2",
                        data: function(param){

                        }
                    },

                    "columns":[
                        /*0*/{ "data" : "action", orderable:false, searchable:false },
                        /*1*/{ "data" : "control_no" },
                        /*2*/{ "data" : "date_issuance" },
                        /*3*/{ "data" : "yec_po_no" },
                        /*4*/{ "data" : "pmi_po_no" },
                        /*5*/{ "data" : "device_code" },
                        /*6*/{ "data" : "device_name" },
                        /*7*/{ "data" : "prodn_qty" },
                        /*8*/{ "data" : "po_balance" }
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
                        url: 'get_control_no_v2',
                        method: 'get',

                        beforeSend: function(){

                        },
                        success: function (response) {
                            let getNewControlNo = response['newControlNo']
                            $('#txtMimfControlNo').val(getNewControlNo)

                        }
                    })
                })

                // $('#modalMimf').on('shown.bs.modal', function(event){
                //     $('input[name="ul_sticker"]').prop('disabled', false)
                // })

                $('#modalMimf').on('hidden.bs.modal', function() {
                    // $('.uncheck').prop({disabled:false, checked:false })
                    $('.save-button').removeClass('d-none')
                    $('.mimfClass').val('').removeClass('is-invalid')
                    $('#tblPpsRequest').addClass('d-none')
                    $('#txtMimfPmiPoNo').attr('readonly', false)
                    // $('input[name="ul_sticker"]').prop('disabled', true)
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
                        },
                        beforeSend: function(){
                        },
                        success: function(response){
                                let getPoReceivedPmiPoForMolding = response['getPoReceivedPmiPoForMolding']

                                if(getPoReceivedPmiPoForMolding.length > 0){
                                    $('#txtPpsPoReceivedId').val(getPoReceivedPmiPoForMolding[0].id)
                                    $('#txtMimfProdnQuantity').val(getPoReceivedPmiPoForMolding[0].OrderQty)
                                    $('#txtMimfDeviceCode').val(getPoReceivedPmiPoForMolding[0].ItemCode)
                                    $('#txtMimfDeviceName').val(getPoReceivedPmiPoForMolding[0].ItemName)
                                }else{
                                    $('.clearReceivedPo').val('')
                                }
                        }
                    })
                })

                $(document).on('click', '.actionEditMimf', function(e){
                    e.preventDefault()

                    $('#txtCreateEdit').val('edit')
                    mimfID = $(this).attr('mimf-id')
                    mimfStatus = $(this).attr('mimf-status')
                    poReceivedID = $(this).attr('po_received-id')

                    $('#txtMimfId').val(mimfID)
                    $('#txtMimfStatus').val(mimfStatus)
                    $('#txtPpsPoReceivedId').val(poReceivedID)

                    $('#txtMimfPmiPoNo').prop('readonly', false)

                    GetMimfById(mimfID)
                })

                $('#formMimf').submit(function (e) {
                    e.preventDefault()
                    CreateUpdateMimf()
                })

                $(document).on('click', '.actionMimfPpsRequest', function(e){
                    e.preventDefault()
                    mimfID = $(this).attr('mimf-id')
                    let balance = $(this).attr('balance')

                    if(balance == '0'){
                        $('#buttonAddMimfPpsRequest').addClass('d-none')
                    }else{
                        $('#buttonAddMimfPpsRequest').removeClass('d-none')
                    }

                    $('#txtMimfId').val(mimfID)

                    $('#tblPpsRequest').removeClass('d-none')
                    $('#txtMimfPmiPoNo').prop('readonly', true)
                    $('.save-button').addClass('d-none')

                    GetMimfById(mimfID)

                    // setTimeout(() => {
                    //     $('.uncheck').prop('disabled', true)
                    // }, 500);
                    
                    // ======================= START DATA TABLE =======================
                    dataTableMimfPpsRequest = $("#tblMimfPpsRequest").DataTable({
                        "processing"    : false,
                        "serverSide"    : true,
                        "destroy"       : true,
                        "pageLength"    : 100,
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
                        drawCallback: function (data,mimfStatus) {
                            const apiDataTable= this.api()
                        }
                    });// END DATA TABLE
                })

                $('#slctMoldingProductCategory').change(function (e) {
                    e.preventDefault();
                    let disabled = $('#slctMimfMaterialType')
                        $('#multiplier').val('')
                        $('#txtRequestQuantity').val('')
                        $('#txtMimfNeededKgs').val('.0')
                        $('#txtMimfVirginMaterial').val('.0')
                        $('#txtMimfRecycled').val('.0')

                    if( $(this).val() != null){
                        $('#txtMimfMoldingAllowedQuantity').attr('readonly', false)
                        if($('#txtMimfMoldingAllowedQuantity').val() != ''){
                            $('.auto-compute').attr('readonly', false)
                        }else{
                            $('.auto-compute').attr('readonly', true)
                        }

                        if($(this).val() == 1){
                            disabled.removeClass('slct');
                            $('#exceptContactMe').removeClass('d-none')
                            $('#contactMeOnly').addClass('d-none')
                            $('.resinOnly').removeClass('d-none')
                            $('.moldingNeededKGS').removeClass('d-none')
                            $('.moldingMultiplier').addClass('d-none')
                            $('#multiplier').attr('readonly', true)
                        }else{
                            disabled.removeClass('slct');
                            $('#exceptContactMe').addClass('d-none')
                            $('#contactMeOnly').removeClass('d-none')
                            $('.resinOnly').addClass('d-none')
                            $('.moldingNeededKGS').addClass('d-none')
                            $('.moldingMultiplier').removeClass('d-none')
                        }
                    }
                });

                $('#buttonAddMimfPpsRequest').click(function (e) { 
                    e.preventDefault();
                    $('#txtMimfPpsRequestCreateUpdateStatus').val('0')
                });

                $('#modalMimfPpsRequest').on('shown.bs.modal', function(event){
                    let getID                   = $('#txtMimfId').val()
                        getMimfPoNo             = $('#txtMimfPmiPoNo').val()

                    $('#getMimfId').val(getID)
                    $('#getMimfDeviceCode').val($('#txtMimfDeviceCode').val())

                    GetPpdMaterialType($('.get-mimf-device'))

                    if($('#slctMimfMaterialType').val() != '') {
                        setTimeout(() => {
                            console.log('SHOW MODAL: MATERIAL TYPE IS NOT EQUAL TO ""');
                            GetQtyFromInventory($('#slctMimfMaterialType').val());
                        }, 500);
                    }
                    
                })

                $('#modalMimfPpsRequest').on('hidden.bs.modal', function() {
                    console.log('HIDE MODAL - Material Issuance Monitoring Issuance Request');
                    $('#multiplier').val('')
                    $('.reset-value').val('')
                    $('#txtRequestQuantity').val('').attr('readonly', true)
                    $('.moldingMultiplier').addClass('d-none')
                    $('#txtMimfMoldingAllowedQuantity').attr('readonly', true)
                    $('#slctMimfMaterialType').val('').trigger('change')
                    $('#slctMoldingProductCategory').val('').trigger('change')
                })

                $('#slctMimfMaterialType').change(function (e) {
                    e.preventDefault();
                    console.log('object');
                    $('#txtMimfMoldingAllowedQuantity').val('')
                    $('#leftQty').val('.0')
                    if($(this).val() != null){
                        GetQtyFromInventory($(this).val())
                        console.log('ONCHANGE MATERIAL TYPE');
                        setTimeout(() => {
                            console.log('CheckRequestQtyForIssuance FIRST');
                            CheckRequestQtyForIssuance(
                                $('#txtMimfId').val(),
                                $('#txtMimfMaterialCode').val(),
                                $('#slctMoldingProductCategory').val(),
                                $('#txtMimfVirginMaterial').val(),
                                $('#txtMimfNeededKgs').val(),
                                $('#txtMimfPpsRequestId').val(),
                            )                        
                        }, 555);
                    }
                    if($('#slctMimfMaterialType').val() != null){
                        $('.auto-compute').attr('readonly', false)
                        $('#txtMimfMoldingAllowedQuantity').attr('readonly', false)
                    }else{
                        $('.auto-compute').attr('readonly', true)
                        $('#txtMimfMoldingAllowedQuantity').attr('readonly', true)
                    }
                });

                $(".auto-compute").keyup(function() {
                    console.log('0');
                    let getPartialQuantity = $(this).val()
                    updateStatusQty = 0
                    if($('#txtMimfStatus').val() != 1){
                        setTimeout(() => {
                            console.log('CheckRequestQtyForIssuance FOURTH');

                            CheckRequestQtyForIssuance(
                                $('#txtMimfId').val(),
                                $('#txtMimfMaterialCode').val(),
                                $('#slctMoldingProductCategory').val(),
                                $('#txtMimfVirginMaterial').val(),
                                $('#txtMimfNeededKgs').val(),
                                $('#txtMimfPpsRequestId').val(),
                                updateStatusQty
                            )
                        }, 500);
                    }
                    if(getPartialQuantity != ''){
                        $.ajax({
                            url: 'get_pps_request_partial_quantity',
                            method: 'get',
                            data: {
                                'getPartialQuantity'        : getPartialQuantity,
                                'getMimfMatrixItemCode'     : $('#txtMimfDeviceCode').val(),
                                'getPpsRequestMaterialType' : $('#slctMimfMaterialType').val(),
                                'getMoldingProductCategory' : $('#slctMoldingProductCategory').val()
                            },
                            beforeSend: function(){
                            },
                            success: function(response){
                                let calculate = response['calculate'];
                                let getDeviceCode = response['getDeviceCode'];
                                let calcualateDieset = response['calcualateDieset'];
                                let shotWgtAlert = response['shotWgtAlert'];
                                let noOfCavAlert = response['noOfCavAlert'];
                                let virginMaterialComputation
                                let recyledComputation
                                let calculated

                                if(shotWgtAlert == 1){
                                    alert('Check the Shot Weight in Rapid PPS Die-Set')
                                    return;
                                }else if(noOfCavAlert == 1){
                                    alert('Check the No of Cavity in Rapid PPS Die-Set')
                                    return;
                                }
                                else{
                                    if($('#slctMoldingProductCategory').val() != null && $('#slctMoldingProductCategory').val() != 1){ 
                                        calculated = $('#txtRequestQuantity').val() * $('#multiplier').val()
                                    }else{
                                        console.log('calculate: ', calculate);
                                        calculated = calculate.toFixed(2)
                                        if(calcualateDieset[0].ppd_matrix_info != null){
                                            virginMaterialComputation = (calculated*calcualateDieset[0].ppd_matrix_info.virgin_percent)/100
                                            $('#btnMimfPpsRequest').removeClass('d-none')
                                        }else{
                                            $('#btnMimfPpsRequest').addClass('d-none')
                                            alert('There is a mismatch between the material code and the Die-Set R3 code.')
                                        }                                        recyledComputation = (calculated*calcualateDieset[0].ppd_matrix_info.recycle_percent)/100
                                        $('#txtMimfVirginMaterial').val(virginMaterialComputation.toFixed(2))
                                        $('#txtMimfRecycled').val(recyledComputation.toFixed(2))
                                    }

                                    $('#txtMimfNeededKgs').val(calculated)
                                    if(Number($('#txtMimfQuantityFromInventory').val()) < Number(calculated)){ //nmodify
                                        alert('Needed KGS is greater than the Quantity from Inventory')
                                        $('#btnMimfPpsRequest').addClass('d-none')
                                    }else{
                                        $('#btnMimfPpsRequest').removeClass('d-none')
                                    }
                                }

                            }
                        })
                    }else{
                        $('#txtMimfNeededKgs').val('.0')
                        $('#txtMimfVirginMaterial').val('.0')
                        $('#txtMimfRecycled').val('.0')
                    }
                })

                $(".auto-compute").keypress(function(){
                    console.log('1');
                    $(this).val()
                    updateStatusQty = 0
                    if($('#txtMimfStatus').val() != 1){
                        setTimeout(() => {
                            console.log('CheckRequestQtyForIssuance FOURTH');

                            CheckRequestQtyForIssuance(
                                $('#txtMimfId').val(),
                                $('#txtMimfMaterialCode').val(),
                                $('#slctMoldingProductCategory').val(),
                                $('#txtMimfVirginMaterial').val(),
                                $('#txtMimfNeededKgs').val(),
                                $('#txtMimfPpsRequestId').val(),
                                updateStatusQty
                            )
                        }, 500);
                    }
                })

                $('#txtMimfMoldingAllowedQuantity').keypress(function (e) {
                    updateStatusQty = 1
                    if($('#txtMimfMoldingAllowedQuantity').val() != null){
                            $('.auto-compute').attr('readonly', false)
                        }else{
                            $('.auto-compute').attr('readonly', true)
                        }
                    console.log('CheckRequestQtyForIssuance SECOND');
                    CheckRequestQtyForIssuance(
                                $('#getMimfId').val(),
                                $('#txtMimfMaterialCode').val(),
                                $('#slctMoldingProductCategory').val(),
                                $('#txtMimfVirginMaterial').val(),
                                $('#txtMimfNeededKgs').val(),
                                $('#txtMimfPpsRequestId').val(),
                                updateStatusQty
                            )
                });

                $('#txtMimfMoldingAllowedQuantity').keyup(function (e) {
                    updateStatusQty = 1
                    if($('#txtMimfMoldingAllowedQuantity').val() != null){
                            $('.auto-compute').attr('readonly', false)
                        }else{
                            $('.auto-compute').attr('readonly', true)
                        }
                    CheckRequestQtyForIssuance(
                        $('#getMimfId').val(),
                        $('#txtMimfMaterialCode').val(),
                        $('#slctMoldingProductCategory').val(),
                        $('#txtMimfVirginMaterial').val(),
                        $('#txtMimfNeededKgs').val(),
                        $('#txtMimfPpsRequestId').val(),
                        updateStatusQty
                    )      
                });

                $(document).on('click', '.actionEditMimfPpsRequest', function(e){
                    e.preventDefault()
                    let mimfPpsRequestID = $(this).attr('mimf_pps_request-id')
                    let mimfPpsRequestMaterialType = $(this).attr('mimf_pps_request-material_type')
                    $('#txtMimfPpsRequestId').val(mimfPpsRequestID)
                    $('#txtMimfPpsRequestCreateUpdateStatus').val('1')

                    GetMimfPpsRequestById(mimfPpsRequestID)
                    setTimeout(() => {
                        console.log('CLICK EDIT MIMF PPS REQUEST');
                        GetQtyFromInventory(mimfPpsRequestMaterialType);
                    }, 1000);
                })

                $('#formMimfPpsRequest').submit(function (e) {
                    e.preventDefault()
                    CreateUpdateMimfPpsRequest()
                })

                $('#formMimfImportPo').submit(function(e){
                    e.preventDefault();

                    $.ajax({
                        url: 'import',
                        method: 'post',
                        data: new FormData(this),
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            if(response['result'] == 1){
                                $('#formMimfImportPo')[0].reset();
                                $('#modalImportPo').modal('hide');
                                toastr.success('Import Data Successful!');
                                dataTableMimf.draw();
                            }
                            else{
                                toastr.error('Import Failed! Please Check File');
                                $('#modalImportPo').modal('hide');
                            }
                        }
                    });
                })

                $('#modalImportPo').on('hidden.bs.modal', function() {
                    $('#formMimfImportPo')[0].reset();
                })

            })
        </script>
    @endsection
@endauth
