@php $layout = 'layouts.admin_layout'; @endphp
{{-- @auth
  @php
    if(Auth::user()->user_level_id == 1){
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->user_level_id == 2){
      $layout = 'layouts.admin_layout';
    }
    else if(Auth::user()->user_level_id == 3){
      $layout = 'layouts.user_layout';
    }
  @endphp
@endauth --}}

@auth
    @extends($layout)

    @section('title', 'First Molding')

    @section('content_page')

        <style type="text/css">
            .hidden_scanner_input{
                position: absolute;
                opacity: 0;
            }

            .checked-ok { background: #5cec4c!important; }
            /* textarea{
                resize: none;
            } */

            #colDevice, #colMaterialProcess{
                transition: .5s;
            }
        </style>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>IPQC Qualification</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">IPQC Qualification</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    {{-- <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <label class="form-label">PO Number</label>
                                            <div class="input-group mb-3">
                                                <button class="btn btn-primary" id="btnScanPo" data-bs-toggle="modal" data-bs-target="#mdlScanQrCode"><i class="fa-solid fa-qrcode"></i></button>
                                                <input type="text" class="form-control" placeholder="Search PO Number" name="po_number" id="txtSearchPONum">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="form-label">Part Code</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Product Code" aria-label="Partcode" id="txtSearchPartCode" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="form-label">Material Name</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Material Name" aria-label="Materialname" id="txtSearchMatName" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-2">
                                                <label class="form-label">Filter Device Name:</label>
                                            <div class="input-group mb-3">
                                                <i class="fa-solid fa-circle-info fa-lg mt-3 mr-2" data-bs-toggle="tooltip" data-bs-html="true" title="Select Device Name"></i>
                                                <select class="form-control select2bs5 txtSelectPartName" id="txtSelectPartName" name="sel_device_name" placeholder="Select Device Name"></select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="form-label">Part Code</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Product Code" aria-label="Partcode" id="txtSearchPartCode" readonly>
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
                            {{-- <div class="card card-primary"> --}}
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">IPQC Qualification Details</h3>
                                </div>

                                {{-- <div class="mt-2 mr-2">
                                    <button  class="btn btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#modalVerifyData" id="btnSearchScanQr"><i
                                        class="fa-solid fa-qrcode"></i>&nbsp; Validation of Lot #
                                    </button><br><br>
                                </div> --}}

                                <div class="mt-2 mr-2">
                                    <button style="float:left;" class="btn btn-dark ml-3" data-bs-toggle="modal" data-bs-target="#modalSearchData" id="btnSearchScanQr">
                                        <i class="fa-solid fa-qrcode"></i>&nbsp; Search Lot Number
                                    </button>

                                    {{-- <button style="float:right;" class="btn btn-primary" id="btnAddQualificationData"><i class="fa-solid fa-plus"></i> Add Qualification Inspection</button> --}}
                                </div>
                                <!-- Start Page Content -->
                                <div class="card-body">
                                    {{-- TABS --}}
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="Pending-tab" data-bs-toggle="tab" href="#Pending" role="tab" aria-controls="Pending" aria-selected="true">Pending</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="Completed-tab" data-bs-toggle="tab" href="#Completed" role="tab" aria-controls="Completed" aria-selected="false">Completed</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="Resetup-tab" data-bs-toggle="tab" href="#Resetup" role="tab" aria-controls="Resetup" aria-selected="false">For Re-Setup</a>
                                        </li>
                                    </ul>
                                    <br>
                                    <div class="tab-content" id="myTabContent">
                                        {{-- Pending Tab --}}
                                        <div class="tab-pane fade show active" id="Pending" role="tabpanel" aria-labelledby="Pending-tab">
                                            <div class="table-responsive">
                                                <table id="tblRuncardQualiPending" class="table table-sm table-bordered table-striped table-hover text-center"
                                                    style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th hidden>ID</th>
                                                            <th>Action</th>
                                                            <th>Status</th>
                                                            <th>Process Status</th>
                                                            <th>Created At</th>
                                                            <th>Device Name</th>
                                                            <th>PO Number</th>
                                                            <th>Production Lot#</th>
                                                            <th>Judgement</th>
                                                            <th>QC Sample</th>
                                                            <th>Inspected At</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        {{-- Completed Tab --}}
                                        <div class="tab-pane fade" id="Completed" role="tabpanel" aria-labelledby="Completed-tab">
                                                <div class="table-responsive">
                                                    <table id="tblRuncardQualiCompleted" class="table table-sm table-bordered table-striped table-hover text-center"
                                                        style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th hidden>ID</th>
                                                                <th>Action</th>
                                                                <th>Status</th>
                                                                <th>Process Status</th>
                                                                <th>Created At</th>
                                                                <th>Device Name</th>
                                                                <th>PO Number</th>
                                                                <th>Production Lot#</th>
                                                                <th>Judgement</th>
                                                                <th>QC Sample</th>
                                                                <th>Inspected At</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                        </div>
                                        {{-- For Re-Setup Tab --}}
                                        <div class="tab-pane fade" id="Resetup" role="tabpanel" aria-labelledby="Resetup-tab">
                                                <div class="table-responsive">
                                                    <table id="tblRuncardQualiResetup" class="table table-sm table-bordered table-striped table-hover text-center"
                                                        style="width: 100%;">
                                                        <thead>
                                                            <tr>
                                                                <th hidden>ID</th>
                                                                <th>Action</th>
                                                                <th>Status</th>
                                                                <th>Process Status</th>
                                                                <th>Created At</th>
                                                                <th>Device Name</th>
                                                                <th>PO Number</th>
                                                                <th>Production Lot#</th>
                                                                <th>Judgement</th>
                                                                <th>QC Sample</th>
                                                                <th>Inspected At</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                        </div>
                                    </div>
                                    {{-- TABS END --}}
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
        <div class="modal fade" id="modalSearchData">
            <div class="modal-dialog modal-dialog-center">
                <div class="modal-content modal-sm">
                    <div class="modal-body">
                        <input type="text" class="scanner w-100 hidden_scanner_input" id="txtScanQrData" name="scan_lot_number" autocomplete="off">
                        {{-- <input type="text" class="scanner w-100 " id="txtScanVerifyData" name="scan_packing_lot_number" autocomplete="off"> --}}
                        <div class="text-center text-secondary"><span id="modalScanQrLotNumberIdText">Scan QR Code</span><br><br><h1><i class="fa fa-qrcode fa-lg"></i></h1></div>
                    </div>
                </div>
            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- CONFIRM SUBMIT MODAL START -->
        <div class="modal fade" id="modalConfirmSubmitIPQCInspection">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title"><i class="fa-solid fa-file-circle-check"></i>&nbsp;&nbsp;Confirmation</h4>
                        <button type="button" style="color: #fff" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="FrmConfirmSubmitIPQCInspection">
                        @csrf
                        <div class="modal-body">
                            <div class="row d-flex justify-content-center">
                                <label class="text-secondary mt-2">Are you sure you want to proceed?</label>
                                {{-- <input type="hidden" class="form-control" name="cnfrm_first_molding_id" id="cnfrmtxtFirstMoldingId"> --}}
                                <input type="hidden" class="form-control" name="cnfrm_quali_id" id="cnfrmtxtQualiID">
                                <input type="hidden" class="form-control" name="cnfrm_quali_production_lot" id="cnfrmtxtQualiProdLot">
                                <input type="hidden" class="form-control" name="cnfrm_quali_process_status" id="cnfrmtxtQualiProcessStatus">
                                <input type="hidden" class="form-control" name="cnfrm_quali_status" id="cnfrmtxtQualiStatus">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="btnConfirmSubmitIPQCInspection" class="btn btn-primary"><i id="ConfirmSubmitIPQCInspectionIcon"
                                    class="fa fa-check"></i> Proceed</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- CONFIRM SUBMIT MODAL END -->

        {{-- MODAL FOR PRINTING  --}}
        <div class="modal fade" id="modalQualiDetails" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-xl-custom" role="document">
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
                                                            <button class="btn btn-sm btn-info" type="button" title="Scan code" id="ScanProductLot" hidden>
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

                                            <div class="col">
                                                <div class="form-group">
                                                    <label class="form-label text-primary">Category:</label>
                                                    <select class="form-control form-control-sm bg-primary" type="text" name="category" id="txtCategory">
                                                        <option disabled selected>Select Category</option>
                                                        <option value="1">First Five</option>
                                                        <option value="2">Sampling Monitoring</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
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
                                                            <input type="hidden" class="form-control form-control-sm" name="quali_prod_name_id" id="txtQualiProdNameID" readonly>
                                                            <input type="text" class="form-control form-control-sm" name="quali_prod_name" id="txtQualiProdName" placeholder="Auto Generate" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend w-100">
                                                            <span class="input-group-text w-100" id="basic-addon1">Date</span>
                                                            <input type="date" class="form-control form-control-sm" id="txtQualiProdDate" name="quali_prod_date" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-100">
                                                            <span class="input-group-text w-50" id="basic-addon1">Input Qty</span>
                                                            <input type="number" class="form-control form-control-sm" id="txtQualiProdInputQty" name="quali_prod_input_qty" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100" id="basic-addon1">Judgement</span>
                                                        </div>
                                                        <select class="form-control" type="text" name="quali_prod_judgement" id="txtQualiProdJudgement" required>
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
                                                            <input type="number" class="form-control form-control-sm" id="txtQualiProdOutputQty" name="quali_prod_output_qty" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="input-group input-group-sm mb-3">
                                                        <div class="input-group-prepend w-50">
                                                            <span class="input-group-text w-100" id="basic-addon1">Actual Sample</span>
                                                        </div>
                                                        <select class="form-control" type="text" name="quali_prod_actual_sample" id="txtQualiProdActualSample" required>
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
                </div>
            </div>
        </div>

        <!-- MODALS -->
        {{-- * ADD --}}
        <div class="modal fade" id="modalIpqcInspection" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Add IPQC Inspection Data</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="formIPQCInspectionData" autocomplete="off">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" id="txtQualiDetailsId" name="quali_details_id" value="0">
                            {{-- <input type="hidden" id="txtFirstMoldingId" name="first_molding_id"> --}}
                            <input type="hidden" id="txtProcessCategory" name="process_category" value="1">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
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

                                            <div class="form-group">
                                                <label class="form-label">PO Number:</label>
                                                <input type="text" class="form-control form-control-sm" name="po_number" id="txtPoNumber"  placeholder="Auto Generate"readonly>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Part Code:</label>
                                                <input type="text" class="form-control form-control-sm" name="part_code" id="txtPartCode" placeholder="Auto Generate"readonly>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Material Name:</label>
                                                <input type="text" class="form-control form-control-sm" name="material_name" id="txtMaterialName" placeholder="Auto Generate" readonly>
                                            </div>
                                            {{-- <div class="form-group">
                                                <label class="form-label">Production Lot #:</label>
                                                <input type="text" class="form-control form-control-sm" name="production_lot" id="txtProductionLot" readonly>
                                            </div> --}}
                                            <div class="form-group">
                                                <label class="form-label">QC Sample:</label>
                                                {{-- <i class="fa-solid fa-circle-info fa-lg mt-2 mr-2" data-bs-toggle="tooltip" data-bs-html="true" title="QC Sample Qty"></i> --}}
                                                <input type="text" class="form-control form-control-sm" name="qc_samples" id="txtQcSamples">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">OK:</label>
                                                <input type="text" class="form-control form-control-sm" name="ok_samples" id="txtOkSamples"
                                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">NG:</label>
                                                <i class="fa-solid fa-circle-info fa-lg mt-3 mr-2" data-bs-toggle="tooltip" data-bs-html="true" title="Input - Output = NG Qty"></i>
                                                <input type="text" class="form-control form-control-sm" name="ng_qty" id="txtNGQty" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-check-label"> Keep Sample:</label>
                                                <div class="form-check form-check-inline ml-1">
                                                    <input class="form-check-input" type="radio" value="1" name="keep_sample" id="txtKeepSample1">
                                                    <label class="form-check-label" for="txtKeepSample1"> Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" value="2" name="keep_sample" id="txtKeepSample2">
                                                    <label class="form-check-label" for="txtKeepSample2"> No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="form-label">Judgement:</label>
                                                {{-- <input type="text" class="form-control form-control-sm" name="judgement" id="txtJudgement"> --}}
                                                <select class="form-control form-control-sm" type="text" name="judgement" id="txtJudgement" required>
                                                    <option value="" disabled selected>Select Judgement</option>
                                                    <option value="Accepted" style="color:#008000">Accepted</option>
                                                    <option value="Rejected" style="color:#ff0000">Rejected</option>
                                                </select>
                                            </div>
                                            <div class="form-group mt-1">
                                                <label class="form-label">Inspector Name:</label>
                                                <input type="hidden" class="form-control form-control-sm" name="inspector_id" id="txtInspectorID" readonly>
                                                {{-- `${let name = response['users'][index].rapidx_user_details.firstname + response['users'][index].rapidx_user_details.lastname}` --}}
                                                <input type="text" class="form-control form-control-sm" name="inspector_name" id="txtInspectorName" placeholder="Auto Generate" readonly>
                                            </div>
                                            {{-- DROPDOWN --}}
                                            <div class="form-group">
                                                    <label class="form-label">Doc No.(B Drawing):</label>
                                                <div class="input-group input-group-sm" style="width: 100%;">
                                                    <div id="BDrawingDiv" class="input-group-prepend">
                                                    </div>
                                                    <select class="form-control form-control-sm" id="txtSelectDocNoBDrawing" name="doc_no_b_drawing">
                                                        <option>Please Select Material</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                    <label class="form-label">Doc No.(Inspection Standard):</label>
                                                <div class="input-group input-group-sm" style="width: 100%;">
                                                    <div id="InspStandardDiv" class="input-group-prepend">
                                                    </div>
                                                    <select class="form-control form-control-sm" id="txtSelectDocNoInspStandard" name="doc_no_inspection_standard">
                                                        <option>Please Select Material</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                    <label class="form-label">Doc No.(UD):</label>
                                                <div class="input-group input-group-sm" style="width: 100%;">
                                                    <div id="UDDiv" class="input-group-prepend">
                                                    </div>
                                                    <select class="form-control form-control-sm" id="txtSelectDocNoUD" name="doc_no_ud">
                                                        <option>Please Select Material</option>
                                                    </select>
                                                </div>
                                            </div>
                                            {{-- <div class="form-group">
                                                <label class="form-label">MeasData Attachment:</label>
                                                <input type="text" class="form-control form-control-sm" name="drawing_no" id="txtDrawingNo" readonly>
                                            </div> --}}
                                            {{-- ATTACHMENT --}}
                                            <div class="form-group">
                                                <div id="AttachmentDiv">
                                                    <label class="form-control-label">MeasData Attachment:</label>
                                                </div>
                                                    <input type="file" class="form-control form-control-sm" id="txtAddFile" name="uploaded_file" accept=".xlsx, .xls, .csv" style="width:100%;" required>
                                                    <input type="text" class="form-control form-control-sm d-none" name="re_uploaded_file" id="txtEditUploadedFile" readonly>
                                                <div class="form-group form-check d-none m-0" id="btnReuploadTriggerDiv">
                                                    <input type="checkbox" class="form-check-input d-none" id="btnReuploadTrigger">
                                                    <label class="d-none" id="btnReuploadTriggerLabel"> Re-upload Attachment</label>
                                                </div>
                                            </div>
                                            {{-- ATTACHMENT --}}
                                            <div class="form-group">
                                                <label class="form-label">Remarks:</label>
                                                <textarea class="form-control form-control-sm" name="remarks" id="txtRemarks"></textarea>
                                            </div>
                                            <div class="form-group text-center">
                                                {{-- <label class="form-label">ILQCM Link:</label> --}}
                                                {{-- <a href="{{ route('ilqcm') }}" target="_blank"> --}}
                                                <a href="http://rapidx/ilqcm/dashboard" target="_blank">
                                                {{-- <a href="http://rapidx/cash_advance/" target="_blank"> --}}
                                                    <button type="button" class="btn btn-primary" id="btnilqcmlink">
                                                        <i class="fa-solid fa-pen"></i> Update In-Line QC Monitoring
                                                    </button>
                                                </a>
                                                <i class="fa-solid fa-circle-info fa-lg" data-bs-toggle="tooltip" data-bs-html="true" title="Update In-Line QC Monitoring Thru our ILQCM System in RapidX"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="frmSaveBtn"><i
                                    class="fa fa-check"></i> Save</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- Start Scan QR Modal -->
        <div class="modal fade" id="modalQrScanner" data-form-id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0 pb-0">
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body pt-0">
                        <input type="text" id="QualitextQrScanner" class="hidden_scanner_input" class="" autocomplete="off">
                        <div class="text-center text-secondary">
                            Please scan Material Lot #
                            <br><br>
                            <h1><i class="fa fa-qrcode fa-lg"></i></h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.End Scan QR Modal -->

    @endsection

    @section('js_content')
        <script type="text/javascript">
        $(document).ready(function(){
            $( '.select2bs5' ).select2({
                theme: 'bootstrap-5'
            });
            setTimeout(() => {
                console.log('sad');
                GetQualiPartName($('.txtSelectPartName'));
            }, 500);

            $('#modalSearchData').on('shown.bs.modal', function () {
                $('#txtScanQrData').focus();
            });

            $('#txtScanQrData').on('keyup', function(e){
                if(e.keyCode == 13){
                    try{
                        const qrScannedItem = $('#txtScanQrData').val();
                        let ScannedQrCodeVal = JSON.parse(qrScannedItem)
                        let scannedItem = ScannedQrCodeVal.production_lot.toUpperCase();
                        console.log('scanned lot', scannedItem);
                        // scannedItem = JSON.parse($(this).val());
                        // scannedItem = $('#txtScanQrData').val().toUpperCase();
                        // scannedItem = $('#txtScanQrData').val();
                        // console.log('scannedItem', scannedItem);
                        $('#tblRuncardQualiPending tbody tr').each(function(index, tr){
                            let lot_no = $(tr).find('td:eq(6)').text().trim().toUpperCase();
                            let powerOff = $(this).find('td:nth-child(1)').children().children();
                            if(scannedItem === lot_no){
                                console.log('SCAN FOUND', scannedItem === lot_no);
                                $(tr).addClass('checked-ok');
                                powerOff.removeAttr('style');
                                $('#modalSearchData').modal('hide');
                            }
                        })

                        $('#tblRuncardQualiCompleted tbody tr').each(function(index, tr){
                            let lot_no = $(tr).find('td:eq(6)').text().trim().toUpperCase();
                            let powerOff = $(this).find('td:nth-child(1)').children().children();
                            if(scannedItem === lot_no){
                                console.log('SCAN FOUND', scannedItem === lot_no);
                                $(tr).addClass('checked-ok');
                                powerOff.removeAttr('style');
                                $('#modalSearchData').modal('hide');
                            }
                        })
                    }
                    catch (e){
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
