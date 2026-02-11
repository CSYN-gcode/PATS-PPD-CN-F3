@php $layout = 'layouts.admin_layout'; @endphp

{{-- Here I removed the @auth because the dashboard isn't loading properly --}}
@auth
    @extends($layout)
    @section('title', 'DMRPQC (PPS-TS)')
    @section('content_page')
    <style>
        .select2-container--bootstrap-5 .select2-selection{
            /* .input-group .select2-container--bootstrap-5 .select2-selection{ */
            /* .select2-selection .select2-selection--single { */
            min-height: calc(1.9rem + 2px) !important;
            font-size: 0.875rem !important;
            /* min-height: calc(1.9rem + 2px) !important;
            height: calc(1.9rem + 2px) !important;
            line-height: normal !important;
            font-size: 0.875rem !important; */
        }

        .input-group .select2-container--bootstrap-5 .select2-selection{
            height: calc(1.9rem + 2px) !important;
            font-size: 0.875rem !important;
        }

        /* .input-group .select2-container--bootstrap-5 .select2-dropdown { */
            /* position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            width: 100% !important;
            z-index: 1051 !important;
        } */

        table.dataTable td {
            font-size:  1em;
            /* padding:    5px 5px !important; */
            text-align: center;
        }

    </style>
    <div class="content-wrapper">
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-12">
                <h4>Die set Maintenance Runcard & Product Qualification Checksheet</h4>
              </div>
            </div>
          </div>
        </section>

        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="card card-dark">
                    <div class="card-header">
                        <h3 class="card-title">Die-set Maintenance Preparation Requests</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div style="float: right;">
                                <button class="btn btn-dark btnx-sm" style="float: right;" id="btnShowAddRequest"><i class="fa fa-plus"></i> New Prepartion Request</button>
                            </div> <br><br>

                            <div class="text-left mt-4 d-flex flex-row">
                                {{-- <div class="text-left mt-4 d-flex flex-row">
                                    <div class="form-group ml-3 col-2">
                                        <label><strong>Fiscal Year :</strong></label>
                                        <select class="form-control select2bs4 selectYearEnergy" name="fiscal_year"
                                            id="selFiscalYearEnergy" style="width: 100%;">
                                            <!-- Code generated -->
                                        </select>
                                    </div>

                                    <div class="form-group ml-3 col-2">
                                        <label><strong>Month :</strong></label>
                                        <select class="form-control select2bs4 selectMonthInkSG" name="month_value"
                                            id="selMonthInkSG" style="width: 100%;">
                                            <option value="0" disabled selected>Select Month</option>
                                            <option value="" >All</option>
                                            <option value="January">January</option>
                                            <option value="February">February</option>
                                            <option value="March">March</option>
                                            <option value="April">April</option>
                                            <option value="May">May</option>
                                            <option value="June">June</option>
                                            <option value="July">July</option>
                                            <option value="August">August</option>
                                            <option value="September">September</option>
                                            <option value="October">October</option>
                                            <option value="November">November</option>
                                            <option value="December">December</option>
                                        </select>
                                    </div>

                                    <div class="form-group ml-3 col-2">
                                        <label><strong>Department :</strong></label>
                                        <select class="form-control select2bs4 selectDepartment" name="department_value"
                                            id="selDepartment" style="width: 100%;">
                                            <option value="0" disabled selected>Select Department</option>
                                            <option value="">All</option>
                                            <option value="1">BOD</option>
                                            <option value="2">IAS</option>
                                            <option value="3">FIN</option>
                                            <option value="4">HRD</option>
                                            <option value="5">ESS</option>
                                            <option value="6">LOG</option>
                                            <option value="7">FAC</option>
                                            <option value="8">ISS</option>
                                            <option value="9">QAD</option>
                                            <option value="10">EMS</option>
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="form-group ml-3 col-2">
                                    @php
                                        $factory = request()->query('factory', 'F1'); // default to F1 if not set
                                    @endphp

                                    <label><strong>Category : &nbsp;</strong></label>
                                    {{-- <input type="text" id="" class="form-control form-control-sm" name="" value="Factory 1" readonly> --}}
                                    <!-- Hidden input for backend -->
                                    {{-- <input type="hidden" name="factory" id="txtFactory" value="{{ $factory }}"> --}}

                                    <!-- Or selectable if user can switch -->
                                    <select id="factorySelect" class="form-control form-control-sm select2bs5" name="factory" disabled>
                                        <option value="1" {{ $factory == 'F1' ? 'selected' : '' }}>Factory 1</option>
                                        <option value="2" {{ $factory == 'F3' ? 'selected' : '' }}>Factory 3</option>
                                    </select>
                                </div>

                                <div class="form-group ml-3 col-2">
                                    <label><strong>Filter Year : &nbsp;</strong></label>
                                    <input type="text" id="SearchYear" class="form-control form-control-sm" name="year" title="<?php echo date('Y'); ?>" value="<?php echo date('Y'); ?>">
                                </div>

                                <div class="form-group ml-3 col-2">
                                    <label><strong>Month :</strong></label>
                                    <select class="form-control form-control-sm selectMonth select2bs5" name="month_value" id="SelectMonth">
                                        <option value="<?php echo date('m'); ?>" readonly><?php echo date('F'); ?></option><!-- selected -->
                                        <option value="" selected>All</option>
                                        <option value="01">January</option>
                                        <option value="02">February</option>
                                        <option value="03">March</option>
                                        <option value="04">April</option>
                                        <option value="05">May</option>
                                        <option value="06">June</option>
                                        <option value="07">July</option>
                                        <option value="08">August</option>
                                        <option value="09">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>

                                <div class="form-group ml-3 col-2">
                                    <label><strong>Advanced Search :</strong></label>
                                    <button class="form-control form-control-sm btn-info btn-outline-dark border-0" data-bs-toggle="modal" data-bs-target="#modalAdvancedSearch" id=""><i class="fas fa-search fa-md"></i> Advanced Search</button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered text-center table-hover" id="tbl_dmrpqc" style="min-width: 900px; width: 100%;">
                                <thead>
                                    <tr class="bg-light">
                                        <th style="width: 10%;">Action</th>
                                        <th style="width: 8%;">Status</th>
                                        <th style="width: 8%;">Process</th>
                                        <th style="width: 15%;">Device Name</th>
                                        <th style="width: 15%;">PO No.</th>
                                        <th style="width: 10%;">Item Code</th>
                                        <th style="width: 12%;">Request Type</th>
                                        {{-- <th>Request By</th> --}}
                                        <th style="width: 10%;">Date/Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                </table>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <div class="modal fade" id="modalProdIdentification" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-xl-custom modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                <h3 class="modal-title" style="text-align: center"> Die set Maintenance Runcard & Product Qualification Checksheet </h3>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>

                <form id="frm_prod_identification" method="post">
                  @csrf
                  <input name="_token" id="csrf_token" value="{{ csrf_token()}}" hidden>
                  <div class="modal-body">
                    <div class="row d-none">
                    {{-- <div class="row"> --}}
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Category</span>
                                </div>
                                <input type="text" name="form_category" id="txtCategory" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Global ID</span>
                                </div>
                                <input type="text" name="global_dmrpqc_id" id="txt_global_dmrpqc_id" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Process Status</span>
                                </div>
                                <input type="text" name="global_status" id="txt_global_status" readonly>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">User ID</span>
                                </div>
                                <input type="text" class="form-control form-control-sm" name="user_id" id="txt_user_id" readonly>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="input-group input-group-sm mb-2">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">User Name</span>
                                </div>
                                <input type="text" class="form-control form-control-sm" name="user_name" id="txt_user_name" readonly>
                            </div>
                        </div>
                    </div>

                        <div class="accordion" id="accordionMain">
                            <div class="Part1">
                                {{-- <h5 class="modal-title"> I. Product Identification & Drawing Revision Checking <strong>(Responsible:Machine Operator)</strong></h5> --}}
                                <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <h5 class="modal-title"> I. Product Identification & Drawing Revision Checking <strong>(Responsible:Production Operator)</strong></h5>
                                </button>

                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne">
                                    <div class="row"><!-- 1st row -->
                                        <div class="col-sm-4">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100" id="basic-addon1">Device Name</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" index="1.0" id="frm_txt_device_name" name="device_name" style="color: blue; font-weight: bold;" readonly>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100" id="basic-addon1">Item Code</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="frm_txt_item_code" name="item_code" readonly>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100" id="basic-addon1">Rev No.</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="frm_txt_rev_no" name="rev_no" readonly>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100" id="basic-addon1">Drawing No.</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="frm_txt_drawing_no" name="drawing_no" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row"><!-- 2nd row -->

                                        <div class="col-sm-4">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100 bg-primary" id="basic-addon1">P.O #: (Press Enter to Search)</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="frm_txt_po_no" name="po_no" placeholder="Insert PO Number">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100 bg-primary" id="basic-addon1">Request Type</span>
                                                </div>
                                                    <select class="form-control" type="text" name="request_type" id="frm_request_type">
                                                        <option value="" disabled selected>Select Request type</option>
                                                        <option value="1">For Maintenance</option>
                                                        <option value="2">Temporary Stop</option>
                                                        <option value="3">Comma Change</option>
                                                    </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100" id="basic-addon1">Die No.</span>
                                                </div>
                                                <input type="text" class="form-control form-control-sm" id="frm_txt_die_no" name="die_no" readonly>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100" id="basic-addon1">Date/Time</span>
                                                </div>
                                                <input class="form-control form-control-sm" id="frm_txt_start_datetime" name="txt_start_datetime" placeholder="(Auto Generate)" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-30">
                                                    <p class="input-group-text" id="basic-addon1">Drawing & rev# validation:</p>
                                                </div>
                                                <p class="input-group-text" id="basic-addon1">Using PO balance from PPC with drawing & rev#, compare it versus RAPID.</p>
                                                <p class="input-group-text" id="basic-addon1">if not tally, inform Supervisor & do not proceed the production </p>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="input-group input-group-sm mb-2">
                                                <div class="input-group-prepend w-50">
                                                    <span class="input-group-text w-100" id="basic-addon1">Requested By:</span>
                                                    <input type="hidden" class="form-control form-control-sm" name="requested_by_id" id="frm_txt_requested_by_id">
                                                </div>
                                                <input type="text" class="form-control form-control-sm" name="requested_by" id="frm_txt_requested_by" placeholder="(Auto Generate)" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="border: 2px; border-style:solid; padding:8px;">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend w-30">
                                                        <p class="input-group-text" id="basic-addon1">Previous Shots</p>
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm" name="prev_shots" id="frm_txt_prev_shots" placeholder="(Auto Generate)" readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100" id="basic-addon1">Accumulated Shots</span>
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm" name="prev_shots_accume" id="frm_txt_prev_shot_accum" placeholder="(Auto Generate)" readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend w-30">
                                                        <p class="input-group-text" id="basic-addon1">Maintenance Cycle</p>
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm" name="prev_maint_cycle" id="frm_txt_prev_maint_cycle" placeholder="(Auto Generate)" readonly>
                                                </div>
                                            </div>

                                            <div class="col-sm-2">
                                                <div class="input-group input-group-sm mb-2">
                                                    <div class="input-group-prepend w-50">
                                                        <span class="input-group-text w-100" id="basic-addon1">Machine No.</span>
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm" name="prev_machine_no" id="frm_txt_prev_machine_no" placeholder="(Auto Generate)" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Part2"><!-- PART II Division Start-->
                                    {{-- <h5 class="modal-title">II. Dieset Condition <strong>(Responsible:Die Maintenance Engineering)</strong></h5> --}}
                                    <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <h5 class="modal-title">II. Dieset Condition <strong>(Responsible:Die Maintenance Engineering)</strong></h5>
                                    </button>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingOne">
                                        <div class="row col-sm-12">
                                            <div class="col-sm-3 border border-right-0 border-bottom-0 border-dark"  id="action_done_div">
                                                <strong><label class="form-check-label"> Action Done </label></strong>
                                                <div class="table-responsive col-sm-12">
                                                    <table id="tbl_action_done" class="table table-sm table-borderless table-hover display nowrap" style="width: 100%;">
                                                        {{-- table-borderless --}}
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <div style="margin-left: 0" class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data ActionDoneChckbox" type="checkbox" index="1.0" name="action_1" id="frm_txt_action_1" value="1">
                                                                        &NonBreakingSpace;
                                                                        <label class="form-check-label dieset_condition_data"> 1. Mold Cleaned</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data ActionDoneChckbox" type="checkbox" index="1.1" name="action_2" id="frm_txt_action_2" value="1">
                                                                        &NonBreakingSpace;
                                                                        <label class="form-check-label dieset_condition_data"> 2. Mold Check</label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td width="25">
                                                                    {{-- <input class="form-check-input" type="checkbox" name="action_3" id="frm_txt_action_3"> --}}
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data ActionDoneChckbox" type="checkbox" index="1.2" name="action_3" id="frm_txt_action_3" value="1">
                                                                </td>
                                                                <td colspan="3">
                                                                    <label class="form-check-label dieset_condition_data"> 3. Device conversion (Coma change)</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data ActionDoneChckbox" type="checkbox" index="1.3" name="action_4" id="frm_txt_action_4" value="1">
                                                                </td>
                                                                <td colspan="3">
                                                                    <label class="form-check-label dieset_condition_data"> 4. Die set Overhaul</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data Action4Chckbox" type="checkbox" index="1.4" name="action_4a" id="frm_txt_action_4a" value="1" disabled>
                                                                        &NonBreakingSpace;
                                                                        <label class="form-check-label dieset_condition_data"> Fix side</label>
                                                                    </div>
                                                                    <div style="margin-left: 2%" class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data Action4Chckbox" type="checkbox" index="1.5" name="action_4b" id="frm_txt_action_4b" value="1" disabled>
                                                                        &NonBreakingSpace;
                                                                        <label class="form-check-label dieset_condition_data"> Movement side</label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data Action4Chckbox" type="checkbox" index="1.6" name="action_4c" id="frm_txt_action_4c" value="1" disabled>
                                                                        &NonBreakingSpace;
                                                                        <label class="form-check-label dieset_condition_data"> With parts marking</label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data Action4Chckbox" type="checkbox" index="1.7" name="action_4d" id="frm_txt_action_4d" value="1" disabled>
                                                                        &NonBreakingSpace;
                                                                        <label class="form-check-label dieset_condition_data"> Without part(s) marking (Qty: )</label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <label class="form-check-label dieset_condition_data"> 5. With parts that can reversely installed</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data ActionDoneChckbox" type="radio" index="1.8" name="action_5" id="frm_txt_action_5_yes" value="1">
                                                                        &NonBreakingSpace;
                                                                        <label class="form-check-label dieset_condition_data"> Yes</label>
                                                                    </div>
                                                                    <div style="margin-left: 10%" class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data ActionDoneChckbox" type="radio" index="1.8" name="action_5" id="frm_txt_action_5_no" value="0">
                                                                        &NonBreakingSpace;
                                                                        <label class="form-check-label dieset_condition_data"> No</label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data ActionDoneChckbox" type="checkbox" index="1.9" name="action_6" id="frm_txt_action_6" value="1">
                                                                </td>
                                                                <td colspan="3">
                                                                    <label class="form-check-label dieset_condition_data"> 6. Repair</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data ActionDoneChckbox" type="checkbox" index="1.10" name="action_7" id="frm_txt_action_7" value="1">
                                                                </td>
                                                                <td colspan="2">
                                                                    <label class="form-check-label dieset_condition_data"> 7. Parts change</label>
                                                                </td>
                                                                <td>
                                                                    <button type="button" id="btnPartsDrawing" class="btn btn-primary btn-xs float-right">Parts Drawing Section</button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data Action7Chckbox" type="checkbox" index="1.11" name="action_7_a" id="frm_txt_action_7a" value="1">
                                                                        &NonBreakingSpace;
                                                                        <label class="form-check-label dieset_condition_data"> New</label>
                                                                    </div>
                                                                    <div style="margin-left: 8%" class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data Action7Chckbox" type="checkbox" index="1.12" name="action_7_b" id="frm_txt_action_7b" value="1">
                                                                        &NonBreakingSpace;
                                                                        <label class="form-check-label dieset_condition_data"> Fabricated</label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data Action7Chckbox" type="checkbox" name="action_7c" index="1.13" id="frm_txt_action_7c" value="1">
                                                                        &NonBreakingSpace;
                                                                        <label class="form-check-label dieset_condition_data"> With part(s) marking</label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data Action7Chckbox" type="checkbox" name="action_7d" index="1.14" id="frm_txt_action_7d" value="1">
                                                                        &NonBreakingSpace;
                                                                        <label class="form-check-label dieset_condition_data"> With die-set part(s) change notice</label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 border border-left-0 border-bottom-0 border-dark">
                                                <div class="col-sm-10">
                                                    <strong><label class="form-check-label"> Details of activity:</label></strong>
                                                    <br>
                                                    <textarea class="form-control dieset_condition_data" style="height: 150px;" name="details_of_activity" id="frm_txt_details_of_activity" required></textarea>
                                                </div>
                                                <br>
                                                <div class="table-responsive col-sm-12">
                                                    <div class="text-right">
                                                        <button type="button" class="btn btn-primary btn-sm float-right" id="btn_add_row"><i class="fa fa-check fa-xs icon_save"></i> Add Row</button>
                                                        <button type="button" class="btn btn-sm btn-danger d-none float-right mr-2" id="btn_remove_row"><i class="fas fa-times"></i> Remove Row</button>
                                                    </div>
                                                    <table id="tbl_parts_no_and_qty" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                        <thead>
                                                            <tr class="bg-light">
                                                              <td hidden style="width: 15%;">Counter</td>
                                                              <td style="width: 5%;">#</td>
                                                              <td style="width: 40%;">
                                                                <label class="form-check-label"> Parts No.</label>
                                                              </td>
                                                              <td style="width: 40%;">
                                                                <label class="form-check-label"> Quantity</label>
                                                              </td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td hidden>
                                                                    <input type="text" class="form-control" name="row_counter" id="row_counter" value="1" readonly>
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <label class="form-check-label"> 1.</label>
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control dieset_condition_data" index="4.1" name="parts_no" id="frm_parts_no_1">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control dieset_condition_data" index="4.1" name="quantity" id="frm_quantity_1">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="table-responsive col-sm-12">
                                                    <table id="tbl_date_start_finish" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                        <tbody
                                                            <tr class="text-center align-middle">
                                                                <td colspan="2">
                                                                    <label class="form-check-label"> Date & Time Start</label>
                                                                </td>
                                                                {{-- <td>
                                                                    <label class="form-check-label"> Time Start</label>
                                                                </td> --}}
                                                                <td colspan="2">
                                                                    <label class="form-check-label"> Date & Time Finish</label>
                                                                </td>
                                                                {{-- <td>
                                                                    <label class="form-check-label"> Time Finish</label>
                                                                </td> --}}
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input type="date" class="form-control text-center align-middle dieset_condition_data" name="action_done_date_start" id="frm_txt_action_date_start" required>
                                                                </td>
                                                                <td>
                                                                    <input type="time" class="form-control text-center align-middle dieset_condition_data" name="action_done_start_time" id="frm_txt_action_start_time" required>
                                                                    {{-- oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" --}}
                                                                </td>
                                                                <td>
                                                                    <input type="date" class="form-control text-center align-middle dieset_condition_data" name="action_done_date_finish" id="frm_txt_action_date_finish" required>
                                                                </td>
                                                                <td>
                                                                    <input type="time" class="form-control text-center align-middle dieset_condition_data" name="action_done_finish_time" id="frm_txt_action_finish_time" required>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-center align-middle">
                                                                <td>
                                                                    <label class="form-check-label" style="margin-top: 5%" > In-charged</label>
                                                                    <input type="text" class="form-control dieset_condition_data" name="action_done_in_charged_id" id="frm_txt_action_done_in_charged_id" hidden>
                                                                </td>
                                                                <td colspan="3">
                                                                    <input type="text" class="form-control dieset_condition_data" name="action_done_in_charged" id="frm_txt_action_done_in_charged" placeholder="(Auto Generate)" readonly>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="col-sm-3 border border-left-0 border-right-0 border-bottom-0 border-dark">
                                                <strong><label class="form-check-label"> Checkpoints during Mold overhauling:</label></strong>
                                                <div class="table-responsive col-sm-12">
                                                    <table id="tbl_check_points" class="table table-sm table-borderless table-hover display nowrap" style="width: 100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2"></td>
                                                                <td width="30" class="form-check-label">OK</td>
                                                                <td width="30" class="form-check-label">N.G</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">1. Marking Check</td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.0" name="check_point_1" id="frm_check_point_1_ok" value="OK">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.0" name="check_point_1" id="frm_check_point_1_ng" value="NG">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">2. Tanshi Pin</td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.1" name="check_point_2" id="frm_check_point_2_ok" value="OK">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.1" name="check_point_2" id="frm_check_point_2_ng" value="NG">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <div style="margin-left: 15%" class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data" type="checkbox" index="2.2" name="check_point_2a" value="1" id="frm_check_point_2a">
                                                                        &NonBreakingSpace;
                                                                        <label class="form-check-label"> Crack</label>
                                                                    </div>
                                                                    <div style="margin-left: 2%" class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data" type="checkbox" index="2.3" name="check_point_2b" value="1" id="frm_check_point_2b">
                                                                        &NonBreakingSpace;
                                                                        <label class="form-check-label"> Bend</label>
                                                                    </div>
                                                                    <div style="margin-left: 2%" class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data" type="checkbox" index="2.4" name="check_point_2c" value="1" id="frm_check_point_2c">
                                                                        &NonBreakingSpace;
                                                                        <label class="form-check-label"> Worn out</label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">3. Dent</td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.5" name="check_point_3" id="frm_check_point_3_ok" value="OK">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.5" name="check_point_3" id="frm_check_point_3_ng" value="NG">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">4. Porous</td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.6" name="check_point_4" id="frm_check_point_4_ok" value="OK">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.6" name="check_point_4" id="frm_check_point_4_ng" value="NG">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">5. Ejection Pin</td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.7" name="check_point_5" id="frm_check_point_5_ok" value="OK">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.7" name="check_point_5" id="frm_check_point_5_ng" value="NG">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">6. Coma</td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.8" name="check_point_6" id="frm_check_point_6_ok" value="OK">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.8" name="check_point_6" id="frm_check_point_6_ng" value="NG">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <div class="form-check form-check-inline">
                                                                        7. Gasvent
                                                                    </div>
                                                                    <br>
                                                                    <div style="margin-left: 17%" class="form-check form-check-inline">
                                                                        Required: 0.005mm min
                                                                    </div>
                                                                    <br>
                                                                    <div style="margin-left: 15%" class="input-group input-group-sm col-sm-10">
                                                                        <span class="input-group-text">Actual: </span>
                                                                        <input type="number" class="form-control dieset_condition_data" index="2.9" name="check_point_7" id="frm_check_point_7">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">8. Assy. Orientation</td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.10" name="check_point_8" id="frm_check_point_8_ok" value="OK">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.10" name="check_point_8" id="frm_check_point_8_ng" value="NG">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">9. F.S and M.S fittings</td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.11" name="check_point_9" id="frm_check_point_9_ok" value="OK">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.11" name="check_point_9" id="frm_check_point_9_ng" value="NG">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">10. Sub. Gate appearance</td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.12" name="check_point_10" id="frm_check_point_10_ok" value="OK">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="2.12" name="check_point_10" id="frm_check_point_10_ng" value="NG">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-sm-2 border border-left-0 border-bottom-0 border-dark">
                                                <strong><label class="form-check-label"> Remarks:</label></strong>
                                                <br>
                                                <textarea class="form-control dieset_condition_data" style="height: 150px;" name="check_point_remarks" id="frm_check_point_remarks"></textarea>
                                            </div>
                                        </div>
                                        <div class="row col-sm-12">
                                            <div class="col-sm-4 border border-right-0 border-dark">
                                                <strong><label class="form-check-label"> Mold check prior endorse to production:</label></strong>
                                                <div class="table-responsive col-sm-12">
                                                    <table id="tbl_mold_check" class="table table-sm table-borderless table-hover display nowrap" style="width: 100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2"></td>
                                                                <td width="30" class="form-check-label">OK</td>
                                                                <td width="30" class="form-check-label">N.G</td>
                                                                <td width="30" class="form-check-label">N/A</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">1. Withdraw Pin (External)</td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.0" name="mold_check_1" id="frm_mold_check_1_ok" value="OK">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.0" name="mold_check_1" id="frm_mold_check_1_ng" value="NG">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.0" name="mold_check_1" id="frm_mold_check_1_na" value="N/A">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">2. Withdraw Pin (Internal)</td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.1" name="mold_check_2" id="frm_mold_check_2_ok" value="OK">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.1" name="mold_check_2" id="frm_mold_check_2_ng" value="NG">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.1" name="mold_check_2" id="frm_mold_check_2-na" value="N/A">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">3. Slidecore stopper</td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.2" name="mold_check_3" id="frm_mold_check_3_ok" value="OK">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.2" name="mold_check_3" id="frm_mold_check_3_ng" value="NG">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.2" name="mold_check_3" id="frm_mold_check_3_na" value="N/A">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">4. Locator ring</td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.3" name="mold_check_4" id="frm_mold_check_4_ok" value="OK">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.3" name="mold_check_4" id="frm_mold_check_4_ng" value="NG">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.3" name="mold_check_4" id="frm_mold_check_4_na" value="N/A">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">5. Bolts and Nuts</td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.4" name="mold_check_5" id="frm_mold_check_5_ok" value="OK">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.4" name="mold_check_5" id="frm_mold_check_5_ng" value="NG">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.4" name="mold_check_5" id="frm_mold_check_5_na" value="N/A">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">6. Stripper plate</td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.5" name="mold_check_6" id="frm_mold_check_6_ok" value="OK">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.5" name="mold_check_6" id="frm_mold_check_6_ng" value="NG">
                                                                </td>
                                                                <td>
                                                                    &nbsp;
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" index="3.5" name="mold_check_6" id="frm_mold_check_6_na" value="N/A">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 border border-left-0 border-dark">
                                                <div class="col-sm-12">
                                                    <strong><label class="form-check-label"> Remarks:</label></strong>
                                                    <br>
                                                    <textarea class="form-control dieset_condition_data" style="height: 200px;" name="mold_check_remarks" id="frm_mold_check_remarks"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 border border-left-0 border-dark">
                                                <div class="table-responsive col-sm-12">
                                                    <strong><label class="form-check-label">References Used </label></strong>
                                                    <table id="tbl_references_used" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                        <tbody>
                                                            <tr class="align-middle">
                                                                <td width="25">
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" value="1" name="references_used" id="frm_ref_used_1">
                                                                </td>
                                                                <td colspan="3">
                                                                    <label class="form-check-label"> Eval Sample</label>
                                                                </td>
                                                            </tr>
                                                            <tr class="align-middle">
                                                                <td>
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" value="2" name="references_used" id="frm_ref_used_2">
                                                                </td>
                                                                <td colspan="3">
                                                                    <label class="form-check-label"> Japan Sample</label>
                                                                </td>
                                                            </tr>
                                                            <tr class="align-middle">
                                                                <td>
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" value="3" name="references_used" id="frm_ref_used_3">
                                                                </td>
                                                                <td colspan="3">
                                                                    <label class="form-check-label"> Dieset Assy. Drawing</label>
                                                                </td>
                                                            </tr>
                                                            <tr class="align-middle">
                                                                <td>
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_data" type="radio" value="4" name="references_used" id="frm_ref_used_4">
                                                                </td>
                                                                <td colspan="3">
                                                                    <label class="form-check-label"> Last Production Sample</label>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-sm-12">
                                            <div class="col-sm-12 border border-top-0 border-dark">
                                                <div class="table-responsive col-sm-8">
                                                    <table id="tbl_checked_by" class="table table-sm table-borderless display nowrap" style="width: 100%;">
                                                        <tbody>
                                                            <tr class="text-center align-middle">
                                                                <td>
                                                                    <label class="form-check-label"> Checked by:</label>
                                                                    <input type="text" class="form-control text-center align-middle dieset_condition_data" name="mold_check_checked_by_id" id="frm_txt_mold_check_checked_by_id" hidden>
                                                                </td>
                                                                <td>
                                                                    <label class="form-check-label"> Date/Time</label>
                                                                </td>
                                                                {{-- <td>
                                                                    <label class="form-check-label"> Time</label>
                                                                </td> --}}
                                                                <td>
                                                                    <label class="form-check-label"> Status</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <input type="text" class="form-control text-center align-middle dieset_condition_data" name="mold_check_checked_by" id="frm_txt_mold_check_checked_by" placeholder="(Auto Generate)" readonly>
                                                                </td>
                                                                <td>
                                                                    <input type="datetime" class="form-control text-center align-middle dieset_condition_data" name="mold_check_date_time" id="frm_mold_check_date_time" placeholder="(Auto Generate)" readonly>
                                                                </td>
                                                                {{-- <td>
                                                                    <input type="text" class="form-control" id="mold_check_time" readonly>
                                                                </td> --}}
                                                                <td width="25%">
                                                                    <input type="text" class="form-control text-center align-middle dieset_condition_data" name="mold_check_status" id="frm_mold_check_status" value='OK FOR SET-UP'>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-sm-12">
                                            <div class="col-sm-12 border border-top-0 border-dark">
                                                <div class="table-responsive col-sm-12" style="margin-bottom: -1%">
                                                    <table id="tbl_final_remarks" class="table table-sm table-borderless" style="width: 100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center align-middle">
                                                                    <label class="form-check-label"> Remarks:</label>
                                                                </td>
                                                                <td colspan="3">
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data" type="radio" value="1" name="final_remarks" id="frm_final_remarks_1">
                                                                        <label class="form-check-label"> No problem found</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input dieset_condition_data" type="radio" value="2" name="final_remarks" id="frm_final_remarks_2">
                                                                        <label class="form-check-label"> With problem found</label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <!-- PART II Division End -->
                            <!-- PART III Division Start-->
                            <div class="Part3">
                                {{-- <h5 class="modal-title">III. Dieset Condition Checking <strong>(Responsible:Die Maintenance)</strong></h5> --}}
                                <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <h5 class="modal-title">III. Dieset Condition Checking <strong>(Responsible:Die Maintenance)</strong></h5>
                                </button>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingOne">
                                    <div class="row col-sm-12">
                                        <div class="col-sm-12 border border-dark">
                                            <br>
                                            <div class="col-sm-6">
                                                <div class="table-responsive col-sm-12">
                                                    <table id="tbl_dieset_condition_checking" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                        <tbody>
                                                            <tr class="align-middle">
                                                                <td width="25">
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_checking_data" type="checkbox" id="frm_txt_good_condition" name="good_condition" value="1">
                                                                </td>
                                                                <td colspan="3">
                                                                    <label class="form-check-label"> Good Condition</label>
                                                                </td>
                                                            </tr>
                                                            <tr class="align-middle">
                                                                <td>
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_checking_data" type="checkbox" id="frm_txt_under_longevity" name="under_longevity" value="1">
                                                                </td>
                                                                <td colspan="3">
                                                                    <label class="form-check-label"> Under Longevity</label>
                                                                </td>
                                                            </tr>
                                                            <tr class="align-middle">
                                                                <td>
                                                                    <input style="margin-left:0" class="form-check-input dieset_condition_checking_data" type="checkbox" id="frm_txt_problematic" name="problematic" value="1">
                                                                </td>
                                                                <td colspan="3">
                                                                    <label class="form-check-label"> Problematic Die set</label>
                                                                </td>
                                                            </tr>
                                                            <tr class="text-center align-middle">
                                                                <td colspan="2">
                                                                    <label class="form-check-label"> Checked by:</label>
                                                                    <input type="text" class="form-control text-center align-middle dieset_condition_checking_data" name="dieset_checking_checked_by_id" id="frm_txt_dieset_checking_checked_by_id" hidden>
                                                                </td>
                                                                <td>
                                                                    <label class="form-check-label"> Date</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">
                                                                    <input type="text" class="form-control text-center align-middle dieset_condition_checking_data" name="dieset_checking_checked_by" id="frm_txt_dieset_checking_checked_by" placeholder="(Auto Generate)" readonly>
                                                                </td>
                                                                <td>
                                                                    <input class="form-control text-center" id="frm_txt_dieset_condition_checking_date" name="dieset_condition_checking_date" placeholder="(Auto Generate)" readonly>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Part III Division End -->
                            <!-- PART IV Division Start-->
                            <div class="Part4">
                                {{-- <h5 class="modal-title">III. Dieset Condition Checking <strong>(Responsible:Die Maintenance)</strong></h5> --}}
                                <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                    <h5 class="modal-title">IV. Machine Set-up <strong>(Responsible:Production / Process Engineering)</strong></h5>
                                </button>
                                <div id="collapseFour" class="collapse" aria-labelledby="headingOne">
                                    <div class="row col-sm-12">
                                        <div class="col-sm-12 border border-dark d-flex justify-content-center">
                                            <div class="col-sm-10 mt-3">
                                                <div class="table-responsive col-sm-12">
                                                    <table id="tbl_machine_setup" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                        <thead>
                                                            <th colspan="2">Machine Adjustment</th>
                                                            <th class="text-center">In-Charge</th>
                                                            <th class="text-center">Date / Time</th>
                                                            <th class="text-center" style="width: 20%;">Remarks</th>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="align-middle">
                                                                <td width="30">
                                                                    <input style="margin-left:0; width:20px; height:20px;" class="form-check-input machine_setup_data first_adjustment" type="checkbox" id="frm_txt_machine_setup_1st_adjustment" name="machine_setup_1st_adjustment" value="1">
                                                                </td>
                                                                <td class="align-middle">
                                                                    <label class="form-check-label"> 1st Adjustment</label>
                                                                </td>
                                                                <td>
                                                                    {{-- <input type="hidden" class="form-control machine_setup_data" id="frm_txt_machine_setup_1st_in_charged_id" name="machine_setup_1st_in_charged_id">
                                                                    <input type="text" class="form-control machine_setup_data text-center" id="frm_txt_machine_setup_1st_in_charged" name="machine_setup_1st_in_charged" placeholder="(Auto Generate)" readonly> --}}
                                                                    <select class="form-control select2bs5 machine_setup_data first_adjustment" id="selProductionUser" name="machine_setup_1st_in_charged" style="width: 100%;">
                                                                        <option disabled selected>-- Select Production --</option>
                                                                        {{-- <option value="">-- N/A --</option> --}}
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="datetime" class="form-control text-center align-middle machine_setup_data first_adjustment" id="frm_txt_machine_setup_1st_datetime" name="machine_setup_1st_datetime" placeholder="(Auto Generate)" readonly>
                                                                </td>
                                                                 <td>
                                                                    {{-- <input type="text" class="form-control machine_setup_data" id="frm_txt_machine_setup_1st_remarks" name="machine_setup_1st_remarks"> --}}
                                                                    <select type="text" class="form-control select2bs5 machine_setup_data first_adjustment text-center" id="frm_txt_machine_setup_1st_remarks" name="machine_setup_1st_remarks" style="width: 100%;">
                                                                        <option value="" disabled selected>Please Select One</option>
                                                                        <option value="1">For Qualification</option>
                                                                        <option value="2">NG</option>
                                                                        <option value="3">Mold Down</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr class="align-middle">
                                                                <td width="30">
                                                                    <input style="margin-left:0; width:20px; height:20px;" class="form-check-input machine_setup_data second_adjustment" type="checkbox" id="frm_txt_machine_setup_2nd_adjustment" name="machine_setup_2nd_adjustment" value="1">
                                                                </td>
                                                                <td class="align-middle">
                                                                    <label class="form-check-label"> 2nd Adjustment</label>
                                                                </td>
                                                                <td>
                                                                    <select class="form-control select2bs5 machine_setup_data second_adjustment" id="selTechnicianUser" name="machine_setup_2nd_in_charged" style="width: 100%;">
                                                                        <option disabled selected>-- Select Technician --</option>
                                                                        {{-- <option value="">-- N/A --</option> --}}
                                                                    </select>
                                                                    {{-- <input type="hidden" class="form-control machine_setup_data" id="frm_txt_machine_setup_2nd_in_charged_id" name="machine_setup_2nd_in_charged_id">
                                                                    <input type="text" class="form-control machine_setup_data text-center" id="frm_txt_machine_setup_2nd_in_charged" name="machine_setup_2nd_in_charged" placeholder="(Auto Generate)" readonly> --}}
                                                                    {{-- <div class="col-sm-12">
                                                                        <div class="form-group">
                                                                            <label>Name</label>
                                                                            <select class="form-control" id="selAddUserAccessUserId" name="user_id" style="width: 100%;">
                                                                            <option disabled selected>-- Select User --</option>
                                                                            <!-- <option disabled selected>-- Select User --</option> <option value="AL">Alabama</option> -->
                                                                            <!-- <option value="WY">Wyoming</option> -->
                                                                            </select>
                                                                        </div>
                                                                    </div> --}}
                                                                </td>
                                                                <td>
                                                                    <input type="datetime" class="form-control text-center align-middle machine_setup_data second_adjustment" id="frm_txt_machine_setup_2nd_datetime" name="machine_setup_2nd_datetime" placeholder="(Auto Generate)" readonly>
                                                                </td>
                                                                <td>
                                                                    {{-- <input type="text" class="form-control machine_setup_data" id="frm_txt_machine_setup_2nd_remarks" name="machine_setup_2nd_remarks"> --}}
                                                                    <select type="text" class="form-control select2bs5 machine_setup_data second_adjustment text-center" id="frm_txt_machine_setup_2nd_remarks" name="machine_setup_2nd_remarks" style="width: 100%;">
                                                                        <option value="" disabled selected>Please Select One</option>
                                                                        <option value="1">For Qualification</option>
                                                                        <option value="2">NG</option>
                                                                        <option value="3">Mold Down</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr class="align-middle">
                                                                <td width="30">
                                                                    <input style="margin-left:0; width:20px; height:20px;" class="form-check-input machine_setup_data third_adjustment" type="checkbox" id="frm_txt_machine_setup_3rd_adjustment" name="machine_setup_3rd_adjustment" value="1">
                                                                </td>
                                                                <td class="align-middle">
                                                                    <label class="form-check-label"> 3rd Adjustment</label>
                                                                </td>
                                                                <td>
                                                                    {{-- <input type="hidden" class="form-control machine_setup_data" id="frm_txt_machine_setup_3rd_in_charged_id" name="machine_setup_3rd_in_charged_id"> --}}
                                                                    {{-- <input type="text" class="form-control machine_setup_data text-center" id="frm_txt_machine_setup_3rd_in_charged" name="machine_setup_3rd_in_charged" placeholder="(Auto Generate)" readonly> --}}
                                                                    <select class="form-control select2bs5 machine_setup_data third_adjustment" id="selSupervisorEngrUser" name="machine_setup_3rd_in_charged" style="width: 100%;">
                                                                        <option disabled selected>-- Select Supervisor/Engr. --</option>
                                                                        {{-- <option value="">-- N/A --</option> --}}
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <input type="datetime" class="form-control text-center align-middle machine_setup_data third_adjustment" id="frm_txt_machine_setup_3rd_datetime" name="machine_setup_3rd_datetime" placeholder="(Auto Generate)" readonly>
                                                                </td>
                                                                <td>
                                                                    {{-- <input type="text" class="form-control machine_setup_data" id="frm_txt_machine_setup_3rd_remarks" name="machine_setup_3rd_remarks"> --}}
                                                                    <select type="text" class="form-control select2bs5 machine_setup_data third_adjustment text-center" id="frm_txt_machine_setup_3rd_remarks" name="machine_setup_3rd_remarks" style="width: 100%;">
                                                                        <option value="" disabled selected>Please Select One</option>
                                                                        <option value="1">For Qualification</option>
                                                                        <option value="2">NG</option>
                                                                        <option value="3">Mold Down</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                {{-- <div class="col-sm-10"> --}}
                                                    {{-- <div class="col-sm-12 border border-top-0 border-dark"> --}}
                                                        <div class="table-responsive col-sm-4">
                                                            <table id="tbl_machine_setup_category" class="table table-sm table-bordered" style="width: 100%;">
                                                                <tbody>
                                                                    <tr class="text-center align-middle">
                                                                        <td>
                                                                            <label class="form-check-label"> Category:</label>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input machine_setup_data first_adjustment" type="radio" value="1" name="machine_setup_category" id="frm_machine_setup_category_1">
                                                                                <label class="form-check-label"> HVM</label>
                                                                            </div>
                                                                            <div class="form-check form-check-inline">
                                                                                <input class="form-check-input machine_setup_data first_adjustment" type="radio" value="2" name="machine_setup_category" id="frm_machine_setup_category_2">
                                                                                <label class="form-check-label"> SM P.O</label>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    {{-- </div> --}}
                                                {{-- </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Part IV Division End -->
                            <!-- PART V Division Start-->
                            <div class="Part5">
                                <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                                    <h5 class="modal-title">V. Product Requirement Checking-Using Actual Product Sample<strong>(Responsible:Production / Process / Die Maintenance Engineering / QC)</strong></h5>
                                </button>
                                <div id="collapseFive" class="collapse" aria-labelledby="headingOne">
                                    <div class="row col-sm-12">
                                        <div class="col-sm-12 border border-dark" style="padding:0">
                                            <br>
                                            <div class="row col-sm-12 text-center" style="padding:0; margin:0">
                                                <div class="table-responsive col-sm-12">
                                                    <table id="tblProdReqLabels" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                        <tbody>
                                                            <tr class="align-middle">
                                                                <td rowspan="3" class="align-middle text-center">
                                                                    <label class="form-check-label"> Product Qualification Flow</label>
                                                                </td>
                                                                <td class="align-middle text-center" colspan="5">
                                                                    <label class="form-check-label"> Dieset/Product Condition</label>
                                                                </td>
                                                                <td class="align-middle text-center" colspan="6">
                                                                    <label class="form-check-label"> In-charge of this activity</label>
                                                                </td>
                                                                <td rowspan="3" class="align-middle" colspan="5">
                                                                    <label class="form-check-label">Important Note:</label>
                                                                    <br>
                                                                    <label class="form-check-label">Sequence of activity shall be performed and</label>
                                                                    <br>
                                                                    <label class="form-check-label">finished first prior to proceeding to the next step.</label>
                                                                </td>
                                                            </tr>
                                                            <tr class="align-middle">
                                                                <td class="align-middle text-center" colspan="5">
                                                                    <label class="form-check-label"> # I, II, III, IX & X</label>
                                                                </td>
                                                                <td colspan="6">
                                                                    <label class="form-check-label"> Machine Operator->Die Maintenance Engineering</label>
                                                                </td>
                                                            </tr>
                                                            <tr class="align-middle">
                                                                <td class="align-middle text-center" colspan="5">
                                                                    <label class="form-check-label"> # IV to XIII</label>
                                                                </td>
                                                                <td colspan="5">
                                                                    <label class="form-check-label">Machine Operator->Eng`g (Technician)->QC Inspector->Process Engr</label>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row col-sm-12 text-center" style="padding:0; margin:0">
                                                <div class="col-sm-4" style="padding:0">
                                                    <div class="row col-sm-12 d-flex justify-content-center" style="padding-left:15px; padding-right:0">
                                                        <div class="col-sm-12 text-center" style="padding:0">
                                                            <div class="table-responsive col-sm-12" style="padding:0">
                                                                <table id="tblProdReqProduction" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="padding:0" width="32%"><label class="form-check-label">Activity Sequence</label></td>
                                                                            <td style="padding:0" colspan="4"><strong><label class="form-check-label">1.0 Production</label></strong></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td rowspan="14" class="align-middle">
                                                                                <label class="form-check-label">STANDARD</label>
                                                                                <br>
                                                                                <label class="form-check-label">REFERENCES</label>
                                                                            </td>
                                                                        </tr>
                                                                        <!-- Sample References -->
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <label class="form-check-label d-flex justify-content-start"> 1. Sample References:</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr hidden>
                                                                            <td width="30">
                                                                                <input class="form-check-input d-flex prod_req_checking_data" index="1.0.0" type="checkbox" name="part_5_prod_id" id="frm_txt_part_5_prod_id">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">Production ID</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" index="1.0" type="checkbox" name="prod_eval_sample" id="frm_txt_prod_eval_sample" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">1.1 Evaluation Sample</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" index="1.1" type="checkbox" name="prod_japan_sample" id="frm_txt_prod_japan_sample" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">1.2 Japan Sample</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" index="1.2" type="checkbox" name="prod_last_prodn_sample" id="prod_last_prodn_sample" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">1.3 Last Prodn Sample</td>
                                                                        </tr>
                                                                        <!-- Document References -->
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <label class="form-check-label d-flex justify-content-start"> 2. Document References:</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" index="1.3" type="checkbox" name="prod_dieset_eval_report" id="frm_txt_action_1" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">2.2 Die set Evaluation Report</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4" height="39"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" index="1.4" type="checkbox" name="prod_cosmetic_defect" id="frm_txt_action_1" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">2.3 Cosmetic Defect(Point Panel)</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4" height="107"></td>
                                                                        </tr>
                                                                        {{-- <tr>
                                                                            <td colspan="4" height="34"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4" height="34"></td>
                                                                        </tr> --}}
                                                                        <!-- C/T Hole Checking -->
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <label class="form-check-label d-flex justify-content-start"> 3. C/T Hole Checking</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" index="1.5" type="checkbox" name="prod_pingauges" id="frm_txt_prod_pingauges" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">3.1 Pingauges</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" index="1.6" type="checkbox" name="prod_measurescope" id="frm_txt_prod_measurescope" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">3.2 Measurescope</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" index="1.7" type="checkbox" name="prod_na" id="frm_txt_prod_na" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">3.3 N/A</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td rowspan="2" class="align-middle">
                                                                                <label class="form-check-label"> ACTIVITY</label>
                                                                            </td>
                                                                            <td rowspan="2" colspan="2" class="align-middle">
                                                                                <label class="form-check-label"> Machine Operator Name/</label>
                                                                                <br>
                                                                                <label class="form-check-label"> Date/Time</label>
                                                                            </td>
                                                                            <td colspan="2" width="20%">
                                                                                <label class="form-check-label"> Result</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <label class="form-check-label"> OK</label>
                                                                            </td>
                                                                            <td>
                                                                                <label class="form-check-label"> NG</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-left align-middle" style="padding:0">
                                                                                <label class="form-check-label">1. Visual Insp.</label>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <select class="form-control prod_req_checking_data text-center" id="selProductionVisualUser" name="prod_visual_insp_name" style="width: 100%;">
                                                                                        <option disabled selected>-- Select Production --</option>
                                                                                    </select>
                                                                                    <input readonly type="datetime" placeholder="Auto Generate Date/Time" class="form-control prod_req_checking_data text-center" name="prod_visual_insp_datetime" id="frm_txt_prod_visual_insp_datetime" style="padding:1%" readonly>
                                                                                </div>
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="1.8" name="prod_visual_insp_result" id="frm_txt_prod_visual_insp_result_ok" value="1">
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="1.8" name="prod_visual_insp_result" id="frm_txt_prod_visual_insp_result_ng" value="0">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-left align-middle" style="padding:0">
                                                                                <label class="form-check-label">2. Dimension Insp.</label>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <select class="form-control prod_req_checking_data text-center" id="selProductionDimentionUser" name="prod_dimension_insp_name" style="width: 100%;" required>
                                                                                        <option disabled selected>-- Production --</option>
                                                                                        {{-- <option value="">-- N/A --</option> --}}
                                                                                    </select>
                                                                                    <input readonly type="datetime" placeholder="Auto Generate Date/Time" class="form-control prod_req_checking_data text-center" name="prod_dimension_insp_datetime" id="frm_txt_prod_dimension_insp_datetime" style="padding:1%" readonly>
                                                                                </div>
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="1.9" name="prod_dimension_insp_result" id="frm_txt_prod_dimension_insp_result_ok" value="1">
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="1.9" name="prod_dimension_insp_result" id="frm_txt_prod_dimension_insp_result_ng" value="0">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class="text-left align-middle">
                                                                                <label class="form-check-label">Remarks/Result</label>
                                                                            </td>
                                                                            <td colspan="4">
                                                                                <input type="text" class="form-control prod_req_checking_data" style="height:30px" name="prod_actual_checking_remarks" id="frm_txt_prod_actual_checking_remarks">
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm" style="padding:0">
                                                    <div class="row col-sm-12 d-flex justify-content-center" style="padding:0">
                                                        <div class="col-sm-12 text-center" style="padding:0">
                                                            <div class="table-responsive col-sm-12" style="padding:0">
                                                                <table id="tblProdReqEngr" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="padding:0" colspan="4"><strong><label class="form-check-label">2.0 ENGINEERING(TECHNICIAN)</label></strong></td>
                                                                        </tr>
                                                                        {{-- Sample References --}}
                                                                        <tr hidden>
                                                                            <td width="30">
                                                                                <input class="form-check-input d-flex prod_req_checking_data" index="2.0.0" type="checkbox" name="part_5_engr_id" id="frm_txt_part_5_engr_id">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">Engineering ID</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <label class="form-check-label d-flex justify-content-start"> 1. Sample References:</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="2.0" name="engr_tech_eval_sample" id="frm_txt_engr_tech_eval_sample" value="1">
                                                                            </td>
                                                                            <td width="30" colspan="3" class="text-left">1.1 Evaluation Sample</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="2.1" name="engr_tech_japan_sample" id="frm_txt_engr_tech_japan_sample" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">1.2 Japan Sample</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="2.2" name="engr_tech_last_prodn_sample" id="frm_txt_engr_tech_last_prodn_sample" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">1.3 Last Prodn Sample</td>
                                                                        </tr>
                                                                        {{-- Document References --}}
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <label class="form-check-label d-flex justify-content-start"> 2. Document References:</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="2.3" name="engr_tech_material_drawing" id="frm_txt_engr_tech_material_drawing" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">2.1 Material/Product Drawing</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td></td>
                                                                            <td>
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Drawing #: </span>
                                                                                    <input type="text" class="form-control prod_req_checking_data" name="engr_tech_material_drawing_no" id="frm_txt_engr_tech_material_drawing_no" readonly>
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="padding:3%">Rev #: </span>
                                                                                    <input type="text" class="form-control prod_req_checking_data" name="engr_tech_material_rev_no" id="frm_txt_engr_tech_material_rev_no" readonly>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="2.4" name="engr_tech_insp_guide" id="frm_txt_engr_tech_insp_guide" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">2.2 Inspection Guide</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td></td>
                                                                            <td>
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Drawing #: </span>
                                                                                    <input type="text" class="form-control prod_req_checking_data" name="engr_tech_insp_guide_drawing_no" id="frm_txt_engr_tech_insp_guide_drawing_no" readonly>
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="padding:3%">Rev #: </span>
                                                                                    <input type="text" class="form-control prod_req_checking_data" name="engr_tech_insp_guide_rev_no" id="frm_txt_engr_tech_insp_guide_rev_no" readonly>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="2.5" name="engr_tech_dieset_eval_report" id="frm_txt_engr_tech_dieset_eval_report" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">2.3 Die set Evaluation Report</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="2.6" name="engr_tech_cosmetic_defect" id="frm_txt_engr_tech_cosmetic_defect" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">2.4 Cosmetic Defect(Point Panel)</td>
                                                                        </tr>
                                                                        {{-- C/T Hole Checking--}}
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <label class="form-check-label d-flex justify-content-start"> 3. C/T Hole Checking</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="2.7" name="engr_tech_pingauges" id="frm_txt_engr_tech_pingauges" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">3.1 Pingauges</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="2.8" name="engr_tech_measurescope" id="frm_txt_engr_tech_measurescope" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">3.2 Measurescope</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="2.9" name="engr_tech_na" id="frm_txt_engr_tech_na" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">3.3 N/A</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td rowspan="2" colspan="2" class="align-middle">
                                                                                <label class="form-check-label"> Technician Name/</label>
                                                                                <br>
                                                                                <label class="form-check-label"> Date/Time</label>
                                                                            </td>
                                                                            <td colspan="2" width="30%">
                                                                                <label class="form-check-label"> Result</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <label class="form-check-label"> OK</label>
                                                                            </td>
                                                                            <td>
                                                                                <label class="form-check-label"> NG</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                {{-- <input type="text" class="form-control dieset_condition_data" name="check_point_7" id="frm_check_point_7"> --}}
                                                                                <div class="input-group input-group-sm">
                                                                                    <select class="form-control prod_req_checking_data text-center" id="selTechnicianVisualUser" name="engr_tech_visual_insp_name" style="width: 100%;" required>
                                                                                        <option disabled selected>-- Technician --</option>
                                                                                        {{-- <option value="">-- N/A --</option> --}}
                                                                                    </select>
                                                                                    {{-- <select class="form-control" type="text" name="request_type" id="frm_request_type">
                                                                                        <option value="" disabled="" selected="">Select Engr.</option>
                                                                                        <option value="1">Clark Chester Casuyon</option>
                                                                                        <option value="2">Miguel Legaspi</option>
                                                                                    </select> --}}
                                                                                    <input readonly type="datetime" placeholder="Auto Generate Date/Time" class="form-control prod_req_checking_data text-center" name="engr_tech_visual_insp_datetime" id="frm_txt_engr_tech_visual_insp_datetime" style="padding:1%" readonly>
                                                                                </div>
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="2.10" name="engr_tech_visual_insp_result" id="frm_txt_engr_tech_visual_insp_result_ok" value="1">
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="2.10" name="engr_tech_visual_insp_result" id="frm_txt_engr_tech_visual_insp_result_ng" value="0">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                {{-- <input type="text" class="form-control prod_req_checking_data" name="check_point_7" id="frm_check_point_7"> --}}
                                                                                <div class="input-group input-group-sm">
                                                                                    <select class="form-control prod_req_checking_data text-center" id="selTechnicianDimensionUser" name="engr_tech_dimension_insp_name" style="width: 100%;">
                                                                                        <option disabled selected>-- Technician --</option>
                                                                                        {{-- <option value="">-- N/A --</option> --}}
                                                                                    </select>
                                                                                    {{-- <select class="form-control" type="text" name="request_type" id="frm_request_type">
                                                                                        <option value="" disabled="" selected="">Select Engr.</option>
                                                                                        <option value="1">Clark Chester Casuyon</option>
                                                                                        <option value="2">Miguel Legaspi</option>
                                                                                    </select> --}}
                                                                                    <input readonly type="datetime" placeholder="Auto Generate Date/Time" class="form-control prod_req_checking_data text-center" name="engr_tech_dimension_insp_datetime" id="frm_txt_engr_tech_dimension_insp_datetime" style="padding:1%" readonly>
                                                                                </div>
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="2.11" name="engr_tech_dimension_insp_result" id="frm_txt_engr_tech_dimension_insp_result_ok" value="1">
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="2.11" name="engr_tech_dimension_insp_result" id="frm_txt_engr_tech_dimension_insp_result_ng" value="0">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <input type="text" style="height:30px" class="form-control prod_req_checking_data" name="engr_tech_actual_checking_remarks" id="frm_txt_engr_tech_actual_checking_remarks">
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm" style="padding:0">
                                                    <div class="row col-sm-12 d-flex justify-content-center" style="padding:0">
                                                        <div class="col-sm-12 text-center" style="padding:0">
                                                            <div class="table-responsive col-sm-12" style="padding:0">
                                                                <table id="tblProdReqQC" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="padding:0" colspan="4"><strong><label class="form-check-label">3.0 LINE QUALITY CONTROL</label></strong></td>
                                                                        </tr>
                                                                        {{-- Sample References --}}
                                                                        <tr hidden>
                                                                            <td width="30">
                                                                                <input class="form-check-input d-flex prod_req_checking_data" index="3.0.0" type="checkbox" name="part_5_lqc_id" id="frm_txt_part_5_lqc_id">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">LQC ID</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <label class="form-check-label d-flex justify-content-start"> 1. Sample References:</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="3.0" name="lqc_eval_sample" id="frm_txt_lqc_eval_sample" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">1.1 Evaluation Sample</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="3.1" name="lqc_japan_sample" id="frm_txt_lqc_japan_sample" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">1.2 Japan Sample</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="3.2" name="lqc_last_prodn_sample" id="frm_txt_lqc_last_prodn_sample" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">1.3 Last Prodn Sample</td>
                                                                        </tr>
                                                                        {{-- Document References --}}
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <label class="form-check-label d-flex justify-content-start"> 2. Document References:</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="3.3" name="lqc_material_drawing" id="frm_txt_lqc_material_drawing" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">2.1 Material/Product Drawing</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td></td>
                                                                            <td>
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Drawing #: </span>
                                                                                    <input type="text" class="form-control prod_req_checking_data" name="lqc_material_drawing_no" id="frm_txt_lqc_material_drawing_no">
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="padding:3%">Rev #: </span>
                                                                                    <input type="text" class="form-control prod_req_checking_data" name="lqc_material_rev_no" id="frm_txt_lqc_material_rev_no">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="3.4" name="lqc_insp_guide" id="frm_txt_lqc_insp_guide" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">2.2 Inspection Guide</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td></td>
                                                                            <td>
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Drawing #: </span>
                                                                                    <input type="text" class="form-control prod_req_checking_data" name="lqc_insp_guide_drawing_no" id="frm_txt_lqc_insp_guide_drawing_no">
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="padding:3%">Rev #: </span>
                                                                                    <input type="text" class="form-control prod_req_checking_data" name="lqc_insp_guide_rev_no" id="frm_txt_lqc_insp_guide_rev_no">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="3.5" name="lqc_dieset_eval_report" id="frm_txt_lqc_dieset_eval_report" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">2.3 Die set Evaluation Report</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="3.6" name="lqc_cosmetic_defect" id="frm_txt_lqc_cosmetic_defect" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">2.4 Cosmetic Defect(Point Panel)</td>
                                                                        </tr>
                                                                        {{-- C/T Hole Checking--}}
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <label class="form-check-label d-flex justify-content-start"> 3. C/T Hole Checking</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="3.7" name="lqc_pingauges" id="frm_txt_lqc_pingauges" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">3.1 Pingauges</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="3.8" name="lqc_measurescope" id="frm_txt_lqc_measurescope" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">3.2 Measurescope</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="3.9" name="lqc_na" id="frm_txt_lqc_na" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">3.3 N/A</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td rowspan="2" colspan="2" class="align-middle">
                                                                                <label class="form-check-label"> QC Inspector Name/</label>
                                                                                <br>
                                                                                <label class="form-check-label"> Date/Time</label>
                                                                            </td>
                                                                            <td colspan="2" width="30%">
                                                                                <label class="form-check-label"> Result</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <label class="form-check-label"> OK</label>
                                                                            </td>
                                                                            <td>
                                                                                <label class="form-check-label"> NG</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                {{-- <input type="text" class="form-control dieset_condition_data" index="2.9" name="check_point_7" id="frm_check_point_7"> --}}
                                                                                <div class="input-group input-group-sm">
                                                                                    <select class="form-control prod_req_checking_data text-center" id="selQcVisualUser" name="lqc_visual_insp_name" style="width: 100%;" required>
                                                                                        <option disabled selected>-- QC Inspector --</option>
                                                                                        {{-- <option value="">-- N/A --</option> --}}
                                                                                    </select>
                                                                                    {{-- <select class="form-control" type="text" name="request_type" id="frm_request_type">
                                                                                        <option value="" disabled="" selected="">Select Engr.</option>
                                                                                        <option value="1">Clark Chester Casuyon</option>
                                                                                        <option value="2">Miguel Legaspi</option>
                                                                                    </select> --}}
                                                                                    <input type="datetime" placeholder="Auto Generate Date/Time" class="form-control prod_req_checking_data text-center" name="lqc_visual_insp_datetime" id="frm_txt_lqc_visual_insp_datetime" style="padding:1%" readonly>
                                                                                </div>
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="3.10" name="lqc_visual_insp_result" id="frm_txt_lqc_visual_insp_result_ok" value="1">
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="3.10" name="lqc_visual_insp_result" id="frm_txt_lqc_visual_insp_result_ng" value="0">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                {{-- <input type="text" class="form-control prod_req_checking_data" name="check_point_7" id="frm_check_point_7"> --}}
                                                                                <div class="input-group input-group-sm">
                                                                                    <select class="form-control prod_req_checking_data text-center" id="selQcDimensionUser" name="lqc_dimension_insp_name" style="width: 100%;">
                                                                                        <option disabled selected>-- QC Inspector --</option>
                                                                                        {{-- <option value="">-- N/A --</option> --}}
                                                                                    </select>
                                                                                    {{-- <select class="form-control" type="text" name="request_type" id="frm_request_type">
                                                                                        <option value="" disabled="" selected="">Select Engr.</option>
                                                                                        <option value="1">Clark Chester Casuyon</option>
                                                                                        <option value="2">Miguel Legaspi</option>
                                                                                    </select> --}}
                                                                                    <input type="datetime" placeholder="Auto Generate Date/Time" class="form-control prod_req_checking_data text-center" name="lqc_dimension_insp_datetime" id="frm_txt_lqc_dimension_insp_datetime" style="padding:1%" readonly>
                                                                                </div>
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="3.11" name="lqc_dimension_insp_result" id="frm_txt_lqc_dimension_insp_result_ok" value="1">
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="3.11" name="lqc_dimension_insp_result" id="frm_txt_lqc_dimension_insp_result_ng" value="0">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <input type="text" style="height:30px" class="form-control prod_req_checking_data" index="2.9" name="lqc_actual_checking_remarks" id="frm_txt_lqc_actual_checking_remarks">
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm" style="padding:0">
                                                    <div class="row col-sm-12 d-flex justify-content-center" style="padding:0">
                                                        <div class="col-sm-12 text-center" style="padding:0">
                                                            <div class="table-responsive col-sm-12" style="padding:0">
                                                                <table id="tblProdReqProcessEngr" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="padding:0" colspan="4"><strong><label class="form-check-label">4.0 PROCESS ENGINEERING</label></strong></td>
                                                                        </tr>
                                                                        {{-- Sample References --}}
                                                                        <tr hidden>
                                                                            <td width="30">
                                                                                <input class="form-check-input d-flex prod_req_checking_data" index="4.0.0" type="checkbox" name="part_5_pro_engr_id" id="frm_txt_part_5_pro_engr_id">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">Process Engineering ID</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <label class="form-check-label d-flex justify-content-start"> 1. Sample References:</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="4.0" name="process_engr_eval_sample" id="frm_txt_process_engr_eval_sample" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">1.1 Evaluation Sample</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="4.1" name="process_engr_japan_sample" id="frm_txt_process_engr_japan_sample" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">1.2 Japan Sample</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="4.2" name="process_engr_last_prodn_sample" id="frm_txt_process_engr_last_prodn_sample" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">1.3 Last Prodn Sample</td>
                                                                        </tr>
                                                                        {{-- Document References --}}
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <label class="form-check-label d-flex justify-content-start"> 2. Document References:</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="4.3" name="process_engr_material_drawing" id="frm_txt_process_engr_material_drawing" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">2.1 Material/Product Drawing</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td></td>
                                                                            <td>
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Drawing #: </span>
                                                                                    <input type="text" class="form-control prod_req_checking_data" name="process_engr_material_drawing_no" id="frm_txt_process_engr_material_drawing_no">
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="padding:3%">Rev #: </span>
                                                                                    <input type="text" class="form-control prod_req_checking_data" name="process_engr_material_rev_no" id="frm_txt_process_engr_material_rev_no">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="4.4" name="process_engr_insp_guide" id="frm_txt_process_engr_insp_guide" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">2.2 Inspection Guide</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td></td>
                                                                            <td>
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Drawing #: </span>
                                                                                    <input type="text" class="form-control prod_req_checking_data" name="process_engr_insp_guide_drawing_no" id="frm_txt_process_engr_insp_guide_drawing_no">
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="padding:3%">Rev #: </span>
                                                                                    <input type="text" class="form-control prod_req_checking_data" name="process_engr_insp_guide_rev_no" id="frm_txt_process_engr_insp_guide_rev_no">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="4.5" name="process_engr_dieset_eval_report" id="frm_txt_process_engr_dieset_eval_report" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">2.3 Die set Evaluation Report</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="4.6" name="process_engr_cosmetic_defect" id="frm_txt_process_engr_cosmetic_defect" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">2.4 Cosmetic Defect(Point Panel)</td>
                                                                        </tr>
                                                                        {{-- C/T Hole Checking--}}
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <label class="form-check-label d-flex justify-content-start"> 3. C/T Hole Checking</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="4.7" name="process_engr_pingauges" id="frm_txt_process_engr_pingauges" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">3.1 Pingauges</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="4.8" name="process_engr_measurescope" id="frm_txt_process_engr_measurescope" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">3.2 Measurescope</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex prod_req_checking_data" type="checkbox" index="4.9" name="process_engr_na" id="frm_txt_process_engr_na" value="1">
                                                                            </td>
                                                                            <td colspan="3" class="text-left">3.3 N/A</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td rowspan="2" colspan="2" class="align-middle">
                                                                                <label class="form-check-label"> Process Engr. Name/</label>
                                                                                <br>
                                                                                <label class="form-check-label"> Date/Time</label>
                                                                            </td>
                                                                            <td colspan="2" width="30%">
                                                                                <label class="form-check-label"> Result</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <label class="form-check-label"> OK</label>
                                                                            </td>
                                                                            <td>
                                                                                <label class="form-check-label"> NG</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            {{-- OGAB --}}
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <select class="form-control select2bs5 prod_req_checking_data text-center" id="selEngrVisualUser" name="process_engr_visual_insp_name" style="width: 100%;">
                                                                                        <option disabled selected>-- Process Engr --</option>
                                                                                        {{-- <option value="">-- N/A --</option> --}}
                                                                                    </select>
                                                                                    {{-- <select class="form-control" type="text" name="request_type" id="frm_request_type">
                                                                                        <option value="" disabled="" selected="">Select Engr.</option>
                                                                                        <option value="1">Clark Chester Casuyon</option>
                                                                                        <option value="2">Miguel Legaspi</option>
                                                                                    </select> --}}
                                                                                    <input type="datetime" placeholder="Auto Generate Date/Time" class="form-control prod_req_checking_data text-center" name="process_engr_visual_insp_datetime" id="frm_txt_process_engr_visual_insp_datetime" style="padding:1%" readonly>
                                                                                </div>
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="4.10" name="process_engr_visual_insp_result" id="frm_txt_process_engr_visual_insp_resul_ok" value="1">
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="4.10" name="process_engr_visual_insp_result" id="frm_txt_process_engr_visual_insp_result_ng" value="0">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2" class="text-center">
                                                                                {{-- <input type="text" class="form-control prod_req_checking_data" name="check_point_7" id="frm_check_point_7"> --}}
                                                                                <div class="input-group input-group-sm">
                                                                                    <select class="form-control select2bs5 prod_req_checking_data text-center" id="selEngrDimensionUser" name="process_engr_dimension_insp_name" style="width: 100%;">
                                                                                        <option disabled selected>-- Process Engr --</option>
                                                                                        {{-- <option value="">-- N/A --</option> --}}
                                                                                    </select>
                                                                                    {{-- <select class="form-control" type="text" name="request_type" id="frm_request_type">
                                                                                        <option value="" disabled="" selected="">Select Engr.</option>
                                                                                        <option value="1">Clark Chester Casuyon</option>
                                                                                        <option value="2">Miguel Legaspi</option>
                                                                                    </select> --}}
                                                                                    <input type="datetime" placeholder="Auto Generate Date/Time" class="form-control prod_req_checking_data text-center" name="process_engr_dimension_insp_datetime" id="frm_txt_process_engr_dimension_insp_datetime" style="padding:1%" readonly>
                                                                                </div>
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="4.11" name="process_engr_dimension_insp_result" id="frm_txt_process_engr_dimension_insp_result_ok" value="1">
                                                                            </td>
                                                                            <td class="pl-4 pt-3">
                                                                                <input style="width:20px; height:20px;" class="form-check-input prod_req_checking_data" type="radio" index="4.11" name="process_engr_dimension_insp_result" id="frm_txt_process_engr_dimension_insp_result_ng" value="0">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <input type="text" style="height:30px" class="form-control prod_req_checking_data" name="process_engr_actual_checking_remarks" id="frm_txt_process_engr_actual_checking_remarks">
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            {{-- MACHINE SET-UP SAMPLES --}}
                                            <div class="col-sm-5" style="padding:0; margin-left:30%">
                                                <div class="row col-sm-12 d-flex justify-content-center" style="padding-left:15px; padding-right:0">
                                                    <div class="col-sm-12 text-center" style="padding:0">
                                                        <div class="table-responsive col-sm-12" style="padding:0">
                                                            <table id="tbl_machine_samples" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="padding:0" colspan="4"><strong><label class="form-check-label">Machine Setup Samples</label></strong></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <label class="form-check-label d-flex justify-content-end"> Number of Shots:</label>
                                                                        </td>
                                                                        <td width="30">
                                                                            <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex machine_setup_sample_data" type="checkbox" name="number_of_shots" id="frm_txt_number_of_shots" value="1">
                                                                        </td>
                                                                        <td>
                                                                            <label class="form-check-label d-flex justify-content-start">25 Shots</label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <label class="form-check-label d-flex justify-content-end">Actual Quantity:</label>
                                                                        </td>
                                                                        <td colspan="2">
                                                                            <div class="input-group input-group-sm">
                                                                                <input type="text" class="form-control machine_setup_sample_data" name="actual_quantity" id="frm_txt_actual_quantity">
                                                                                <span class="input-group-text" style="50%">PCS</span>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <label class="form-check-label d-flex justify-content-end">Judgement:</label>
                                                                        </td>
                                                                        <td width="30">
                                                                            <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex machine_setup_sample_data" type="checkbox" name="judgement" id="frm_txt_judgement" value="1">
                                                                        </td>
                                                                        <td>
                                                                            <label class="form-check-label d-flex justify-content-start">For Disposal</label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="4" class="text-center"><strong>MACHINE CLEANING ACTIVITY</strong></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="4" class="text-left">Checkpoints during cleaning:</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td width="30">
                                                                            <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex machine_setup_sample_data" type="checkbox" name="machine_parts" id="frm_txt_machine_parts" value="1">
                                                                        </td>
                                                                        <td colspan="3">
                                                                            <label class="form-check-label d-flex justify-content-start"> Machine Parts(inside)</label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td width="30">
                                                                            <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex machine_setup_sample_data" type="checkbox" name="output_path" id="frm_txt_output_path" value="1">
                                                                        </td>
                                                                        <td colspan="3">
                                                                            <label class="form-check-label d-flex justify-content-start"> Output Path</label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td width="30">
                                                                            <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex machine_setup_sample_data" type="checkbox" name="product_catcher" id="frm_txt_product_catcher" value="1">
                                                                        </td>
                                                                        <td colspan="3">
                                                                            <label class="form-check-label d-flex justify-content-start"> Product Catcher</label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <div class="input-group input-group-sm">
                                                                                <span class="input-group-text" style="width:30%">PIC</span>
                                                                                {{-- <input type="text" class="form-control machine_setup_sample_data" name="pic" id="selMachineSetupSamplesPIC"> --}}
                                                                                <select class="form-control machine_setup_sample_data text-center" id="selMachineSetupSamplesPIC" name="pic"></select>
                                                                            </div>
                                                                            {{-- <select class="form-control machine_setup_sample_data text-center" id="selMachineSetupSamplesPIC" name="prod_visual_insp_name" style="width: 100%;">
                                                                                <option disabled selected>-- Production --</option>
                                                                            </select> --}}
                                                                        </td>
                                                                        <td colspan="2">
                                                                            <div class="input-group input-group-sm">
                                                                                <span class="input-group-text" style="width:30%">DATE</span>
                                                                                <input type="datetime" class="form-control machine_setup_sample_data" name="pic_date" id="frm_txt_pic_date" placeholder="Auto Generate Date/Time" readonly>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="4">
                                                                            <label class="form-check-label d-flex justify-content-start"> Checked By:</label>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <div class="input-group input-group-sm">
                                                                                <span class="input-group-text" style="width:30%">QC</span>
                                                                                {{-- <input type="text" class="form-control machine_setup_sample_data" name="checked_by_qc" id="selMachineSetupSamplesQc"> --}}
                                                                                <select class="form-control machine_setup_sample_data text-center" id="selMachineSetupSamplesQc" name="checked_by_qc"></select>
                                                                            </div>
                                                                        </td>
                                                                        <td colspan="2">
                                                                            <div class="input-group input-group-sm">
                                                                                <span class="input-group-text" style="width:30%">DATE</span>
                                                                                <input type="datetime" class="form-control machine_setup_sample_data" name="qc_date" id="frm_txt_qc_date" placeholder="Auto Generate Date/Time" readonly>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <div class="input-group input-group-sm">
                                                                                <span class="input-group-text" style="width:30%">ENG'G</span>
                                                                                {{-- <input type="text" class="form-control machine_setup_sample_data" name="checked_by_engr" id="selMachineSetupSamplesEngr"> --}}
                                                                                <select class="form-control machine_setup_sample_data text-center" id="selMachineSetupSamplesEngr" name="checked_by_engr"></select>
                                                                            </div>
                                                                        </td>
                                                                        <td colspan="2">
                                                                            <div class="input-group input-group-sm">
                                                                                <span class="input-group-text" style="width:30%">DATE</span>
                                                                                <input type="datetime" class="form-control machine_setup_sample_data" name="engr_date" id="frm_txt_engr_date" placeholder="Auto Generate Date/Time" readonly>
                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- MACHINE SET-UP SAMPLES END --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Part V Division End -->
                            <!-- PART VI Division Start-->
                            <div class="Part6">
                                {{-- <h5 class="modal-title">III. Dieset Condition Checking <strong>(Responsible:Die Maintenance)</strong></h5> --}}
                                <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                                    <h5 class="modal-title">VI. Machine Parameter Checking <strong>(Responsible: Process Engineering / QC)</strong></h5>
                                </button>
                                <div id="collapseSix" class="collapse" aria-labelledby="headingOne">
                                    <div class="row col-sm-12">
                                        <div class="col-sm-12 border border-dark d-flex justify-content-center">
                                            <div class="row mt-3">
                                                <div class="table-responsive col-sm-6">
                                                    <table id="tbl_machine_param_chckng_reference" class="table table-sm table-bordered" style="width: 100%;">
                                                        <tbody>
                                                            <tr class="text-center align-middle">
                                                                <td>
                                                                    <label class="form-check-label"> Reference:</label>
                                                                </td>
                                                                <td>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input machine_param_checking_data engr_input" type="radio" value="1" name="machine_param_chckng_ref" id="frm_machine_param_chckng_ref_1">
                                                                        <label class="form-check-label"> Standard Machine Data</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input machine_param_checking_data engr_input" type="radio" value="2" name="machine_param_chckng_ref" id="frm_machine_param_chckng_ref_2">
                                                                        <label class="form-check-label"> Approved Evaluation Data</label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div class="table-responsive col-sm-12">
                                                    <table id="tbl_machine_param_chckng" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-center align-middle" rowspan="2" width="10%">Parameter</td>
                                                                <td class="text-center align-middle" rowspan="2" width="8%">STD. Specs</td>
                                                                <td class="text-center align-middle" rowspan="2" width="8%">Actual</td>
                                                                <td class="text-center align-middle" colspan="2" width="5%">Result</td>
                                                                <td class="text-center align-middle" rowspan="2" >Process Eng`g Name <br>/ Date</td>
                                                                <td class="text-center align-middle" rowspan="2" width="8%">Actual</td>
                                                                <td class="text-center align-middle" colspan="2" width="5%">Result</td>
                                                                <td class="text-center align-middle" rowspan="2" >QC Name <br>/ Date</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center align-middle">
                                                                    <label class="form-check-label"> OK</label>
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <label class="form-check-label"> NG</label>
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <label class="form-check-label"> OK</label>
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <label class="form-check-label"> NG</label>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center align-middle">
                                                                    <label class="form-check-label"> Pressure</label>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <input type="text" class="form-control machine_param_checking_data engr_input" name="pressure_std_specs" id="frm_txt_pressure_std_specs">
                                                                </td>
                                                                <td class="align-middle">
                                                                    <input type="text" class="form-control machine_param_checking_data engr_input" name="pressure_engr_actual" id="frm_txt_pressure_engr_actual">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data engr_input" index="6.0" type="radio" id="pressure_engr_result_1" name="pressure_engr_result" value="0">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data engr_input" index="6.0" type="radio" id="pressure_engr_result_2" name="pressure_engr_result" value="1">
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <select class="form-control select2bs5 text-center machine_param_checking_data engr_input" id="selPressureEngrUser" name="pressure_engr_name"></select>
                                                                        <input type="date" placeholder="Date" class="form-control form-control-sm text-center machine_param_checking_data engr_input" name="pressure_engr_date" id="frm_txt_pressure_engr_date" style="padding:1%">
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <input type="text" class="form-control machine_param_checking_data qc_input" name="pressure_qc_actual" id="frm_txt_pressure_qc_actual">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data qc_input" index="6.1" type="radio" id="pressure_qc_result_1" name="pressure_qc_result" value="0">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data qc_input" index="6.1" type="radio" id="pressure_qc_result_2" name="pressure_qc_result" value="1">
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <select class="form-control select2bs5 text-center machine_param_checking_data qc_input" id="selPressureQCUser" name="pressure_qc_name"></select>
                                                                        <input type="date" placeholder="Date" class="form-control form-control-sm text-center machine_param_checking_data qc_input" name="pressure_qc_date" id="frm_txt_pressure_qc_date" style="padding:1%">
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="text-center align-middle">
                                                                    <label class="form-check-label"> Temp. Nozzle</label>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <input type="text" class="form-control machine_param_checking_data engr_input" name="temp_nozzle_std_specs" id="frm_txt_temp_nozzle_std_specs">
                                                                </td>
                                                                <td class="align-middle">
                                                                    <input type="text" class="form-control machine_param_checking_data engr_input" name="temp_nozzle_engr_actual" id="frm_txt_temp_nozzle_engr_actual">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data engr_input" index="6.2" type="radio" id="temp_nozzle_engr_result_1" name="temp_nozzle_engr_result" value="0">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data engr_input" index="6.2" type="radio" id="temp_nozzle_engr_result_2" name="temp_nozzle_engr_result" value="1">
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <select class="form-control select2bs5 text-center machine_param_checking_data engr_input" id="selTempNozzleEngrUser" name="temp_nozzle_engr_name"></select>
                                                                        <input type="date" placeholder="Date" class="form-control form-control-sm text-center machine_param_checking_data engr_input" name="temp_nozzle_engr_date" id="frm_txt_temp_nozzle_engr_date" style="padding:1%">
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <input type="text" class="form-control machine_param_checking_data qc_input" name="temp_nozzle_qc_actual" id="frm_txt_temp_nozzle_qc_actual">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data qc_input" index="6.3" type="radio" id="temp_nozzle_qc_result_1" name="temp_nozzle_qc_result" value="0">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data qc_input" index="6.3" type="radio" id="temp_nozzle_qc_result_2" name="temp_nozzle_qc_result" value="1">
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <select class="form-control select2bs5 text-center machine_param_checking_data qc_input" id="selTempNozzleQCUser" name="temp_nozzle_qc_name"></select>
                                                                        <input type="date" placeholder="Date" class="form-control form-control-sm text-center machine_param_checking_data qc_input" name="temp_nozzle_qc_date" id="frm_txt_temp_nozzle_qc_date" style="padding:1%">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center align-middle">
                                                                    <label class="form-check-label"> Temp. Mold</label>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <input type="text" class="form-control machine_param_checking_data engr_input" name="temp_mold_std_specs" id="frm_txt_temp_mold_std_specs">
                                                                </td>
                                                                <td class="align-middle">
                                                                    <input type="text" class="form-control machine_param_checking_data engr_input" name="temp_mold_engr_actual" id="frm_txt_temp_mold_engr_actual">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data engr_input" index="6.4" type="radio" id="temp_mold_engr_result_1" name="temp_mold_engr_result" value="0">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data engr_input" index="6.4" type="radio" id="temp_mold_engr_result_2" name="temp_mold_engr_result" value="1">
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <select class="form-control select2bs5 text-center machine_param_checking_data engr_input" id="selTempMoldEngrUser" name="temp_mold_engr_name"></select>
                                                                        <input type="date" placeholder="Date" class="form-control form-control-sm text-center machine_param_checking_data engr_input" name="temp_mold_engr_date" id="frm_txt_temp_mold_engr_date" style="padding:1%">
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <input type="text" class="form-control machine_param_checking_data qc_input" name="temp_mold_qc_actual" id="frm_txt_temp_mold_qc_actual">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data qc_input" index="6.5" type="radio" id="temp_mold_qc_result_1" name="temp_mold_qc_result" value="0">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data qc_input" index="6.5" type="radio" id="temp_mold_qc_result_2" name="temp_mold_qc_result" value="1">
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <select class="form-control select2bs5 text-center machine_param_checking_data qc_input" id="selTempMoldQCUser" name="temp_mold_qc_name"></select>
                                                                        <input type="date" placeholder="Date" class="form-control form-control-sm text-center machine_param_checking_data qc_input" name="temp_mold_qc_date" id="frm_txt_temp_mold_qc_date" style="padding:1%">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-center align-middle">
                                                                    <label class="form-check-label"> Cooling Time</label>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <input type="text" class="form-control machine_param_checking_data engr_input" name="ctime_std_specs" id="frm_txt_ctime_std_specs">
                                                                </td>
                                                                <td class="align-middle">
                                                                    <input type="text" class="form-control machine_param_checking_data engr_input" name="ctime_engr_actual" id="frm_txt_ctime_engr_actual">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data engr_input" index="6.6" type="radio" id="ctime_engr_result_1" name="ctime_engr_result" value="0">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data engr_input" index="6.6" type="radio" id="ctime_engr_result_2" name="ctime_engr_result" value="1">
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <select class="form-control select2bs5 text-center machine_param_checking_data engr_input" id="selCtimeEngrUser" name="ctime_engr_name"></select>
                                                                        <input type="date" placeholder="Date" class="form-control form-control-sm text-center machine_param_checking_data engr_input" name="ctime_engr_date" id="frm_txt_ctime_engr_date" style="padding:1%">
                                                                    </div>
                                                                </td>
                                                                <td class="align-middle">
                                                                    <input type="text" class="form-control machine_param_checking_data qc_input" name="ctime_qc_actual" id="frm_txt_ctime_qc_actual">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data qc_input" index="6.7" type="radio" id="ctime_qc_result_1" name="ctime_qc_result" value="0">
                                                                </td>
                                                                <td width="30">
                                                                    <input style="margin-left:0px; width:25px; height:25px;" class="form-check-input machine_param_checking_data qc_input" index="6.7" type="radio" id="ctime_qc_result_2" name="ctime_qc_result" value="1">
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <select class="form-control select2bs5 text-center machine_param_checking_data qc_input" id="selCtimeQCUser" name="ctime_qc_name"></select>
                                                                        <input type="date" placeholder="Date" class="form-control form-control-sm text-center machine_param_checking_data qc_input" name="ctime_qc_date" id="frm_txt_ctime_qc_date" style="padding:1%">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Part VI Division End -->
                            <!-- PART VII Division Start-->
                            <div class="Part7">
                                {{-- <h5 class="modal-title">III. Dieset Condition Checking <strong>(Responsible:Die Maintenance)</strong></h5> --}}
                                <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
                                    <h5 class="modal-title">VII. Specifications <strong>(Responsible: Process Engineering / QC)</strong></h5>
                                </button>
                                <div id="collapseSeven" class="collapse" aria-labelledby="headingOne">
                                    <div class="row col-sm-12">
                                        <div class="col-sm-12 border border-dark d-flex justify-content-center">
                                            <div class="row mt-3">
                                                <div class="col-sm-6" style="padding:0">
                                                    <div class="row col-sm-12 d-flex justify-content-center" style="padding-left:15px; padding-right:0">
                                                        <div class="col-sm-12 text-center" style="padding:0">
                                                            <div class="table-responsive col-sm-12" style="padding:0">
                                                                <table id="tblSpecificationsNGResult" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="padding:0" colspan="4"><label class="form-check-label d-flex justify-content-start">>> if RESULT is NG:</label></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="padding:0" colspan="4"><strong><label class="form-check-label">4.0 Responsible: Engineering (Process Engineering / QC)</label></strong></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <label class="form-check-label text-center"><strong>Initial Activity</strong></label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex specification_data" index="7.0" type="checkbox" name="ng_issued_ptnr" id="frm_txt_ng_issued_ptnr" value="1">
                                                                            </td>
                                                                            <td colspan="3">
                                                                                <label class="form-check-label d-flex justify-content-start">Issued PTNR</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex specification_data" index="7.1" type="checkbox" name="ng_coordinate_to_ts_cn_assembly" id="frm_txt_ng_coordinate_to_ts_cn_assembly" value="1">
                                                                            </td>
                                                                            <td colspan="3">
                                                                                <label class="form-check-label d-flex justify-content-start" title="AER = Assembly Evaluation Request">Coordinate to TS/CN-Assembly thru request for Assembly Evaluation Request (AER)</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex specification_data" index="7.2" type="checkbox" name="ng_discussion_w_tech_adviser" id="frm_txt_ng_discussion_w_tech_adviser" value="1">
                                                                            </td>
                                                                            <td colspan="3">
                                                                                <label class="form-check-label d-flex justify-content-start">Discussion with Technical Adviser</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <label class="form-check-label text-center"><strong>Final Judgement</strong></label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex specification_data" index="7.3" type="checkbox" name="ng_go_production" id="frm_txt_ng_go_production" value="1">
                                                                            </td>
                                                                            <td colspan="3">
                                                                                <label class="form-check-label d-flex justify-content-start">GO on Production</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex specification_data" index="7.4" type="checkbox" name="ng_stop_production" id="frm_txt_ng_stop_production" value="1">
                                                                            </td>
                                                                            <td colspan="3">
                                                                                <label class="form-check-label d-flex justify-content-start">STOP Production</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="50%">Judged By:</span>
                                                                                    <select class="form-control select2bs5 specification_data" id="selNgJudgedBy" name="ng_judged_by"></select>

                                                                                    {{-- <input hidden class="form-control specification_data" name="ng_judged_by_id" id="frm_txt_ng_judged_by_id">
                                                                                    <input type="text" class="form-control specification_data" name="ng_judged_by" id="frm_txt_ng_judged_by"> --}}
                                                                                    {{-- <span class="input-group-text" style="50%">(Name/Sign)</span> --}}
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="50%">Date / Time</span>
                                                                                    <input type="date" class="form-control specification_data" name="ng_datetime" id="frm_txt_ng_datetime">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6" style="padding:0">
                                                    <div class="row col-sm-12 d-flex justify-content-center" style="padding-left:15px; padding-right:0">
                                                        <div class="col-sm-12 text-center" style="padding:0">
                                                            <div class="table-responsive col-sm-12" style="padding:0">
                                                                <table id="tblSpecificationsOKResult" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="padding:0" colspan="2"><label class="form-check-label d-flex justify-content-start">>> if RESULT is OK:</label></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td style="padding:0" colspan="2"><strong><label class="form-check-label">5.0 Responsible: QC</label></strong></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <label class="form-check-label text-center"><strong>Confirmation</strong></label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex specification_data" index="7.5" type="checkbox" name="ok_go_production" id="frm_txt_ok_go_production" value="1">
                                                                            </td>
                                                                            <td>
                                                                                <label class="form-check-label d-flex justify-content-start">GO on Production</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm" style="width:50%">
                                                                                    <span class="input-group-text" style="width:30%">Verified By:</span>
                                                                                    <select class="form-control select2bs5 specification_data" id="selOkVerifiedBy" name="ok_verified_by"></select>
                                                                                    {{-- <input hidden class="form-control specification_data" name="ok_verified_by_id" id="frm_txt_ok_verified_by_id">
                                                                                    <input type="text" class="form-control specification_data" name="ok_verified_by" id="frm_txt_ok_verified_by"> --}}
                                                                                    {{-- <span class="input-group-text" style="50%">(Name/Sign)</span> --}}
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm" style="width:50%">
                                                                                    <span class="input-group-text" style="width:30%">Date / Time</span>
                                                                                    <input type="date" class="form-control specification_data" name="ok_datetime" id="frm_txt_ok_datetime">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12" style="padding:0">
                                                    <div class="row col-sm-12 d-flex justify-content-center" style="padding-left:15px; padding-right:0">
                                                        <div class="col-sm-12 text-center" style="padding:0">
                                                            <div class="table-responsive col-sm-12" style="padding:0">
                                                                <table id="tbl_part_7_notes" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <label class="form-check-label d-flex">
                                                                                    Notes: &nbsp;&nbsp;
                                                                                    1. On the references slot, concerned shall indicate check on each slot for the references used during product qualification activity.
                                                                                </label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <label class="form-check-label d-flex">
                                                                                    2. On the result of inspection activity, concerned Machine Operators / Process Eng`g / LQC shall indicate check whether it is OK or NG.
                                                                                </label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <label class="form-check-label d-flex">
                                                                                    3. NEED Engineering Head conformance if the result of machine parameters checking is NG.
                                                                                </label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <label class="form-check-label d-flex">
                                                                                    4. Qualification Flow: Production -> Process Engineering -> LQC -> Process Engineering -> LQC
                                                                                </label>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- ENG`G HEAD APPROVAL --}}
                                                <div class="col-sm-4" style="padding:0">
                                                    <div class="row col-sm-12 d-flex justify-content-center" style="padding-left:15px; padding-right:0">
                                                        <div class="col-sm-12 text-center" style="padding:0">
                                                            <div class="table-responsive col-sm-12" style="padding:0">
                                                                <table id="tblEngrHeadConformance" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="padding:0" colspan="4"><strong><label class="form-check-label">Engineering Head Conformance</label></strong></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex specification_data" index="7.6" type="checkbox" name="engr_head_go_production" id="frm_txt_engr_head_go_production" value="1">
                                                                            </td>
                                                                            <td>
                                                                                <label class="form-check-label d-flex justify-content-start"> GO Production</label>
                                                                            </td>

                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex specification_data" index="7.7" type="checkbox" name="engr_head_stop_production" id="frm_txt_engr_head_stop_production" value="1">
                                                                            </td>
                                                                            <td>
                                                                                <label class="form-check-label d-flex justify-content-start"> STOP Production</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="4">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="width:20%">Remarks:</span>
                                                                                    <input type="text" class="form-control specification_data" name="remarks" id="frm_txt_remarks">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="width:40%">Signed By:</span>
                                                                                    <select class="form-control select2bs5 specification_data" id="selSignedBy" name="signed_by"></select>
                                                                                    {{-- <input hidden class="form-control specification_data" name="signed_id" id="frm_txt_signed_id">
                                                                                    <input type="text" class="form-control specification_data" name="signed" id="frm_txt_signed"> --}}
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="width:30%">Date:</span>
                                                                                    <input type="date" class="form-control specification_data" name="engr_head_datetime" id="frm_txt_engr_head_datetime">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- ENG`G HEAD APPROVAL END --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Part VII Division End -->
                            <!-- PART VIII Division Start-->
                            <div class="Part8">
                                <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="true" aria-controls="collapseEight">
                                    <h5 class="modal-title">VIII. Completion Activity <strong>(Responsible: Production / Machine Operator)</strong></h5>
                                </button>
                                <div id="collapseEight" class="collapse" aria-labelledby="headingOne">
                                    <div class="row col-sm-12">
                                        <div class="col-sm-12 border border-dark d-flex justify-content-center">
                                            <div class="row">
                                                <div class="col-sm-12 mt-3" style="padding:0">
                                                    <div class="row col-sm-12 d-flex justify-content-center" style="padding-left:15px; padding-right:0">
                                                        <div class="col-sm-12" style="padding:0">
                                                            <div class="table-responsive col-sm-12" style="padding:0">
                                                                <table id="tblCompletionActivityA" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td colspan="3">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="50%">Trouble Content:</span>
                                                                                    <input type="text" class="form-control completion_activity" name="trouble_content" id="frm_txt_trouble_content">
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="3">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="50%">Illustration:</span>
                                                                                    <input type="text" class="form-control completion_activity" name="illustration" id="frm_txt_illustration">
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="50%">Remarks:</span>
                                                                                    <input type="text" class="form-control completion_activity" name="completion_remarks" id="frm_txt_completion_remarks">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-8 text-center" style="padding:0">
                                                            <div class="table-responsive col-sm-12" style="padding:0">
                                                                <table id="tblCompletionActivityB" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:10px; width:20px; height:20px;" class="form-check-input d-flex completion_activity" index="8.0" type="checkbox" name="finished_po" id="frm_txt_finished_po" value="1">
                                                                            </td>
                                                                            <td width="110">
                                                                                <label class="form-check-label d-flex justify-content-start">Finished P.O</label>
                                                                            </td>
                                                                            <td width="30">
                                                                                <input style="margin-left:10px; width:20px; height:20px;" class="form-check-input d-flex completion_activity" index="8.1" type="checkbox" name="sample_attachment" id="frm_txt_sample_attachment" value="1">
                                                                            </td>
                                                                            <td width="130">
                                                                                <label class="form-check-label d-flex justify-content-start">Sample Attachment</label>
                                                                            </td>
                                                                            <td width="150" rowspan="2">
                                                                                <label class="form-check-label d-flex justify-content-center pt-3" >Stop Production</label>
                                                                            </td>
                                                                            <td width="150" rowspan="2">
                                                                                <label class="form-check-label d-flex justify-content-start pt-1">Prepared By:</label>
                                                                                {{-- <div class="input-group input-group-sm"> --}}
                                                                                    {{-- <span class="input-group-text" style="50%">Prepared By:</span> --}}
                                                                                    {{-- <input type="text" class="form-control form-control-sm completion_activity" name="prepared_by" id="SelPreparedBy"> --}}
                                                                                {{-- </div> --}}
                                                                                    <select class="form-control select2bs5 form-control-sm completion_activity" id="SelPreparedBy" name="prepared_by"></select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:10px; width:20px; height:20px;" class="form-check-input d-flex completion_activity" index="8.2" type="checkbox" name="with_po_received" id="frm_txt_with_po_received" value="1">
                                                                            </td>
                                                                            <td>
                                                                                <label class="form-check-label d-flex justify-content-start">With P.O received</label>
                                                                            </td>
                                                                            <td width="30">
                                                                                <input style="margin-left:10px; width:20px; height:20px;" class="form-check-input d-flex completion_activity" index="8.3" type="checkbox" name="illustration_attachment" id="frm_txt_illustration_attachment" value="1">
                                                                            </td>
                                                                            <td>
                                                                                <label class="form-check-label d-flex justify-content-start">Illustration Attachment</label>
                                                                            </td>
                                                                        </tr>
                                                                        {{-- <tr>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="50%">Time:</span>
                                                                                    <input type="time" class="form-control" name="" id="frm_txt">
                                                                                </div>
                                                                            </td>
                                                                        </tr> --}}
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:10px; width:20px; height:20px;" class="form-check-input d-flex completion_activity" index="8.4" type="checkbox" name="po_not_finished" id="frm_txt_po_not_finished" value="1">
                                                                            </td>
                                                                            <td>
                                                                                <label class="form-check-label d-flex justify-content-start">P.O not yet Finished</label>
                                                                            </td>
                                                                            <td width="30">
                                                                                <input style="margin-left:10px; width:20px; height:20px;" class="form-check-input d-flex completion_activity" index="8.5" type="checkbox" name="for_repair" id="frm_txt_for_repair" value="1">
                                                                            </td>
                                                                            <td >
                                                                                <label class="form-check-label d-flex justify-content-start">For Repair</label>
                                                                            </td>
                                                                            {{-- <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="50%">Shots:</span>
                                                                                    <input type="text" class="form-control" name="" id="frm_txt">
                                                                                </div>
                                                                            </td> --}}

                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm w-50">
                                                                                    <span class="input-group-text" style="50%">Date:</span>
                                                                                    <input type="date" class="form-control completion_activity" name="stop_prod_date" id="frm_txt_stop_prod_date">
                                                                                </div>
                                                                            </td>
                                                                            {{-- <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="50%">Shots Accume:</span>
                                                                                    <input type="text" class="form-control" name="" id="frm_txt">
                                                                                </div>
                                                                            </td> --}}
                                                                        </tr>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:10px; width:20px; height:20px;" class="form-check-input d-flex completion_activity" index="8.6" type="checkbox" name="mold_checking" id="frm_txt_mold_checking" value="1">
                                                                            </td>
                                                                            <td>
                                                                                <label class="form-check-label d-flex justify-content-start">Mold Checking</label>
                                                                            </td>
                                                                            <td width="30">
                                                                                <input style="margin-left:10px; width:20px; height:20px;" class="form-check-input d-flex completion_activity" index="8.7" type="checkbox" name="mold_clean" id="frm_txt_mold_clean" value="1">
                                                                            </td>
                                                                            <td >
                                                                                <label class="form-check-label d-flex justify-content-start">Mold Clean</label>
                                                                            </td>

                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm w-50">
                                                                                    <span class="input-group-text" style="50%">Time:</span>
                                                                                    <input type="time" class="form-control completion_activity" name="stop_prod_time" id="frm_txt_stop_prod_time">
                                                                                </div>
                                                                            </td>
                                                                            {{-- <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Maint. Cycle:</span>
                                                                                    <input type="text" class="form-control" name="" id="frm_txt">
                                                                                </div>
                                                                            </td> --}}
                                                                            {{-- <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Machine No:</span>
                                                                                    <input type="text" class="form-control" name="" id="frm_txt">
                                                                                </div>
                                                                            </td> --}}
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="3">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" style="50%">Shots:</span>
                                                                                    <input type="text" class="form-control completion_activity" name="shots" id="frm_txt_shots">
                                                                                </div>
                                                                            </td>

                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Date Needed:</span>
                                                                                    <input type="date" class="form-control completion_activity" name="date_needed" id="frm_txt_date_needed">
                                                                                </div>
                                                                            </td>
                                                                            <td width="150" rowspan="2">
                                                                                <label class="form-check-label d-flex justify-content-start pt-1">Checked By:</label>
                                                                                {{-- <div class="input-group input-group-sm"> --}}
                                                                                    {{-- <span class="input-group-text" >Checked By:</span> --}}
                                                                                    {{-- <input type="text" class="form-control form-control-sm completion_activity" name="checked_by" id="SelCheckedBy"> --}}
                                                                                {{-- </div> --}}
                                                                                <select class="form-control select2bs5 form-control-sm completion_activity" id="SelCheckedBy" name="checked_by"></select>
                                                                            </td>

                                                                            {{-- <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Shipt. Sched:</span>
                                                                                    <input type="text" class="form-control" name="" id="frm_txt">
                                                                                </div>
                                                                            </td> --}}

                                                                            {{-- <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" >PTNR Ctrl no.</span>
                                                                                    <input type="text" class="form-control" name="" id="frm_txt">
                                                                                </div>
                                                                            </td> --}}
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="3">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Shots Accume:</span>
                                                                                    <input type="text" class="form-control completion_activity" name="shots_accume" id="frm_txt_shots_accume">
                                                                                </div>
                                                                            </td>

                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Shipt. Sched:</span>
                                                                                    <input type="text" class="form-control completion_activity" name="ship_sched" id="frm_txt_ship_sched">
                                                                                </div>
                                                                            </td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td colspan="3">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Maint. Cycle:</span>
                                                                                    <input type="text" class="form-control completion_activity" name="maint_cycle" id="frm_txt_maint_cycle">
                                                                                </div>
                                                                            </td>

                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text" >PTNR Ctrl no.</span>
                                                                                    <input type="text" class="form-control completion_activity" name="ptnr_ctrl_no" id="frm_txt_ptnr_ctrl_no">
                                                                                </div>
                                                                            </td>
                                                                            <td></td>
                                                                        </tr>

                                                                        <tr>
                                                                            <td colspan="3">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Machine No:</span>
                                                                                    <input type="text" class="form-control completion_activity" name="machine_no" id="frm_txt_machine_no">
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="3"></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4 text-center" style="padding:0">
                                                            <div class="table-responsive col-sm-12" style="padding:0">
                                                                <table id="tblCompletionActivityC" class="table table-sm table-bordered table-hover display nowrap" style="width: 100%;">
                                                                    <thead>
                                                                        <th style="padding:0" colspan="4">
                                                                            <strong><label class="form-check-label">Treatment on Affected Lot</label></strong>
                                                                        </th>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex completion_activity" index="8.8" type="checkbox" name="w_produce_unit" id="frm_txt_w_produce_unit" value="1">
                                                                            </td>
                                                                            <td>
                                                                                <label class="form-check-label d-flex justify-content-start">With Produce Unit</label>
                                                                            </td>
                                                                            <td width="30">
                                                                                <input style="margin-left:0; width:20px; height:20px;" class="form-check-input d-flex completion_activity" index="8.9" type="checkbox" name="wo_produce_unit" id="frm_txt_wo_produce_unit" value="1">
                                                                            </td>
                                                                            <td>
                                                                                <label class="form-check-label d-flex justify-content-start">W/o Produce Unit</label>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Affected Lot</span>
                                                                                    <input type="text" class="form-control completion_activity" name="affected_lot" id="frm_txt_affected_lot">
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Qty</span>
                                                                                    <input type="text" class="form-control completion_activity" name="affected_lot_qty" id="frm_txt_affected_lot_qty">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Backtracking Lot</span>
                                                                                    <input type="text" class="form-control completion_activity" name="backtracking_lot" id="frm_txt_backtracking_lot">
                                                                                </div>
                                                                            </td>
                                                                            <td colspan="2">
                                                                                <div class="input-group input-group-sm">
                                                                                    <span class="input-group-text">Qty</span>
                                                                                    <input type="text" class="form-control completion_activity" name="backtracking_lot_qty" id="frm_txt_backtracking_lot_qty">
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Part VIII Division End -->
                        </div>
                  </div> <!-- End Body -->
                  <div class="modal-footer">
                      <div class="actions">
                          <button type="button" id="idbtnSaveFrm" class="btn btn-primary btn-sm"><i class="fa fa-check fa-xs icon_save"></i> Save</button>
                          <button type="button" id="id_btn_close" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                      </div>
                  </div>
                </form>

              </div>
            </div>
        </div>

        <!-- MODALS -->

        <div class="modal fade" id="modalExportPackingList">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h4 class="modal-title">Generate Packing List</h4>
                        <button type="button" style="color: #fff;" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formGeneratePackingList">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Select Control # for Export</label>
                                        <select class="form-control select2bs5 selectControlNumber" name="ctrl_no" id="txtCtrlNo" style="width: 100%;"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                            <button id="btnExportFile" class="btn btn-primary"><i id="iBtnDownloadPackingList" class="fas fa-file-download"></i> Export</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Filtering Modal Start -->
        <div class="modal fade" id="modalAdvancedSearch" data-bs-keyboard="false" data-bs-backdrop="static">
            <div class="modal-dialog modal-md" style="min-width: 550px;">
                <div class="modal-content">
                    <div class="modal-header bg-secondary">
                        <h4 class="modal-title"><i class="fas fa-filter"></i>&nbsp;Multiple Filter</h4>
                        <button type="button" style="color: #fff;" class="close" data-bs-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="frmSearch" autocomplete = 'off'>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Request Type</label>
                                        <select class="form-control" type="text" name="request_type"
                                            id="request_type" style="width: 100%;">
                                            <option value="" disabled selected>Select Request type</option>
                                            <option value="1">For Maintenance</option>
                                            <option value="2">Temporary Stop</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <label>Request Date</label>
                            <br>
                                <div class="col">
                                    <div class="form-group">
                                        <label>From</label>
                                        <input type="date" class="form-control" name="request_date_from" value="">
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label>To</label>
                                        <input type="date" class="form-control" name="request_date_to" value="">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" type="text" name="status"
                                        id="status" style="width: 100%;">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="0">For Submission</option>
                                        <option value="1">For Conformance</option>
                                        <option value="2">Ongoing Condition Checking</option>
                                        <option value="3">For Machine Setup</option>
                                    </select>
                                </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer d-flex">
                    <div class="p-2">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal" style="float: left">Close</button>
                    </div>

                    <div class="p-2">
                        <button type="button" id="btnReset" class="btn btn-secondary" style="float: left">Reset</button>
                    </div>

                    <div class="ml-auto p-2">
                        <button type="button" id="btnRunAdvancedSearch" class="btn btn-primary"><i class="fa fa-check"></i> Search</button>
                    </div>
                    </div>

                </div>
            </div>
        </div><!-- Filtering Modal End -->

        <!-- DELETE REQUEST MODAL START -->
        <div class="modal fade" id="modalDeleteRequest">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h4 class="modal-title"><i class="fa-solid fa-trash-can"></i>&nbsp;&nbsp;Delete Request?</h4>
                        <button type="button" style="color: #fff" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="FrmDeleteRequest">
                        @csrf
                        <div class="modal-body">
                            <div class="row d-flex justify-content-center">
                                <label class="text-secondary mt-2">Are you sure you want to delete this request?</label>
                                <input type="hidden" class="form-control" name="request_id" id="deleterequestID">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="btnDeleteRequest" class="btn btn-primary"><i id="deactivateIcon"
                                    class="fa fa-trash-can"></i> Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- DELETE REQUEST MODAL END -->

        <!-- CONFIRM REQUEST MODAL START -->
        <div class="modal fade" id="modalChangeStatusRequest">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title"><i class="fa-solid fa-file-circle-check"></i>&nbsp;&nbsp;Confirmation</h4>
                        <button type="button" style="color: #fff" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="FrmChangeStatusRequest">
                        {{-- @csrf --}}
                        <input name="_token" id="csrf_token" value="{{ csrf_token()}}" hidden>
                        <div class="modal-body">
                            <div class="row d-flex justify-content-center">
                                <label class="text-secondary mt-2">Are you sure you want to proceed?</label>
                                <input type="hidden" class="form-control" name="request_id" id="ChangeStatusRequestID">
                                <input type="hidden" class="form-control" name="process_status" id="ProcessStatus">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="btnChangeStatusRequest" class="btn btn-primary"><i id="ChangeStatusRequestIcon"
                                    class="fa fa-check"></i> Proceed</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- CONFIRM REQUEST MODAL END -->

        <!-- PARTS DRAWING START -->
        <div class="modal fade" id="modalPartsDrawing" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h4 class="modal-title">X. Internally Fabricated Parts Verification (Responsible: Die Maintenance Engineering)</h4>
                        <button type="button" id="CloseBtnFrmPartsDrawing" style="color: #fff" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="FrmPartsDrawing" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row d-flex justify-content-center">
                                <label class="text-secondary text-center">Internal parts fabrication verification</label>
                                <input type="hidden" class="form-control" name="request_id_for_parts_drawing" id="frm_txt_request_id_for_parts_drawing">
                            </div>
                            {{-- X. Internal Fabrication --}}
                            <div class="row col-sm-12">
                                {{-- 1st Column --}}
                                <div class="col-sm-6 border border-dark text-center">
                                    <label class="form-control-label mt-1">Illustration (Parts Drawing):</label> <br>
                                    {{-- Upload Part Drawing File --}}
                                    <div class="col-sm-12 border border-dark">
                                        <br>
                                        <div id="AttachmentDiv">
                                            <label class="form-control-label">Attachment:</label>
                                            {{-- <a id="download_file" name="download_file" href='download_file/".$employee_infos->cash_advance_details->id."'> See Attachment</a> --}}
                                            {{-- <a id="download_file" name="download_file"> See Attachment</a> --}}

                                            {{-- <button type="button" id="download_file" name="download_file" class="btn btn-primary btn-sm d-none">
                                                <i class="fa-solid fa-file-arrow-down"></i>
                                                See Attachment
                                            </button> --}}
                                        </div>

                                        <input type="file" class="" id="txtAddFile" name="uploaded_file" accept=".png, .jpg, .jpeg" style="width:100%;" required>
                                        <input type="text" class="form-control d-none" name="uploaded_file" id="txtEditUploadedFile" disabled>
                                        <div class="form-group form-check d-none m-0" id="btnReuploadTriggerDiv">
                                            <input type="checkbox" class="form-check-input d-none" id="btnReuploadTrigger">
                                            <label class="d-none" id="btnReuploadTriggerLabel"> Re-upload Drawing</label>
                                        </div>
                                        <br><br>
                                    </div>
                                    {{-- Fabricated By & Measurement Validate By --}}
                                    <div class="row col-sm-12 float-right">
                                        <div class="col-sm-6">
                                            <br>
                                            <label class="form-check-label"> Fabricated by:</label>
                                            <select class="form-control select2bs5 dieset_condition_data" id="selFabricatedBy" name="fabricated_by" style="width: 100%;">
                                                {{-- <option disabled selected>-- Select Engr. --</option> --}}
                                            </select>
                                            {{-- <input type="text" class="form-control text-center align-middle dieset_condition_data" name="fabricated_by_id" id="frm_txt_fabricated_by_id" hidden>
                                            <input type="text" class="form-control text-center align-middle dieset_condition_data" name="fabricated_by" id="frm_txt_fabricated_by" readonly> --}}
                                            <br>
                                        </div>
                                        <div class="col-sm-6">
                                            <br>
                                            <label class="form-check-label"> Measurement validated by:</label>
                                            <select class="form-control select2bs5 dieset_condition_data" id="selValidatedBy" name="m_validated_by" style="width: 100%;">
                                                {{-- <option disabled selected>-- Select Engr. --</option> --}}
                                            </select>
                                            {{-- <input type="text" class="form-control text-center align-middle dieset_condition_data" name="m_validated_by_id" id="frm_m_validated_by_id" hidden>
                                            <input type="text" class="form-control text-center align-middle dieset_condition_data" name="m_validated_by" id="frm_m_validated_by" readonly> --}}
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                {{-- 2nd Column --}}
                                <div class="table-responsive col-sm-6 border border-dark">
                                    <div class="text-right mt-2">
                                        <button type="button" class="btn btn-primary btn-sm float-right" id="btnPartsDrawingAddRow"><i class="fa fa-check fa-xs icon_save"></i> Add Row</button>
                                        <button type="button" class="btn btn-sm btn-danger float-right mr-2" id="btnPartsDrawingRemoveRow"><i class="fas fa-times"></i> Remove Row</button>
                                    </div>
                                    <table id="tblPartsDrawingData" class="table table-sm table-bordered table-hover display nowrap mt-1" style="width: 100%;">
                                        <thead>
                                            <tr class="bg-light">
                                            {{-- <th style="width: 20%;">Action</th> --}}
                                            <td hidden style="width: 15%;">Counter</td>
                                            <td style="width: 5%;">#</td>
                                            <td style="width: 40%;">
                                                <label class="form-check-label">Specification</label>
                                            </td>
                                            <td style="width: 40%;">
                                                <label class="form-check-label">Actual Measurement</label>
                                            </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td hidden>
                                                    <input type="text" class="form-control" name="PartsDrawingRowCounter" id="PartsDrawingRowCounter" value="1" readonly>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <label class="form-check-label"> 1.</label>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control PartsDrawing" index="10.1" name="specification" id="frm_specification">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control PartsDrawing" index="10.1" name="actual_measurement" id="frm_actual_measurement">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" id="CloseBtnFrmPartsDrawing" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="btnSavePartsDrawing" class="btn btn-primary"><i id="SavePartsDrawingIcon" class="fa fa-check"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- PARTS DRAWING MODAL END -->
    </div>
    @endsection

    @section('js_content')
        <style>
            table.dataTable td {
            font-size:  1em;
            /* padding:    5px 5px !important; */
            text-align: center;
            }
        </style>

        <script type="text/javascript">
            $(document).ready(function(){

                // $.fn.modal.Constructor.prototype.enforceFocus = function() {};

                $('.select2bs5').select2({
                    theme: 'bootstrap-5',
                    // dropdownParent: $('body'),
                    // width: '100%'
                    // dropdownAutoWidth: true,
                    // dropdownParent: $('#modalProdIdentification')
                });

                // Apply Select2 to all select elements inside any modal dynamically
                // $('.modal').on('shown.bs.modal', function () {
                //     $(this).find('.select2bs5').each(function() {
                //         $(this).select2({
                //             theme: 'bootstrap-5',
                //             dropdownParent: $(this).closest('.modal') // Ensures correct parent modal
                //         });
                //     });
                // });

                // PART 2 USER SELECTION
                GetSupervisorEngrUser('1,9,15,16,17', $("#selFabricatedBy")); //Internally Fabricated Parts(X) Fabricated By
                GetSupervisorEngrUser('1,9,15,16,17', $("#selValidatedBy")); //Internally Fabricated Parts(X) Validated By

                // PART 4 USER SELECTION
                GetProductionUsers('1,4,12,13,14', $("#selProductionUser")); //Machine Set-up 1st Adjustment (In-Charge)
                GetTechnicianUser('9,11,15', $("#selTechnicianUser")); //Machine Set-up 2md Adjustment (In-Charge)
                GetSupervisorEngrUser('1,9,15,16,17', $("#selSupervisorEngrUser")); //Machine Set-up 3rd Adjustment (In-Charge)

                // PART 5 USER SELECTION
                GetProductionUsers('1,4,12,13,14', $("#selProductionVisualUser")); // Production Visual Inspection
                // GetProductionUsers('1,4,12,13,14', $("#selProductionDimentionUser")); // Production Dimension Inspection //old
                GetTechnicianUser('9,11,15', $("#selProductionDimentionUser")); // // Production Dimension Inspection //new
                GetTechnicianUser('9,11,15', $("#selTechnicianVisualUser")); // Technician Visual Inspection
                GetTechnicianUser('9,11,15', $("#selTechnicianDimensionUser")); // Technician Dimension Inspection
                GetQcInspectorUser('2,5', $("#selQcVisualUser")); // QC Visual Inspection
                GetQcInspectorUser('2,5', $("#selQcDimensionUser")); // QC Dimension Inspection
                GetSupervisorEngrUser('1,9,15,16,17', $("#selEngrVisualUser")); // Process Engr Visual Inspection
                GetSupervisorEngrUser('1,9,15,16,17', $("#selEngrDimensionUser")); // Process Engr Dimension Inspection

                GetProductionUsers('1,4,12,13,14', $("#selMachineSetupSamplesPIC")); //For Machine Setup Sample (PIC)
                GetQcInspectorUser('2,5', $("#selMachineSetupSamplesQc")); //For Machine Setup Sample (QC)
                GetSupervisorEngrUser('1,9,15,16,17', $("#selMachineSetupSamplesEngr")); //For Machine Setup Sample (ENGR)

                GetSupervisorEngrUser('1,9,15,16,17', $("#selPressureEngrUser"));
                GetQcInspectorUser('2,5', $("#selPressureQCUser"));
                GetSupervisorEngrUser('1,9,15,16,17', $("#selTempNozzleEngrUser"));
                GetQcInspectorUser('2,5', $("#selTempNozzleQCUser"));
                GetSupervisorEngrUser('1,9,15,16,17', $("#selTempMoldEngrUser"));
                GetQcInspectorUser('2,5', $("#selTempMoldQCUser"));
                GetSupervisorEngrUser('1,9,15,16,17', $("#selCtimeEngrUser"));
                GetQcInspectorUser('2,5', $("#selCtimeQCUser"));

                // SAMPLE SELECTION ONLY *FOR REVISION
                GetProductionUsers('1,4,12,13,14', $("#selNgJudgedBy")); //For Specification (NG Judged By)
                GetQcInspectorUser('2,5', $("#selOkVerifiedBy")); //For Specification (OK Verified By)
                GetSupervisorEngrUser('1,9,15,16,17', $("#selSignedBy")); //For Specification (Signed By)

                // PART 8 USER SELECTION
                GetProductionUsers('1,4,12,13,14', $("#SelPreparedBy")); //For Specification (Prepared By)
                GetProductionUsers('1,4,12,13,14', $("#SelCheckedBy")); //For Specification (Checked By)

                // $('.select2bs4').select2({
                //         theme: 'bootstrap4'
                //     });

                $("#tbl_dmrpqc").on('click', '.actionExportBtn', function(e){
                    let id = $(this).attr('dmrpqc_id');
                    let process_status = $(this).attr('process_status');
                    // console.log('id', id);
                    window.open(`view_pdf/${id},${process_status}`, '_blank');
                });

                $(document).on('hidden.bs.modal', '.modal',
                () => $('.modal:visible').length && $(document.body).addClass('modal-open'));

                frmProdIdentification = $('#frm_prod_identification');
                    // GetUserIDBySession();

                $('#btnReset').on('click', function(){
                $("#frmSearch").trigger("reset");
                });

                $("#SearchYear").keyup(function(e){
                    e.preventDefault();
                    dataTableDmrpqc.draw();
                });

                $("#SelectMonth").on('change', function(e){
                    e.preventDefault();
                    dataTableDmrpqc.draw();
                });

                $('#btnRunAdvancedSearch').on('click', function(){
                    console.log($('#frmSearch').serialize());
                    dataTableDmrpqc.draw();
                    $('#modalAdvancedSearch').modal('hide');
                });

                //===== DATATABLES ================
                dataTableDmrpqc = $("#tbl_dmrpqc").DataTable({
                    "processing": false,
                    "serverSide": true,
                    "responsive": true,
                    "ajax": {
                        url: "view_dmrpqc",
                        data: function(param){
                            param.year =  $("input[name='year']").val();
                            param.month =  $("select[name='month_value']").val();
                            // param.month =  $("#SearchMonth").val();
                            // param.device_name = $("select[name='supplier_name']", $("#frmSearch")).val();
                            param.request_type = $("select[name='request_type']", $("#frmSearch")).val();
                            param.request_date_from = $("input[name='request_date_from']", $("#frmSearch")).val();
                            param.request_date_to = $("input[name='request_date_to']", $("#frmSearch")).val();
                            param.status = $("select[name='status']", $("#frmSearch")).val();
                            param.category = $("select[name='factory']").val();
                        }
                    },
                    "columns": [
                    {
                        "data": "action",
                        orderable: false,
                        searchable: false,
                    },
                    { "data": "status"},
                    { "data": "process_status"},
                    { "data": "device_name"},
                    { "data": "po_number"},
                    { "data": "item_code"},
                    {
                        "data": "request_type",
                        render: function(data) {
                            if(data == 1) {
                                return 'For Maintenance'
                            }
                            else if(data == 2) {
                                return 'Temporary Stop'
                            }
                            else if(data == 3) {
                                return 'Comma Change'
                            }
                        }
                    },
                    // { "data": "created_by" },
                    { "data": "start_date_time"},
                    ],
                    "order": [0, 'desc']
                });

                $("#btnShowAddRequest").click(function(){
                    frmProdIdentification[0].reset();
                    ProcessStatusDivControls('');
                    // $("#frm_prod_identification .dieset_condition_data").attr('disabled', true);
                    // $("#frm_prod_identification .dieset_condition_data").attr('required', false);
                    frmProdIdentification.find('input').attr('disabled',false);
                    frmProdIdentification.find('select').attr('disabled',false);
                    // $('#idbtnSaveFrm').prop('hidden', false);
                    $('#idbtnSaveFrm').removeClass('d-none');
                    // frmProdIdentification.find('#idbtnSaveFrm').attr('hidden',false);
                    // frmProdIdentification.find('button').attr('disabled',false);
                    frmProdIdentification.find('textarea').attr('readonly',false);
                    GetUserIDBySession();
                    frmProdIdentification.find('#txtCategory').val($('select[name="factory"]').val());
                    $('#modalProdIdentification').modal('show');
                });

                $('#modalProdIdentification').on('hidden.bs.modal', function(){
                    frmProdIdentification[0].reset();
                });
            });
        </script>
    @endsection
@endauth
