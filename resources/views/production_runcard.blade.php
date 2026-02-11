@php $layout = 'layouts.admin_layout'; @endphp
@auth
    @extends($layout)
    @section('title', 'Production Runcard')
    @section('content_page')
        <style type="text/css">
            .hidden_scanner_input{
                position: absolute;
                left: 15%;
                opacity: 0;
            }
            textarea{
                resize: none;
            }

            #colDevice, #colMaterialProcess{
                transition: .5s;
            }

            .checked-ok { background: #5cec4c!important; }

            /* .select2-container--bootstrap-5 .select2-selection--single {
                height: calc(1.9rem + 2px) !important;
            } */

            .select2-container--bootstrap-5 .select2-selection{
            /* .input-group .select2-container--bootstrap-5 .select2-selection{ */
            /* .select2-selection .select2-selection--single { */
                min-height: calc(1.9rem + 2px) !important;
                font-size: 0.875rem !important;
            }

            .input-group .select2-container--bootstrap-5 .select2-selection{
                height: calc(1.9rem + 2px) !important;
                font-size: 0.875rem !important;
            }

        </style>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Production Runcard</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Production Runcard</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label class="form-label">Device Name</label>
                                            <div class="input-group mb-3">
                                                <i class="fa-solid fa-circle-info fa-lg mt-3 mr-2" data-bs-toggle="tooltip" data-bs-html="true" title="Select Device Name"></i>
                                                {{-- <select class="form-control select2bs5" id="txtSelectPONo" name="device_name" placeholder="Select PO Number"></select> --}}
                                                <select class="form-control select2bs5" type="text" name="device_name" id="txtSelectDeviceName" required>
                                                    {{-- <option value="" disabled selected>Select Device Name</option> --}}
                                                    {{-- {{-- <option value="CN171P-007-1002-VE(01)">CN171P-007-1002-VE(01)</option> --}}
                                                    {{-- <option value="CN176-16#MO">CN176-16#MO</option> --}}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="form-label">Device Code</label>
                                            <div class="input-group input-group-sm mb-3">
                                                <input type="text" class="form-control" placeholder="Device Code" id="txtSearchDeviceCode" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="form-label">Material Name</label>
                                            <div class="input-group input-group-sm mb-3">
                                                <input type="text" class="form-control" placeholder="Material Name" id="txtSearchMaterialName" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <label class="form-label">Qty / Box</label>
                                            <div class="input-group input-group-sm mb-3">
                                                <input type="text" class="form-control" placeholder="Qty per Box" id="txtSearchReqOutput" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- left column -->
                        <div class="col-12">
                            <!-- general form elements -->
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title">PPD-F3 Runcards</h3>
                                </div>
                                <!-- Start Page Content -->
                                <div class="card-body">
                                    <div style="float: left;">
                                        <button style="float:left;" class="btn btn-dark ml-2" data-bs-toggle="modal" data-bs-target="#modalSearchProdRuncardData" id="btnSearchProdRuncardQr">
                                            <i class="fa-solid fa-qrcode"></i>&nbsp; Search Lot Number
                                        </button>
                                    </div>

                                    <div style="float: right;">
                                        <button class="btn btn-primary" id="btnAddProductionRuncard"><i class="fa-solid fa-plus"></i> Add Production Runcard</button>
                                    </div> <br><br>
                                    <div class="table-responsive">
                                        <!-- style="max-height: 600px; overflow-y: auto;" -->
                                        <table id="tblProductionRuncard" class="table table-sm table-bordered table-striped table-hover"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th hidden>ID</th>
                                                    <th>Action</th>
                                                    <th>Status</th>
                                                    <th>Part Name</th>
                                                    <th>PO Number</th>
                                                    <th>PO Qty</th>
                                                    <th>Production Lot #</th>
                                                    <th>Machine No</th>
                                                    <th>Created By</th>
                                                    <th>Date Created</th>
                                                    {{-- <th>Required Output</th> --}}
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                                <!-- !-- End Page Content -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- MODALS -->
        <div class="modal fade" id="modalProdRuncard" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-xl-custom">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Add PPD-F3 Runcard</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formProductionRuncard" autocomplete="off">
                        @csrf
                        <div class="modal-body">
                            {{-- <input type="text" id="textSecondMoldingId" class="d-none" name="id"> --}}
                            <div class="row">
                                <div class="col-sm-4 border px-4">
                                    <div class="py-3">
                                        <span class="badge badge-secondary">1.</span> Runcard Details
                                        <div style="margin-left: 40%;" class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="txtNewlyMaintenance" value="1">
                                            &NonBreakingSpace;
                                            <label class="form-check-label"> Newly Maintenance</label>
                                        </div>
                                        {{-- <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="3.11" name="lqc_dimension_insp_result" id="frm_txt_lqc_dimension_insp_result_ng" value="0"> --}}
                                    </div>
                                    <div class="input-group input-group-sm mb-3 d-none">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Production Runcard ID</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="txtProdRuncardId" name="prod_runcard_id" readonly>
                                    </div>
                                    <div class="input-group input-group-sm mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Part Name</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="txtPartName" name="part_name" placeholder="Auto generated" readonly>
                                    </div>
                                    <div class="input-group input-group-sm mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Part Code</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="txtPartCode" name="part_code" placeholder="Auto generated" readonly>
                                    </div>
                                    <div class="input-group input-group-sm mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">PO Number</span>
                                        </div>
                                        {{-- <input type="text" class="form-control form-control-sm" id="txtPONumber" name="po_number" placeholder="Search PO" required> --}}
                                        {{-- <select class="form-control select2bs5 SelPoNumber" type="text" id="txtPONumber" name="po_number" required></select> --}}
                                        <select class="form-control select2bs5" type="text" id="txtPONumber" name="po_number" required></select>
                                    </div>
                                    <div class="input-group input-group-sm mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">PO QTY</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="txtPOQty" name="po_quantity" placeholder="Auto generated" readonly>
                                    </div>
                                    <div class="input-group input-group-sm mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">PO Balance</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="txtPOBalance" name="po_balance" placeholder="Auto generated" readonly>
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Packing Qty</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="txtRequiredOutput" name="required_output" placeholder="Auto generated" readonly>
                                    </div>
                                    <div class="input-group input-group-sm mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Machine No</span>
                                        </div>
                                        <select class="form-control select2bs5 SelMachineNo" type="text" id="txtMachineNumber" name="machine_number" required></select>
                                    </div>
                                    <div class="input-group input-group-sm mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Production Time</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" style="width: 25%" id="txtProductionLotTime" name="production_lot_time" placeholder="07:30-04:30" required>
                                    </div>
                                    <div class="input-group input-group-sm mb-2">
                                        {{-- <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Production Lot</span>
                                        </div> --}}
                                        {{-- <div class="input-group input-group-sm mb-3"> --}}
                                            <span class="input-group-text" style="width: 50%">Production Lot</span>
                                            <input type="text" class="form-control form-control-sm" id="txtProductionLot" name="production_lot" placeholder="Auto generated">
                                            {{-- <input type="text" class="form-control form-control-sm" style="width: 25%" id="txtProductionLotTime" name="production_lot_time" placeholder="07:30-04:30"> --}}
                                        {{-- </div> --}}
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Shipment Output</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="txtShipmentOutput" name="shipment_output" placeholder="Auto Compute" readonly>
                                    </div>
                                    {{-- <div class="col-sm-12"> --}}
                                        <div class="d-sm-inline-block">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Material Drawing #:</span>
                                                </div>
                                                <input style="width:50%" type="text" class="form-control form-control-sm" id="txtDrawingNo" name="drawing_no" placeholder="Auto generated" readonly>
                                            </div>
                                        </div>
                                        <div class="d-sm-inline-block">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-25">
                                                    <span class="input-group-text w-100">Rev</span>
                                                </div>
                                                <input style="width:50%" type="text" class="form-control form-control-sm" id="txtDrawingRev" name="drawing_rev" placeholder="Auto generated" readonly>
                                            </div>
                                        </div>
                                    {{-- </div> --}}

                                    {{-- Resin Material Lot No --}}
                                    <div id="ResinMatLotNumber">
                                        <div style="border: 2px; border-style:dashed; padding:2px;">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Material Name</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="txtMatName" name="material_name" value="" readonly>
                                            </div>

                                            <div class="input-group input-group-sm mt-1 mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Lot No</span>
                                                </div>
                                                <div class="input-group-append">
                                                    <button class="btn btn-info" type="button" title="Scan code" id="btnScanMaterialLot" form-name-value="txtMatName" form-lotnumber-value="txtMaterialLot">
                                                        <i class="fa fa-qrcode"></i>
                                                    </button>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="txtMaterialLot" name="material_lot" placeholder="Scan Material Lot" readonly>
                                                {{-- <input type="hidden" class="form-control form-control-sm" id="txtMaterialLotMatId" name="material_lot_mat_id" placeholder="Material ID"> --}}
                                            </div>

                                            <div class="input-group input-group-sm mb-2" hidden>
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Material Qty</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="txtMaterialMatQty" name="material_qty" placeholder="Auto generated" >
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Contact Lot No --}}
                                    <div id="ContactLotNumber">
                                        <div style="border: 2px; border-style:dashed; padding:2px;" class="border-top-0">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">(CT) Contact Name</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="txtContactMatName" name="contact_mat_name" value="" readonly>
                                            </div>

                                            <div class="input-group input-group-sm mt-1 mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">(CT) Contact Lot No</span>
                                                </div>
                                                <div class="input-group-append">
                                                    <button class="btn btn-info" type="button" title="Scan code" id="btnScanContactLot" form-name-value="txtContactMatName" form-lotnumber-value="txtContactMatLot">
                                                        <i class="fa fa-qrcode"></i>
                                                    </button>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="txtContactMatLot" name="contact_mat_lot" placeholder="Scan Contact Material Lot" readonly>
                                                {{-- <input type="hidden" class="form-control form-control-sm" id="txtContactMatId" name="contact_mat_device_id" placeholder="Material ID"> --}}
                                            </div>

                                            <div class="input-group input-group-sm mb-2" hidden>
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">(CT) Contact Material Qty</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="txtContactMatQty" name="contact_mat_qty" placeholder="Auto generated" >
                                            </div>
                                        </div>
                                    </div>

                                    {{-- #ME Lot No --}}
                                    <div id="MELotNumber">
                                        <div style="border: 2px; border-style:dashed; padding:2px;" class="border-top-0">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">(ME) #ME Material Name</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="txtMEMaterialName" name="me_mat_name" value="" readonly>
                                            </div>

                                            <div class="input-group input-group-sm mt-1 mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">(ME) #ME Lot No</span>
                                                </div>
                                                <div class="input-group-append">
                                                    <button class="btn btn-info" type="button" title="Scan code" id="btnScanMELot" form-name-value="txtMEMaterialName" form-lotnumber-value="txtMEMaterialLot">
                                                        <i class="fa fa-qrcode"></i>
                                                    </button>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="txtMEMaterialLot" name="me_mat_lot" placeholder="Scan ME Material Lot" readonly>
                                                {{-- <input type="hidden" class="form-control form-control-sm" id="txtMEMatId" name="me_mat_id" placeholder="Material ID"> --}}
                                            </div>

                                            <div class="input-group input-group-sm mb-2" hidden>
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">(ME) #ME Material Qty</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="txtMEMatQty" name="me_mat_qty" placeholder="Auto generated" >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="input-group input-group-sm mt-2 mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">UD/PTNR #:</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="txtUdPtnrNo" name="ud_ptnr_no">
                                    </div>
                                    <div class="input-group input-group-sm mt-2 mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">SAR #</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="txtSarNo" name="sar_no">
                                    </div>
                                    <div class="input-group input-group-sm mt-2 mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">AER#</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="txtAerNo" name="aer_no">
                                    </div>

                                    <div class="input-group input-group-sm mb-3 justify-content-end align-items-center">
                                        <button class="btn btn-sm btn-success" type="button" id="btnSaveRuncardDetails">
                                            <i class="fa-solid fa-floppy-disk"></i> Save
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="col border px-4 border">
                                        <div class="py-3">
                                            <div style="float: left;">
                                                <span class="badge badge-secondary">2.</span> Process/Stations
                                            </div>
                                            <div style="float: right;">
                                                <button class="btn btn-primary btn-sm" id="btnAddRuncardStation" runcard_id="" type="button" style="margin-bottom: 5px;">
                                                    <i class="fa fa-plus"></i> Add Station
                                                </button>
                                                <button class="btn btn-primary btn-sm d-none" id="btnAddQualificationData" runcard_id="" type="button" style="margin-bottom: 5px;">
                                                    <i class="fa-solid fa-plus"></i> Add IPQC Inspection
                                                </button>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-sm small table-bordered table-hover" id="tblProdRuncardStation" style="width: 100%;">
                                                    <thead>
                                                        <tr class="bg-light">
                                                            <th></th>
                                                            <!-- <th></th> -->
                                                            <th>Station</th>
                                                            <th>Sub-Station</th>
                                                            <th>Date</th>
                                                            <th>Name</th>
                                                            <th>Input</th>
                                                            <th>NG Qty</th>
                                                            <th>Output</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="btnSubmitAssemblyRuncardData" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- MODALS -->
        <div class="modal fade" id="modalSearchProdRuncardData">
            <div class="modal-dialog modal-dialog-center">
                <div class="modal-content modal-sm">
                    <div class="modal-body">
                        <input type="text" class="scanner w-100 hidden_scanner_input" id="txtScanProdRuncardQrData" name="scan_prod_runcard_lot_number" autocomplete="off">
                        {{-- <input type="text" class="scanner w-100 " id="txtScanVerifyData" name="scan_packing_lot_number" autocomplete="off"> --}}
                        <div class="text-center text-secondary"><span id="modalScanProdRuncardQrIdText">Scan QR Code</span><br><br><h1><i class="fa fa-qrcode fa-lg"></i></h1></div>
                    </div>
                </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- Start Scan QR Modal -->
        <div class="modal fade" id="mdlScanQrCode" data-formid="" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0 pb-0">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body pt-0">
                        {{-- hidden_scanner_input --}}
                        <input type="text" class="scanner w-100 hidden_scanner_input" id="txtScanQrCode" name="scan_qr_code" autocomplete="off">
                        <div class="text-center text-secondary">Please scan the code.<br><br><h1><i class="fa fa-qrcode fa-lg"></i></h1></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.End Scan QR Modal -->

        <div class="modal fade" id="modalQrScanner" data-form-mat-name="" data-form-lotnumber="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0 pb-0">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body pt-0">
                        <input type="text" id="textQrScanner" class="hidden_scanner_input" class="" autocomplete="off">
                        <div class="text-center text-secondary">
                            Please scan Material Lot #
                            <br><br>
                            <h1><i class="fa fa-qrcode fa-lg"></i></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalAddStation" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-object-group text-info"></i> Stations</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formAddProductionRuncardStation">
                            @csrf
                            <div class="input-group input-group-sm mb-3" hidden>
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100">Production Runcard ID</span>
                                </div>
                                    <input type="text" class="form-control form-control-sm" id="txtFrmStationsRuncardId" name="frmstations_runcard_id" readonly>
                            </div>

                            <div class="input-group input-group-sm mb-3" hidden>
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100">Production Runcard Station ID</span>
                                </div>
                                    <input type="text" class="form-control form-control-sm" id="txtFrmStationsRuncardStationId" name="frmstations_runcard_station_id" readonly>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Station</span>
                                        </div>
                                        <select class="form-control" type="text" id="txtSelectRuncardStation" placeholder="Station" disabled>
                                        </select>
                                        <input type="text" class="form-control form-control-sm" id="txtRuncardStation" name="runcard_station" placeholder="Station" hidden>
                                        <input type="text" class="form-control form-control-sm" id="txtStep" name="step" placeholder="Station Step" hidden>
                                        {{-- <input type="text" class="form-control form-control-sm" step="" id="txtSelectRuncardStation" name="runcard_station" placeholder="Station"> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Sub-Station</span>
                                        </div>
                                        <select class="form-control" type="text" id="txtSelectRuncardSubStation" placeholder="Sub Station" disabled>
                                        </select>

                                        <input type="text" class="form-control form-control-sm" id="txtRuncardSubStation" name="runcard_sub_station" placeholder="Sub Station" hidden>
                                        <input type="text" class="form-control form-control-sm" id="txtSubStationStep" name="sub_station_step" placeholder="Sub Station Step" hidden>
                                        {{-- <input type="text" class="form-control form-control-sm" step="" id="txtSelectRuncardStation" name="runcard_station" placeholder="Station"> --}}
                                    </div>
                                </div>
                            </div>

                            {{-- Plastic Injection Additional START --}}
                            <div id="AnnealingAddDiv" class="d-none">
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-2">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100">Temperature / Time (260 °C / 60 sec) Machine #:</span>
                                            </div>
                                                <input type="text" class="form-control form-control-sm" id="txtAnnealingMachineNo" name="annealing_machine_no" value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-2">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100">Type of Annealing:</span>
                                            </div>

                                            <div class="ml-2 mt-2" style=" font-size: 80%;">
                                                <div class="">
                                                    <input class="" type="radio" value="1" name="type_annealing" id="txt100Annealing">
                                                    <label class="" for="txt100Annealing"> 100 % Annealing</label>
                                                </div>
                                                <div>
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend w-50 mt-1">
                                                            <span>
                                                                <input class="" type="radio" value="2" name="type_annealing" id="txtSamplingAnnealing">
                                                                <label class="" for="txtSamplingAnnealing"> Sampling (_pcs)</label>
                                                            </span>
                                                        </div>
                                                        <input type="text" class="form-control form-control-sm" id="txtSamplingPcs" name="sampling_annealing" value="" placeholder="pcs">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-2">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100">Sampling Annealing Result:</span>
                                            </div>

                                            <div class="ml-2 mt-2" style=" font-size: 80%;">
                                                <div class="">
                                                    <input class="" type="radio" value="1" name="sampling_result" id="txtOkSample">
                                                    <label class="" for="txtOkSample"> OK, Continue Production</label>
                                                </div>
                                                <div class="">
                                                    <input class="" type="radio" value="2" name="sampling_result" id="txtNgSample">
                                                    <label class="" for="txtNgSample"> NG, for YEC disposition</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Plastic Injection Additional END --}}

                            <div class="row">
                                <div class="col">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Date</span>
                                        </div>
                                            <input type="date" class="form-control form-control-sm" id="txtDate" name="date" value="<?php echo date('Y-m-d'); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Operator Name</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOperatorName" name="operator_name" placeholder="Auto Generate" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Input</span>
                                        </div>
                                            <input type="number" class="form-control form-control-sm" id="txtInputQuantity" name="input_qty" min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Output</span>
                                        </div>
                                            <input type="number" class="form-control form-control-sm" id="txtOutputQuantity" name="output_qty"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">NG Qty</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtNgQuantity" name="ng_qty" min="0" readonly>
                                    </div>
                                </div>
                            </div>

                            {{-- MODE OF DEFECTS START --}}
                            <div class="row">
                                <div class="col">
                                    <div class="table-responsive">
                                        <div class="d-flex justify-content-between">

                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <label>Total No. of NG: <span id="labelTotalNumberOfNG" style="color: red;">0</span>
                                                <label>
                                                    &nbsp;<li class="fa-solid fa-thumbs-down" id="labelIsTally" style="color: red;"></li>
                                                </label>
                                            </label>
                                            <button type="button" id="buttonAddRuncardModeOfDefect" class="btn btn-sm btn-info" title="Add MOD"><i class="fa fa-plus"></i> Add MOD</button>
                                        </div>
                                        <br>
                                        <table class="table table-sm" id="tableRuncardStationMOD">
                                            <thead>
                                                <tr>
                                                    <th style="width: 55%;">Mode of Defect</th>
                                                    <th style="width: 15%;">QTY</th>
                                                    <th style="width: 10%;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- MODE OF DEFECTS END --}}

                            <div class="row">
                                <div class="col">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Remarks</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtRemarks" name="remarks">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="btnSaveNewRuncardStation">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalQualiDetails" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Qualification Details</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formAddQualiDetails">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="QualificationAddDiv" class="">
                                        <div class="row">
                                            <div class="col">
                                                    <label class="form-label">Production Lot #:</label>
                                                    <div class="input-group mb-2">
                                                        <div class="input-group-append w-100">
                                                            <button class="btn btn-sm btn-info" type="button" title="Scan code" id="ScanProductLot">
                                                                <i class="fa fa-qrcode"></i>
                                                            </button>
                                                            <input type="text" class="form-control form-control-sm" name="production_lot" id="txtProductionLot" placeholder="Scan Production Lot" readonly>
                                                        </div>
                                                    </div>
                                            </div>

                                            <div class="col" hidden>
                                                <label class="form-label">Qualification ID:</label>
                                                <input type="text" class="form-control form-control-sm" id="txtQualiDetailsId" name="quali_details_id" placeholder="Auto Generate" readonly>
                                            </div>

                                            <div class="col" hidden>
                                                <label class="form-label">Production Runcard ID:</label>
                                                <input type="text" class="form-control form-control-sm" name="prod_runcard_id" id="txtProdRuncardId" placeholder="Auto Generate"readonly>
                                            </div>

                                            <div class="col" hidden>
                                                <label class="form-label">Process Status:</label>
                                                <input type="text" class="form-control form-control-sm" name="process_status" id="txtProcessStatus"  placeholder="Auto Generate"readonly>
                                            </div>

                                            <div class="col">
                                                <label class="form-label">PO Number:</label>
                                                <input type="text" class="form-control form-control-sm" name="po_number" id="txtPoNumber"  placeholder="Auto Generate"readonly>
                                            </div>

                                            <div class="col">
                                                    <label class="form-label">PO Qty:</label>
                                                    <input type="text" class="form-control form-control-sm" name="po_qty" id="txtPoQty"  placeholder="Auto Generate"readonly>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="form-label text-primary">Category:</label>
                                                    <select class="form-control form-control-sm" type="text" name="category" id="txtCategory" required>
                                                        <option value='' readonly selected>Select Category</option>
                                                        <option value="1">First Five</option>
                                                        <option value="2">Sampling Monitoring</option>
                                                    </select>
                                                </div>

                                                {{-- <div class="input-group input-group-sm mb-3">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100" id="basic-addon1">Category</span>
                                                    </div>
                                                    <select class="form-control" type="text" name="category" id="txtCategory">
                                                        <option value="" disabled selected>Select Category</option>
                                                        <option value="1">First Five</option>
                                                        <option value="2">Sampling Monitoring</option>
                                                    </select>
                                                </div> --}}
                                            </div>

                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="form-label">Part Name:</label>
                                                    <input type="text" class="form-control form-control-sm" name="part_name" id="txtPartName" placeholder="Auto Generate" readonly>
                                                </div>
                                            </div>

                                            <div class="col">
                                                <label class="form-label">Part Code:</label>
                                                <input type="text" class="form-control form-control-sm" name="part_code" id="txtPartCode" placeholder="Auto Generate" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="QualificationProdDiv" class="">
                                        <div style="border: 2px; border-style:solid; padding:8px;">
                                            <div class="row">
                                                <div class="col">
                                                    <b><span class="form-control-sm">PRODUCTION</span></b>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-100">
                                                            <span class="input-group-text w-50" id="basic-addon1">Name</span>
                                                            {{-- <input type="text" class="form-control form-control-sm" id="txtQualiProdName" name="quali_prod_name"> --}}
                                                            <input type="hidden" class="form-control form-control-sm" name="quali_prod_name_id" id="txtQualiProdNameID" readonly>
                                                            <input type="text" class="form-control form-control-sm" name="quali_prod_name" id="txtQualiProdName" placeholder="Auto Generate" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend w-100">
                                                            <span class="input-group-text w-100" id="basic-addon1">Date</span>
                                                            <input type="date" class="form-control form-control-sm" id="txtQualiProdDate" name="quali_prod_date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-100">
                                                            <span class="input-group-text w-50" id="basic-addon1">Input Qty</span>
                                                            <input type="number" class="form-control form-control-sm" id="txtQualiProdInputQty" name="quali_prod_input_qty">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100" id="basic-addon1">Judgement</span>
                                                        </div>
                                                        <select class="form-control" type="text" name="quali_prod_judgement" id="txtQualiProdJudgement">
                                                            <option value="" readonly selected>Select Judgement</option>
                                                            <option value="1">OK</option>
                                                            <option value="2">NG</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-100">
                                                            <span class="input-group-text w-50" id="basic-addon1">Output Qty</span>
                                                            <input type="number" class="form-control form-control-sm" id="txtQualiProdOutputQty" name="quali_prod_output_qty">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100" id="basic-addon1">Actual Sample</span>
                                                        </div>
                                                        <select class="form-control" type="text" name="quali_prod_actual_sample" id="txtQualiProdActualSample">
                                                            <option value="" readonly selected>Select Sample Used</option>
                                                            <option value="1">Japan Sample</option>
                                                            <option value="2">Evaluation Sample</option>
                                                            <option value="3">Correlation Sample</option>
                                                            <option value="4">Last Production</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-100">
                                                            <span class="input-group-text w-50" id="basic-addon1">Ng Qty</span>
                                                            <input type="number" class="form-control form-control-sm" id="txtQualiProdNgQty" name="quali_prod_ng_qty" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                                                        </div>
                                                            <input type="text" class="form-control form-control-sm" id="txtQualiProdRemarks" name="quali_prod_remarks">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="table-responsive">
                                                        <div class="d-flex justify-content-between">

                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <label>Total No. of NG: <span id="QualiProdlabelTotalNumberOfNG" style="color: red;">0</span>
                                                                <label>
                                                                    &nbsp;<li class="fa-solid fa-thumbs-down" id="labelIsTallyProd" style="color: red;"></li>
                                                                </label>
                                                            </label>
                                                            <button type="button" id="buttonAddQualiProdModeOfDefect" class="btn btn-sm btn-info" title="Add MOD"><i class="fa fa-plus"></i> Add MOD</button>
                                                        </div>
                                                        <br>
                                                        <table class="table table-sm" id="tableQualiProdMOD" style="min-height: 200px;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 55%;">Mode of Defect</th>
                                                                    <th style="width: 15%;">QTY</th>
                                                                    <th style="width: 10%;">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div id="QualificationQcDiv" class="">
                                        <div style="border: 2px; border-style:solid; padding:8px;" class="">
                                            <div class="row">
                                                <div class="col">
                                                    <b><span class="form-control-sm">QC</span></b>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-100">
                                                            <span class="input-group-text w-50" id="basic-addon1">Name</span>
                                                            {{-- <input type="text" class="form-control form-control-sm" id="txtQualiQCName" name="quali_qc_name"> --}}
                                                            {{-- <input type="hidden" class="form-control form-control-sm" name="quali_qc_name_id" id="txtQualiQCNameID" value="@php echo Auth::user()->id; @endphp" readonly> --}}
                                                            <input type="hidden" class="form-control form-control-sm" name="quali_qc_name_id" id="txtQualiQCNameID" readonly>
                                                            <input type="text" class="form-control form-control-sm" name="quali_qc_name" id="txtQualiQCName" placeholder="Auto Generate" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend w-100">
                                                            <span class="input-group-text w-100" id="basic-addon1">Date</span>
                                                            <input type="date" class="form-control form-control-sm" id="txtQualiQcDate" name="quali_qc_date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-100">
                                                            <span class="input-group-text w-50" id="basic-addon1">Input Qty</span>
                                                            <input type="number" class="form-control form-control-sm" id="txtQualiQcInputQty" name="quali_qc_input_qty">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100" id="basic-addon1">Judgement</span>
                                                        </div>
                                                        <select class="form-control" type="text" name="quali_qc_judgement" id="txtQualiQcJudgement">
                                                            <option value="" disabled selected>Select Judgement</option>
                                                            <option value="1">OK</option>
                                                            <option value="2">NG</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-100">
                                                            <span class="input-group-text w-50" id="basic-addon1">Output Qty</span>
                                                            <input type="number" class="form-control form-control-sm" id="txtQualiQcOutputQty" name="quali_qc_output_qty">
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100" id="basic-addon1">Actual Sample</span>
                                                        </div>
                                                        <select class="form-control" type="text" name="quali_qc_actual_sample" id="txtQualiQcActualSample">
                                                            <option value="" disabled selected>Select Sample Used</option>
                                                            <option value="1">Japan Sample</option>
                                                            <option value="2">Evaluation Sample</option>
                                                            <option value="3">Correlation Sample</option>
                                                            <option value="4">Last Production</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-100">
                                                            <span class="input-group-text w-50" id="basic-addon1">Ng Qty</span>
                                                            <input type="number" class="form-control form-control-sm" id="txtQualiQcNgQty" name="quali_qc_ng_qty" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                                                        </div>
                                                            <input type="text" class="form-control form-control-sm" id="txtQualiQcRemarks" name="quali_qc_remarks">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="table-responsive">
                                                        <div class="d-flex justify-content-between">

                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <label>Total No. of NG: <span id="QualiQclabelTotalNumberOfNG" style="color: red;">0</span>
                                                                <label>
                                                                    &nbsp;<li class="fa-solid fa-thumbs-down" id="labelIsTallyQc" style="color: red;"></li>
                                                                </label>
                                                            </label>
                                                            <button type="button" id="buttonAddQualiQcModeOfDefect" class="btn btn-sm btn-info" title="Add MOD"><i class="fa fa-plus"></i> Add MOD</button>
                                                        </div>
                                                        <br>
                                                        <table class="table table-sm" id="tableQualiQcMOD" style="min-height: 200px;">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 55%;">Mode of Defect</th>
                                                                    <th style="width: 15%;">QTY</th>
                                                                    <th style="width: 10%;">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div id="QualificationQcEngrDiv" class="">
                                        <div style="border: 2px; border-style:solid; padding:10px;" class="mb-3">
                                            <div class="row">
                                                <div class="col">
                                                    <b><span class="form-control-sm">CT HEIGHT DATA</span></b>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100" id="basic-addon1">QC</span>
                                                        </div>
                                                        <select class="form-control" type="text" name="ct_height_data_qc" id="txtCtHeightDataQc">
                                                            <option value="" disabled selected>Please Select</option>
                                                            <option value="1">OK</option>
                                                            <option value="2">NG</option>
                                                            <option value="3">N/A</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100" id="basic-addon1">ENG`G</span>
                                                        </div>
                                                        <select class="form-control" type="text" name="ct_height_data_engr" id="txtCtHeightDataEngr">
                                                            <option value="" disabled selected>Please Select</option>
                                                            <option value="1">OK</option>
                                                            <option value="2">NG</option>
                                                            <option value="3">N/A</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                                                        </div>
                                                            <input type="text" class="form-control form-control-sm" id="txtCtHeightDataRemarks" name="ct_height_data_remarks">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="QualificationQcEngr2Div" class="">
                                        <div class="row">
                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="form-label">Defect Checkpoint:</label>
                                                    <select class="form-control select2bs5" type="text" name="defect_checkpoint[]" id="txtDefectCheckpoint" multiple required>
                                                        <option value="0" disabled selected>Select Defects</option>
                                                        <option value="1">Shortshot</option>
                                                        <option value="2">Excess Plastic</option>
                                                        <option value="3">Toolmark</option>
                                                        <option value="4">Scratch</option>
                                                        <option value="5">Dent</option>
                                                        <option value="6">Stain</option>
                                                        <option value="7">Others</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Remarks:</label>
                                                    <textarea class="form-control" style="height: 150px;" id="txtDefectCheckpointRemarks" name="defect_checkpoint_remarks" required></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="btnSaveQualificationDetails">Save</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        {{-- MODAL FOR PRINTING  --}}
        <div class="modal fade" id="modalAssemblyPrintQr">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Production Runcard - QR Code</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- PO 1 -->
                            <div class="col-sm-12">
                                <center><img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->errorCorrection('H')->generate('0')) !!}" id="img_barcode_PO" style="max-width: 200px;"><br></center>
                                <label id="img_barcode_PO_text"></label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnAssemblyPrintQrCode" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs"></i> Print</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <!-- CONFIRM SUBMIT MODAL START -->
        <div class="modal fade" id="modalConfirmSubmit">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title"><i class="fa-solid fa-file-circle-check"></i>&nbsp;&nbsp;Confirmation</h4>
                        <button type="button" style="color: #fff" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="FrmConfirmSubmit">
                        @csrf
                        <div class="modal-body">
                            <div class="row d-flex justify-content-center">
                                <label class="text-secondary mt-2">Are you sure you want to proceed?</label>
                                <input type="hidden" class="form-control" name="cnfrm_assy_id" id="cnfrmtxtId">
                                {{-- <input type="hidden" class="form-control" name="cnfrm_ipqc_production_lot" id="cnfrmtxtIPQCProdLot">
                                <input type="hidden" class="form-control" name="cnfrm_ipqc_process_category" id="cnfrmtxtIPQCProcessCat"> --}}
                                <input type="hidden" class="form-control" name="cnfrm_ipqc_status" id="cnfrmtxtStatus">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="btnConfirmSubmit" class="btn btn-primary"><i id="ConfirmSubmitIcon"
                                    class="fa fa-check"></i> Proceed</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- CONFIRM SUBMIT MODAL END -->

        <!-- DELETE MODAL -->
        <div class="modal fade" id="modalNotification">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h4 class="modal-title"><i class="fa-solid fa-circle-info"></i>&nbsp;&nbsp;Printing Notification</h4>
                        <button type="button" style="color: #fff" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="row d-flex justify-content-center">
                                <label class="text-secondary mt-2">Printing is disabled, please input IPQC information first</label>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- DELETE MODAL END -->
    @endsection

    @section('js_content')
        <script type="text/javascript">
        GetDeviceName($('#txtSelectDeviceName'));

        $(document).ready(function(){

            $('#modalSearchProdRuncardData').on('shown.bs.modal', function () {
                $('#txtScanProdRuncardQrData').focus();
            });

            $('#txtScanProdRuncardQrData').on('keyup', function(e){
                if(e.keyCode == 13){
                    try{
                        const qrScannedProdRuncardItem = $('#txtScanProdRuncardQrData').val();
                        let ScannedProdRuncardQrCodeVal = JSON.parse(qrScannedProdRuncardItem)
                        console.log('scanned',ScannedProdRuncardQrCodeVal);
                        // scannedItem = JSON.parse($(this).val());
                        // scannedItem = $('#txtScanQrData').val().toUpperCase();
                        // scannedItem = $('#txtScanQrData').val();
                        // console.log('scannedItem', scannedItem);
                        $('#tblProductionRuncard tbody tr').each(function(index, tr){
                            let lot_no = $(tr).find('td:eq(5)').text().trim().toUpperCase();
                            let powerOff = $(this).find('td:nth-child(1)').children().children();

                            console.log('tbl_lot_no', lot_no);
                            console.log('scannedItem', ScannedProdRuncardQrCodeVal.production_lot);
                            if(ScannedProdRuncardQrCodeVal.production_lot === lot_no){
                                console.log('found');
                                $(tr).addClass('checked-ok');
                                powerOff.removeAttr('style');
                                $('#modalSearchProdRuncardData').modal('hide');
                            }
                            // console.log(lot_no);
                        })
                    }catch (e){
                        toastr.error('Invalid Sticker');
                        console.log(e);
                    }
                    $(this).val('');
                }
            });
        });
        </script>
    @endsection
@endauth
