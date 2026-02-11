@php $layout = 'layouts.admin_layout'; @endphp
@auth
    @extends($layout)
    @section('title', 'RAPID Pre-Shipment')
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

            #previewTable {
                width: 100%;
                border-collapse: collapse;
                font-size: 12px;
            }

            #previewTable th, #previewTable td {
                border: 1px solid black !important;
                text-align: left;
                vertical-align: top;
                padding: 0px;
            }

            #previewTable .qr-code {
                width: 40px;
                height: 40px;
                background: gray;
                display: inline-block;
            }

            #previewTable .label-row th {
                text-align: left;
                vertical-align: middle;
                width: 20%;
            }

            #previewTable .merged-label {
                padding: 1px !important;
                border-bottom: none !important;
            }

            #previewTable .merged-data {
                border-top: none !important;
                padding-left: 50px;
            }

        </style>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>RAPID Pre-Shipment</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">RAPID Pre-Shipment</li>
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
                                            <label class="form-label">Packing List Control No</label>
                                            <div class="input-group mb-3">
                                                <i class="fa-solid fa-circle-info fa-lg mt-3 mr-2" data-bs-toggle="tooltip" data-bs-html="true" title="Select Control No"></i>
                                                <select class="form-control select2bs5" type="text" name="control_number" id="txtSelectControlNumber" required>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="form-label">Shipment Date</label>
                                            <div class="input-group input-group-sm mb-3">
                                                <input type="text" class="form-control" placeholder="Shipment Date" id="txtSearchShipmentDate" readonly>
                                            </div>
                                        </div>
                                        {{-- <div class="col-sm-3">
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
                                        </div> --}}
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
                                    <h3 class="card-title">Pre-Shipment Summary</h3>
                                </div>
                                <!-- Start Page Content -->
                                <div class="card-body">
                                    {{-- <div style="float: left;">
                                        <button style="float:left;" class="btn btn-dark ml-2" data-bs-toggle="modal" data-bs-target="#modalSearchProdRuncardData" id="btnSearchProdRuncardQr">
                                            <i class="fa-solid fa-qrcode"></i>&nbsp; Search Lot Number
                                        </button>
                                    </div> --}}

                                    <div style="float: right;">
                                        <button class="btn btn-primary" id="btnAddPreShipment"><i class="fa-solid fa-plus"></i> Add Pre-Shipment Data</button>
                                    </div> <br><br>
                                    <div class="table-responsive">
                                        <table id="tblPreShipment" class="table table-sm table-bordered table-striped table-hover"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th hidden>ID</th>
                                                    <th>Action</th>
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                    <th>Station</th>
                                                    <th>Control No</th>
                                                    <th>Shipment Date</th>
                                                    <th>Destination</th>
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
        <div class="modal fade" id="modalPreShipment" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-xl-custom">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Add Pre-Shipment Data</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formPreshipment" autocomplete="off">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-4 border px-4">
                                    <div class="py-3">
                                        <span class="badge badge-secondary">1.</span> Pre-Shipment Details
                                    </div>
                                    <div class="input-group input-group-sm mb-3 d-none">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Pre-shipment ID</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="txtFrmDataPreShipmentId" name="pre_shipment_id" readonly>
                                    </div>
                                    <div class="input-group input-group-sm mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Date</span>
                                        </div>
                                        <input type="date" class="form-control form-control-sm" id="txtDate" name="date" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="input-group input-group-sm mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Packing List Control No</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="txtControlNo" name="control_no" readonly placeholder="Auto-Generated">
                                    </div>
                                    <div class="input-group input-group-sm mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Sales Cut-off</span>
                                        </div>
                                        <select class="form-control" name="sales_cutoff" id="SelectSalesCutOff">
                                            <option value="<?php echo date('m'); ?>" selected ><?php echo date('F'); ?></option><!-- selected -->
                                            <option value="1">January</option>
                                            <option value="2">February</option>
                                            <option value="3">March</option>
                                            <option value="4">April</option>
                                            <option value="5">May</option>
                                            <option value="6">June</option>
                                            <option value="7">July</option>
                                            <option value="8">August</option>
                                            <option value="9">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>

                                    <div class="input-group input-group-sm mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Category</span>
                                        </div>
                                        <select class="form-control" type="text" id="txtCategory" name="category">
                                            <option value="" disabled selected>Select Category</option>
                                            <option value="1">Stamping</option>
                                            <option value="2">Grinding</option>
                                            <option value="3">For PPS-CN Transfer</option>
                                            <option value="4">F3 PPD Molding</option>
                                        </select>
                                    </div>

                                    <div class="input-group input-group-sm mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Destination</span>
                                        </div>
                                        <select class="form-control select2bs5" type="text" id="txtSelectDestination" name="destination"></select>
                                        {{-- <select class="form-control select2bs5" type="text" id="txtDestination" name="destination"></select> --}}
                                    </div>

                                    <div class="input-group input-group-sm mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Station</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="txtStation" name="station" value="Packing">
                                    </div>

                                    <div class="input-group input-group-sm mb-2">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100">Shipment Date</span>
                                        </div>
                                        <input type="date" class="form-control form-control-sm" id="txtShipmentDate" name="shipment_date" value="{{ date('Y-m-d') }}">
                                    </div>

                                    <div class="input-group input-group-sm mb-3 justify-content-end align-items-center">
                                        <button class="btn btn-sm btn-success" type="button" id="btnSavePreShipment">
                                            <i class="fa-solid fa-floppy-disk"></i> Save
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="col border px-4 border">
                                        <div class="py-3">
                                            <div style="float: left;">
                                                <span class="badge badge-secondary">2.</span> Control Number Details
                                            </div>
                                            <div style="float: right;">
                                                <button class="btn btn-primary btn-sm" id="btnAddPreShipmentDetails" preshipment_id="" type="button" style="margin-bottom: 5px;">
                                                    <i class="fa fa-plus"></i> Add Details
                                                </button>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-sm small table-bordered table-hover" id="tblPreShipmentDetails" style="width: 100%;">
                                                    <thead>
                                                        <tr class="bg-light">
                                                            <th>Action</th>
                                                            <th>Status</th>
                                                            <th>Master Carton No</th>
                                                            <th>Item No</th>
                                                            <th>PO No</th>
                                                            <th>Parts Code</th>
                                                            <th>Device Name</th>
                                                            <th>Lot No</th>
                                                            <th>Qty</th>
                                                            <th>Package Category</th>
                                                            <th>Package Qty</th>
                                                            <th>Weighed By</th>
                                                            <th>Packed By</th>
                                                            <th>Checked By</th>
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
                            <button type="button" id="btnSubmitPreShipmentData" class="btn btn-primary"><i class="fa fa-check"></i> Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <div class="modal fade" id="modalAddPreShipmentDetails" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content modal-xl">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-object-group text-info"></i>Add Pre-Shipment Details</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formAddPreShipmentDetails">
                            @csrf
                            <div class="input-group input-group-sm mb-3" hidden>
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100">Pre-Shipment ID</span>
                                </div>
                                    <input type="text" class="form-control form-control-sm" id="txtFrmDetailsPreShipmentId" name="pre_shipment_id" readonly>
                            </div>

                            <div class="input-group input-group-sm mb-3" hidden>
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100">Pre-Shipment Details ID</span>
                                </div>
                                    <input type="text" class="form-control form-control-sm" id="txtPreShipmentDetailsId" name="pre_shipment_details_id" readonly>
                            </div>

                            <div class="row">
                                {{-- LEFT SIDE --}}
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">PO No</span>
                                                </div>
                                                    <select class="form-control select2bs5" type="text" id="txtPONumber" name="po_number" required></select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Parts Code</span>
                                                </div>
                                                    <input type="text" class="form-control form-control-sm" id="txtPartsCode" name="parts_code" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Device Name</span>
                                                </div>
                                                    <input type="text" class="form-control form-control-sm" id="txtDeviceName" name="device_name" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Package Category</span>
                                                </div>
                                                    <input type="text" class="form-control form-control-sm" id="txtPackageCategory" name="package_category" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- RIGHT SIDE --}}
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Weighed By</span>
                                                </div>
                                                <select class="form-control select2bs5" type="text" id="txtWeighedBy" name="weighed_by" required></select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Packed By</span>
                                                </div>
                                                <select class="form-control select2bs5" type="text" id="txtPackedBy" name="packed_by" required></select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Checked By</span>
                                                </div>
                                                <select class="form-control select2bs5" type="text" id="txtCheckedBy" name="checked_by" required></select>
                                            </div>
                                        </div>
                                    </div>

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
                                </div>
                            </div>

                            {{-- Result of PO Search START --}}
                            <div class="row">
                                <div class="col">
                                    <div class="table-responsive">
                                        <table class="table table-sm" id="tblPOSearchResult" style="width: 100%;">
                                            <thead>
                                                <tr class="bg-light">
                                                    <th style="width: 10%;"><center><input type="checkbox" style="width: 25px; height: 25px;" name="check_all" id="chkAllItems" disabled></center></th>
                                                    <th style="width: 15%;">Master Carton No</th>
                                                    <th style="width: 20%;">Item No</th>
                                                    <th style="width: 25%;">Lot No</th>
                                                    <th style="width: 10%;">Qty</th>
                                                    <th style="width: 10%;">Package Category</th>
                                                    <th style="width: 10%;">Package Qty</th>
                                                    <th style="width: 10%;">Remarks</th>
                                                    <th style="width: 10%;">Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {{-- Result of PO Search END --}}
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="btnSavePreShipmentDetails">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODALS -->

        {{-- MODAL FOR PRINTING  --}}
        <div class="modal fade" id="modalPreShipmentPrintQr">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Pre-Shipment - QR Code</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            <!-- Tabs Navigation -->
                            <ul class="nav nav-tabs" id="formPrintTabs">
                                <li class="nav-item">
                                    <button class="nav-link active" id="normal-tab" data-tab="normalDiv">Normal</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="dynamic-tab" data-tab="dynamicDiv">Dynamic</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" id="customized-tab" data-tab="customizedDiv">Customized</button>
                                </li>
                            </ul>

                        <form id="formPrintPreShipmentQrCode">
                            @csrf
                            <div class="row d-flex justify-content-center">
                                <input type="hidden" class="form-control" name="print_username" id="txtPrintUsername" value="{{ Auth::user()->username }}">
                                <input type="hidden" class="form-control" name="print_date" id="txtPrintDate" value="{{ date('Y-m-d') }}">
                                <input type="hidden" class="form-control" name="print_category" id="txtPrintCategory" value="1">
                            </div>

                            <!-- General Section (Always Visible) -->
                            <div class="generalDiv mt-3">
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100">PO #</span>
                                            </div>
                                            <select class="form-control select2bs5" type="text" id="txtPrintDeliveryKeyNo" name="print_delivery_key_no" required></select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100">LOT #</span>
                                            </div>
                                            <select class="form-control select2bs5" type="text" id="txtPrintLotNo" name="print_lot_no" required></select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100">Package Category</span>
                                            </div>
                                            <select class="form-control select2bs5" type="text" id="txtPrintPackageCategory" name="print_package_category">
                                                <option value="" disabled selected>Select Category</option>
                                                <option value="1">Bundle</option>
                                                <option value="2">Box</option>
                                                <option value="3">Tray</option>
                                                <option value="4">Polybag</option>
                                                <option value="5">Reel</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100">Packed By</span>
                                            </div>
                                            {{-- Attribute Name Removed --}}
                                            {{-- <select class="form-control select2bs5" type="text" id="txtPrintPackedBy" name="print_packed_by" required></select> --}}
                                            <select class="form-control select2bs5" type="text" id="txtPrintPackedBy" required></select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab-Specific Sections -->
                            <div class="Tabs">
                                <div id="normalDiv" class="tab-content-div">
                                {{-- <div class="NormalDiv"> --}}
                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Qty Per Box</span>
                                                </div>
                                                <input type="number" class="form-control" id="txtPrintNormalQty" name="print_normal_qty" required></input>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Total Qty</span>
                                                </div>
                                                <input type="number" class="form-control" id="txtPrintNormalTotalQty" name="print_normal_total_qty" required></input>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="generalDiv">
                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Delivery Place Name</span>
                                                </div>
                                                <select class="form-control select2bs5" type="text" id="txtPrintDeliveryPlace" name="print_delivery_place">
                                                    <option value="1" selected>W102</option>
                                                    <option value="2">W001</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Shipment Date</span>
                                                </div>
                                                <input type="date" class="form-control"  id="txtPrintShipmentDate" name="print_shipment_date" required></input>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Delivery Date</span>
                                                </div>
                                                <input type="date" class="form-control" id="txtPrintDeliveryDate" name="print_delivery_date" value="{{ date('Y-m-d') }}" required></input>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="DynamicDiv" style="display: none;"> --}}
                                <div id="dynamicDiv" class="tab-content-div" style="display: none;">
                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Stickers Count</span>
                                                </div>
                                                <input type="number" class="form-control" id="txtPrintDynamicStickerCount" name="print_dynamic_sticker_count" required></input>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="CustomizedDiv" style="display: none;"> --}}
                                <div id="customizedDiv" class="tab-content-div" style="display: none;">
                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Customized Qty</span>
                                                </div>
                                                <input type="number" class="form-control" id="txtPrintCustomQty" name="print_custom_qty" required></input>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Customized Total Qty</span>
                                                </div>
                                                <input type="number" class="form-control" id="txtPrintCustomTotalQty" name="print_custom_total_qty" required></input>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group input-group-sm mb-3">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100">Package Count(Ex: 3/9)</span>
                                                </div>
                                                <input type="text" class="form-control" id="txtPrintCustomPackageCount" name="print_custom_package_count" required></input>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="generalDiv">
                                <div class="row">
                                    <div class="col">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100">Stamping</span>
                                            </div>
                                            <input type="checkbox" class="form-check-input" style="width: 30px; height: 30px; margin-top: 0; margin-left: 230px;" id="txtPrintStamping" name="print_stamping" value="1"></input>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnPrintThroughRapid" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs"></i> Print Sticker</button>
                        {{-- <button type="button" id="btnPrintQrCode" class="btn btn-primary btn-sm d-none"><i class="fa fa-print fa-xs"></i> Print</button> --}}
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        {{-- MODAL PREVIEW STICKER FOR PRINTING  --}}
        <div class="modal fade" id="modalPreviewSticker">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Preview Sticker - QR Code</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {{-- <table class='table table-sm table-borderless' style='width: 100%;'>
                                <tr>
                                    <td style='width: 50%;'>
                                        <center><img src="" id="img_barcode_PO" style="max-width: 200px;"><br></center>
                                        <label id="img_barcode_PO_text"></label>
                                    </td>
                                    <td style='width: 50%;'>
                                        <center><img src="" id="img_barcode_PO2" style="max-width: 200px;"><br></center>
                                        <label id="img_barcode_PO_text2"></label>
                                    </td>
                                </tr>
                            </table> --}}

                            <table id="previewTable" class="table table-sm table-borderless" style="width: 100%;">
                                <tr>
                                    <th colspan="5" class="merged-label">Buyer</th>
                                </tr>
                                <tr>
                                    <td colspan="5" class="merged-data"><strong>PRICON MICROELECTRONICS INC. - (Internal)</strong></td>
                                </tr>
                                <tr>
                                    <th colspan="5" class="merged-label">Vendor</th>
                                </tr>
                                <tr>
                                    <td colspan="5" class="merged-data"><strong>PRICON MICROELECTRONICS INC.</strong></td>
                                </tr>
                                <tr>
                                    <th colspan="2" class="merged-label">Delivery Key No.</th>
                                    <th class="merged-label">Comments for buyer</th>
                                    <th colspan="2" class="merged-label">Comments for Vendor</th>
                                </tr>
                                <tr>
                                    <td colspan="2" class="merged-data"><strong>PR2510149162</strong></td>
                                    <td class="merged-data"></td>
                                    <td colspan="2" class="merged-data"></td>
                                </tr>
                                <tr>
                                    <th class="merged-label" colspan="2">Delivery Date</th>
                                    <th class="merged-label">Pre-shipment</th>
                                    <th class="merged-label">Drawing #</th>
                                    <th class="merged-label">Drawing Rev #</th>
                                </tr>
                                <tr>
                                    <td class="merged-data" colspan="2"><strong>2025-03-04</strong></td>
                                    <td class="merged-data" rowspan="3"><div class="qr-code"></div></td>
                                    <td class="merged-data" rowspan="3"><strong>B139103-001</strong></td>
                                    <td class="merged-data" rowspan="3"><strong>E</strong></td>
                                </tr>
                                <tr>
                                    <th class="merged-label">Qty/Total Qty</th>
                                    <th class="merged-label">Packing Type</th>
                                </tr>
                                <tr>
                                    <td class="merged-data">2000/2000</td>
                                    <td class="merged-data">Tray</td>
                                </tr>
                                <tr>
                                    <th class="merged-label" colspan="2">Product Name</th>
                                    <th class="merged-label">Partcode</th>
                                    <th colspan="2" rowspan="4" title="blank_row4_col2"></th>
                                </tr>
                                <tr>
                                    <td class="merged-data" colspan="2">HF106P-03#IN-VE NO.2</td>
                                    <td class="merged-data">108864001 &nbsp; <div class="qr-code"></div></td>
                                </tr>
                                <tr>
                                    <th class="merged-label">Package Count</th>
                                    <th class="merged-label">Packed by</th>
                                    <th class="merged-label">Lot No.</th>
                                </tr>
                                <tr>
                                    <td class="merged-data">1/1</td>
                                    <td class="merged-data">Mhalou</td>
                                    <td class="merged-data">2E250224-6 <div class="qr-code"></div></td>
                                </tr>
                            </table>
                        </div>
                        {{-- <div class="row">
                            <!-- PO 1 -->
                            <div class="col-sm-12">
                                <center><img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->errorCorrection('H')->generate('0')) !!}" id="img_barcode_PO" style="max-width: 200px;"><br></center>
                                <label id="img_barcode_PO_text"></label>
                            </div>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnAssemblyPrintQrCode" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs" disabled></i> Print</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

    @endsection

    @section('js_content')
        <script type="text/javascript">
        GetControlNo($('#txtSelectControlNumber'));
        GetDestinations($('#txtSelectDestination'));

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
                        $('#tblPreShipment tbody tr').each(function(index, tr){
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
