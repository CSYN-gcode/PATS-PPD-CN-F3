@php $layout = 'layouts.admin_layout'; @endphp
@auth
    @extends($layout)
    @section('title', 'DHD - Monitoring Checksheet')
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

            .text-hidden {
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
                            <h1>DHD - Monitoring Checksheet</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">DHD - Monitoring Checksheet</li>
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
                                <div class="card-header">
                                    <h3 class="card-title">DHD - Monitoring Checksheet Table</h3>
                                </div>
                                <div class="card-body"><!-- Start Page Content -->
                                    <div style="float: right;">
                                        <button class="btn btn-dark" data-bs-toggle="modal"
                                            data-bs-target="#modalAddDHD" id="btnShowAddDevic"><i
                                                class="fa fa-initial-icon"></i> Add New Data</button>
                                    </div> <br><br>
                                    <div class="table-responsive">
                                        <table id="tblDHDMonitoring" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">Action</th>
                                                    <th rowspan="2">Created At</th>
                                                    <th rowspan="2">DHD Number</th>
                                                    <th rowspan="2">Device Code</th>
                                                    <th rowspan="2">Device Name</th>
                                                    <th rowspan="2">Material Name</th>
                                                    <th colspan="2">Materials Mixing</th>
                                                    <th rowspan="2">Total Mixed Mat'ls (Kgs.)</th>
                                                    <th colspan="2">Material Lot No.</th>
                                                    <th colspan="4">Material Drying</th>
                                                    <th colspan="4">DHD Monitoring</th>
                                                </tr>
                                                <tr>
                                                    <th>Virgin (Kgs.)</th>
                                                    <th>Recycle (Kgs.)</th>
                                                    <th>Virgin</th>
                                                    <th>Recycle</th>
                                                    <th>Temperature Setting</th>
                                                    <th>Temperature Actual</th>
                                                    <th>Time IN</th>
                                                    <th>Time OUT</th>
                                                    <th>A Shift 1200hrs - 1300hrs</th>
                                                    <th>B Shift 1200hrs - 1300hrs</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>

                                    <div class="modal fade" id="modalAddDHD">
                                        <div class="modal-dialog  modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title"><i class="fa fa-plus"></i> DHD Details</h4>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form id="formDHDMonitoring" autocomplete="off">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <input type="hidden" id="txtDHDId" name="id">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-sm-2">
                                                                        <label>DHD No.</label>
                                                                        <input type="text" class="form-control" name="dhd_no" id="txtDHDNo">
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label>Device Name</label>
                                                                        <input type="text" class="form-control" name="device_name" placeholder="Dropdown" id="txtDeviceName">
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <label>Device Code</label>
                                                                        <input type="text" class="form-control" name="device_code" id="txtDeviceCode" >
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <label>Material Name</label>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label>Material Lot No.</label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <input type="text" class="form-control" name="mtl_name" placeholder="Dropdown" id="txtMaterialName">
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <input type="text" class="form-control" name="mtl_lot_virgin" placeholder="(Virgin)" id="txtMaterialLotVirgin">
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <input type="text" class="form-control" name="mtl_lot_recycle" placeholder="(Recycle)" id="txtMaterialLotRecycle">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <label>Material Mixing</label>
                                                                    <div class="col-sm-4">
                                                                        <input type="text" class="form-control" name="mtl_mix_virgin" placeholder="(Virgin - Kgs.)" id="txtMaterialMixVirgin">
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <input type="text" class="form-control" name="mtl_mix_recycle" placeholder="(Recycle - Kgs.)" id="txtMaterialMixRecycle">
                                                                    </div>
                                                                    <div class="col-sm-4">
                                                                        <input type="text" class="form-control" name="mtl_ttl_mixing" placeholder="Total Mixed Mat'ls (Kgs.)" id="txtMaterialTotalMixing" >
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Material Drying</label>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <label>Temperature</label>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label>Time</label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-3">
                                                                        <input type="text" class="form-control" name="mtl_dry_setting" placeholder="Setting" id="txtMaterialDrySetting">
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <input type="text" class="form-control" name="mtl_dry_actual" placeholder="Actual" id="txtMaterialDryActual">
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <input type="text" class="form-control" name="mtl_dry_timeIn" placeholder="IN" id="txtMaterialDryTimeIn">
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <input type="text" class="form-control" name="mtl_dry_timeOut" placeholder="OUT" id="txtMaterialDryTimeOut">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>DHD Monitoring</label>
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <label>A Shift (1200hrs - 1300hrs)</label>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label>B Shift (0000hrs - 0100hrs)</label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-sm-2">
                                                                        <input type="text" class="form-control" name="dhd_ashift_actual_temp" placeholder="Act Temp" id="txtDHDAActualTemp">
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <input type="text" class="form-control" name="dhd_ashift_mtl_level" placeholder="Mtl Level" id="txtDHDAMtlLevel">
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <input type="text" class="form-control" name="dhd_ashift_time" placeholder="Time" id="txtDHDATime">
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <input type="text" class="form-control" name="dhd_bshift_actual_temp" placeholder="Act Temp" id="txtDHDBActualTemp">
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <input type="text" class="form-control" name="dhd_bshift_mtl_level" placeholder="Mtl Level" id="txtDHDBMtlLevel">
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <input type="text" class="form-control" name="dhd_bshift_time" placeholder="Time" id="txtDHDBTime">
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <label>Person In-Charge</label>
                                                                        <div class="input-group">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-primary btnSearchPoNo" title="Scan PO Code"><i class="fa fa-qrcode"></i></button>
                                                                            </div>
                                                                        <input type="text" class="form-control" name="person_incharge" placeholder="" id="txtPersonIncharge">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label>QC Inspector</label>
                                                                        <div class="input-group">
                                                                            <div class="input-group-prepend">
                                                                                <button type="button" class="btn btn-primary btnSearchPoNo" title="Scan PO Code"><i class="fa fa-qrcode"></i></button>
                                                                            </div>
                                                                        <input type="text" class="form-control" name="qc_inspector" placeholder="" id="txtQCInspector">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <label>Remarks</label>
                                                                        <input type="text" class="form-control" name="remarks" placeholder="" id="txtRemarks">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <label>Legend:</label><br>
                                                                        <p>OK - to use, No Problem</p>
                                                                        <p>NG - to use, With Problem</p>
                                                                        <p>N/A - Put N/A on the In-Charge portion if the activity is not required.</p>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label>Note:</label><br>
                                                                        <p><> QC shalll conduct checking during loading of materials. Checkpoints => correctness of material and drying setting.</p>
                                                                        <p><> Frequency of prodcution checking/monitoring of DHD => once per shift.</p>
                                                                        <p><> In the case of NIGHT SHIFT or the absence of material prep. it is responsibilty of machine operator to update all the entry on this form.</p>
                                                                        <p><> In the case of continuous use and no material loading was done, Person In-charge to only accomplish the "DHD Monitoring" and put "Continuous production" on remarks portion.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer justify-content-between">
                                                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" id="btnProcess" class="btn btn-dark"><i class="fa fa-check"></i> Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div><!-- /.End Page Content -->
                            </div><!-- /.Card -->
                        </div><!-- /.Col -->
                    </div><!-- /.Row -->
                </div><!-- /.Container-fluid -->
            </section><!-- /.Content -->
        </div><!-- /.Content-wrapper -->
    @endsection

    @section('js_content')
        <script type="text/javascript">
            let dtDHDMonitoring
            let tist

            $(document).ready(function() {
                $('.select2bs4').select2({
                    theme: 'bootstrap-5'
                })

                // ======================= START DATA TABLE =======================
                dtDHDMonitoring = $("#tblDHDMonitoring").DataTable({
                    "processing"    : false,
                    "serverSide"    : true,
                    "destroy"       : true,
                    "ajax" : {
                        url: "view_dhd_monitoring",
                        data: function (param){
                            // param.tist = tist
                        },
                    },
                    "columns":[
                        { "data" : "action", orderable:false, searchable:false },
                        { "data" : "created_by" },
                        { "data" : "dhd_number" },
                        { "data" : "device_code" },
                        { "data" : "device_name" },
                        { "data" : "mtl_name" },
                        { "data" : "mtl_mix_virgin" },
                        { "data" : "mtl_mix_recycle" },
                        { "data" : "total_mixed_mat_kgs" },
                        { "data" : "mtl_lot_virgin" },
                        { "data" : "mtl_lot_recycle" },
                        { "data" : "mtl_dry_setting" },
                        { "data" : "mtl_dry_actual" },
                        { "data" : "mtl_dry_timeIn" },
                        { "data" : "mtl_dry_timeOut" },
                        { "data" : "dhd_ashift_actual_temp" },
                        { "data" : "dhd_bshift_actual_temp" },
                    ],
                    "columnDefs": [
                        // { className: "align-center", targets: [1, 2] },
                    ],
                })

                $('#formDHDMonitoring').submit(function(e){
                    e.preventDefault();

                    console.log('sdfsdfsf');
                    $.ajax({
                        type: "post",
                        url: "add_dhd_monitoring",
                        data: $('#formDHDMonitoring').serialize(),
                        dataType: "json",
                        success: function (response) {
                            if(response['result'] == 1){
                                dtDHDMonitoring.draw();
                                $('#modalAddDHD').modal('hide');
                                toastr.success('Data has been updated successfully!');
                            }
                        }
                    });
                });

                $(document).on('click', '.btnEdit', function(e){
                    let id = $(this).data('id');
                    $.ajax({
                        type: "get",
                        url: "get_dhd_monitoring",
                        data: {
                            "id" : id
                        },
                        dataType: "json",
                        success: function (response) {

                            $('#txtDHDId').val(response['id']);
                            $('#txtDHDNo').val(response['dhd_no']);
                            $('#txtDeviceName').val(response['device_name']);
                            $('#txtDeviceCode').val(response['device_code']);
                            $('#txtMaterialName').val(response['mtl_name']);
                            $('#txtMaterialLotVirgin').val(response['mtl_lot_virgin']);
                            $('#txtMaterialLotRecycle').val(response['mtl_lot_recycle']);
                            $('#txtMaterialMixVirgin').val(response['mtl_mix_virgin']);
                            $('#txtMaterialMixRecycle').val(response['mtl_mix_recycle']);
                            $('#txtMaterialTotalMixing').val(response['mtl_ttl_mixing']);
                            $('#txtMaterialDrySetting').val(response['mtl_dry_setting']);
                            $('#txtMaterialDryActual').val(response['mtl_dry_actual']);
                            $('#txtMaterialDryTimeIn').val(response['mtl_dry_timeIn']);
                            $('#txtMaterialDryTimeOut').val(response['mtl_dry_timeOut']);
                            $('#txtDHDAActualTemp').val(response['dhd_ashift_actual_temp']);
                            $('#txtDHDAMtlLevel').val(response['dhd_ashift_mtl_level']);
                            $('#txtDHDATime').val(response['dhd_ashift_time']);
                            $('#txtDHDBActualTemp').val(response['dhd_bshift_actual_temp']);
                            $('#txtDHDBMtlLevel').val(response['dhd_bshift_mtl_level']);
                            $('#txtDHDBTime').val(response['dhd_bshift_time']);
                            $('#txtPersonIncharge').val(response['person_incharge']);
                            $('#txtQCInspector').val(response['qc_inspector']);
                            $('#txtRemarks').val(response['remarks']);

                            $('#modalAddDHD').modal('show');

                        }
                    });
                });

                $("#txtMaterialMixRecycle").keyup(function(){
                    $("#txtMaterialTotalMixing").val(parseInt($("#txtMaterialMixVirgin").val()) + parseInt($(this).val()));
                });
            })
        </script>
    @endsection
@endauth
