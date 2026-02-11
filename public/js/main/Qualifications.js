function GetQualiPartName(cboElement){
    let result = '<option value="" disabled selected>Select Device Name</option>';
    $.ajax({
        type: "get",
        url: "get_devices_from_quali",
        dataType: "json",
        beforeSend: function(){
            result = '<option value="0" disabled selected>--Loading--</option>';
        },
        success: function (response) {
            let device_details = response['quali_part_name'];
            // console.log(device_details);
            // console.log(device_details.length);
            // console.log('part_name', device_details[0].prod_runcard_details.part_name);
            if(device_details.length > 0) {
                    result = '<option value="" disabled selected>Select Device Name</option>';
                for (let index = 0; index < device_details.length; index++) {
                    // console.log('part_name', device_details[index].prod_runcard_details);
                    result += '<option value="' + device_details[index].part_name + '">' + device_details[index].part_name + '</option>';
                }
            }else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            cboElement.html(result);
        },
        error: function(data, xhr, status) {
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

$(document).ready(function(){
    $( '.select2bs5' ).select2({
        theme: 'bootstrap-5'
    });

    // GetDeviceNameFromIPQC($("#txtSelectPartName"));

    // function GetDeviceNameFromIPQC(cboElement){
    //     let result = '<option value="" disabled selected>--Select Part Name--</option>';
    //     $.ajax({
    //             type: "get",
    //             url: "get_devices_from_quali",
    //             data: {
    //             },
    //             dataType: "json",
    //             beforeSend: function() {
    //                 result = '<option value="0" disabled selected> -- Loading -- </option>';
    //                 cboElement.html(result);
    //             },
    //             success: function(response) {
    //                 let quali_part_name = response['quali_part_name'];
    //                 if (quali_part_name.length > 0) {
    //                         result = '<option value="0" disabled selected>--Select Part Name--</option>';
    //                     for (let index = 0; index < quali_part_name.length; index++) {
    //                         result += '<option value="' + quali_part_name[index].prod_runcard_details.part_name + '">'+quali_part_name[index].prod_runcard_details.part_name+'</option>';
    //                     }
    //                 } else {
    //                     result = '<option value="0" selected disabled> -- No record found -- </option>';
    //                 }
    //                 cboElement.html(result);
    //             },
    //             error: function(data, xhr, status) {
    //                 result = '<option value="0" selected disabled> -- Reload Again -- </option>';
    //                 cboElement.html(result);
    //                 console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
    //             }
    //     });
    // }

    const delay = (fn, ms) => {
        let timer = 0
        return function(...args) {
            clearTimeout(timer)
            timer = setTimeout(fn.bind(this, ...args), ms || 0)
        }
    }

    let frmQualifications = $('#formAddQualiDetails');

    $('#ScanProductLot').on('click',function (e) {
        $('#modalQrScanner').modal('show');
        $('#QualitextQrScanner').val('');
        setTimeout(() => {
            $('#QualitextQrScanner').focus();
        }, 500);
    });

    $('#QualitextQrScanner').keyup(delay(function(e){
        const qrScannerValue = $('#QualitextQrScanner').val();
        let ScanQrCodeVal = JSON.parse(qrScannerValue)
        // let ScanQrCodeVal = $('#QualitextQrScanner').getJSON(qrScannerValue)
        $.ajax({
            type: "get",
            url: "verify_production_lot",
            data: {
                'production_lot': ScanQrCodeVal.production_lot,
                // 'production_lot_extension': ScanQrCodeVal.lot_no_ext,
                'part_name': ScanQrCodeVal.part_name
                // 'process_category': 1
            },
            dataType: "json",
            success: function (response) {
                // console.log(ScanQrCodeVal.lot_no +''+ ScanQrCodeVal.lot_no_ext);
                // console.log(response['production_lot']);
                if(response['production_lot'] == ''){
                    toastr.error('This Production Lot is not found in Runcard!');
                    $('#txtProdRuncardId').val('');
                    $('#txtProductionLot').val('');
                    $('#txtPoNumber').val('');
                    $('#txtPoQty').val('');
                    $('#txtPartCode').val('');
                    $('#txtPartName').val('');
                }else if(response['production_lot'] == ScanQrCodeVal.production_lot){
                    toastr.success('Production Lot Matched!');
                    // $('#txtProductionLot').val(ScanQrCodeVal.lot_no +''+ ScanQrCodeVal.lot_no_ext);
                    $('#txtProdRuncardId').val(response['prod_runcard_id']);
                    $('#txtProductionLot').val(ScanQrCodeVal.production_lot);
                    $('#txtPoNumber').val(ScanQrCodeVal.po_number);
                    $('#txtPoQty').val(ScanQrCodeVal.po_quantity);
                    $('#txtPartCode').val(ScanQrCodeVal.part_code);
                    $('#txtPartName').val(ScanQrCodeVal.part_name);

                    // const trimmed_mat_name = ScanQrCodeVal.part_name.replace(/ /g,'');
                    // GetBDrawingFromACDCS(trimmed_mat_name, 'B Drawing', $("#txtSelectDocNoBDrawing"));
                    // GetInspStandardFromACDCS(trimmed_mat_name, 'Inspection Standard', $("#txtSelectDocNoInspStandard"));
                    // GetUDFromACDCS(trimmed_mat_name, 'Urgent Direction', $("#txtSelectDocNoUD"));
                }else{
                    toastr.error('Incorrect Production Lot!');
                    $('#txtProdRuncardId').val('');
                    $('#txtProductionLot').val('');
                    $('#txtPoNumber').val('');
                    $('#txtPoQty').val('');
                    $('#txtPartCode').val('');
                    $('#txtPartName').val('');
                }

                $('#modalQrScanner').modal('hide');
            }
        });
    }, 300));
    // CLARK NEW CODE

    let dtQualificationPending = $("#tblRuncardQualiPending").DataTable({
        "processing" : true,
        "serverSide" : true,
        "lengthMenu": [ [25, -1], [25, "All"] ],
        "ajax" : {
            url: "view_qualification_data",
            data: function(param){
            param.part_name =  $("#txtSelectPartName").val();
            param.status =  [0,1,2,5]; //Status Pending, Updated (A) or (B), For Re-inspection
            // param.process_status =  [1,2,3]; //1 - Prod, 2 - QC, 3 - ENGR
            // param.first_molding_status = [0]; //First Molding Status : For IPQC
            // param.process_category = 1; //Process Category : First Molding
            }
        },
        fixedHeader: true,
        "order": [0, 'desc'],
        "columns":[
            { "data" : "id", searchable:false, visible:false },
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "quali_status" },
            { "data" : "process_status" },
            { "data" : "request_created_at" },
            { "data" : "part_name" },
            { "data" : "po_number" },
            { "data" : "production_lot" },
            { "data" : "judgement" },
            { "data" : "prod_input_qty" },
            { "data" : "inspected_date" },
        ],
    });

    let dtQualificationCompleted = $("#tblRuncardQualiCompleted").DataTable({
        "processing" : true,
        "serverSide" : true,
        "ajax" : {
            url: "view_qualification_data",
            data: function(param){
            param.part_name =  $("#txtSelectPartName").val();
            param.status = [3]; //Status 3 = Submitted: Judgement - Accepted
            // param.first_molding_status = [1, 3]; //First Molding Status : For Mass Prod, Done
            // param.process_category = 1; //Process Category : First Molding
            }
        },
        fixedHeader: true,
        "order": [0, 'desc'],
        "columns":[
            { "data" : "id", searchable:false, visible:false },
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "quali_status" },
            { "data" : "process_status" },
            { "data" : "request_created_at" },
            { "data" : "part_name" },
            { "data" : "po_number" },
            { "data" : "production_lot" },
            { "data" : "judgement" },
            { "data" : "prod_input_qty" },
            { "data" : "inspected_date" },
        ],
    });

    let dtQualificationResetup = $("#tblRuncardQualiResetup").DataTable({
        "processing" : true,
        "serverSide" : true,
        "ajax" : {
            url: "view_qualification_data",
            data: function(param){
            param.part_name =  $("#txtSelectPartName").val();
            param.status = [4]; //Status 4 = Submitted: Judgement - Rejected
            // param.first_molding_status = [2]; //First Molding Status : For Resetup
            param.process_category = 1; //Process Category : First Molding
            }
        },
        fixedHeader: true,
        "order": [0, 'desc'],
        "columns":[
            { "data" : "id", searchable:false, visible:false },
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "quali_status" },
            { "data" : "process_status" },
            { "data" : "request_created_at" },
            { "data" : "part_name" },
            { "data" : "po_number" },
            { "data" : "production_lot" },
            { "data" : "judgement" },
            { "data" : "prod_input_qty" },
            { "data" : "inspected_date" },
        ],
    });

    function validateUser(userId, validPosition, callback){ // this function will accept scanned id and validPosition based on user table (number only)
        console.log('validPosition', validPosition);
        $.ajax({
            type: "get",
            url: "validate_user",
            data: {
                'id'    : userId,
                'pos'   : validPosition
            },
            dataType: "json",
            success: function (response) {
                let value1
                if(response['result'] == 1){
                    value1 = true;
                }
                else{
                    value1 = false;
                }

                callback(value1);
            }
        });
    }

    $('#txtSelectPartName').on('change', function(e){
            let searchPartName = $('#txtSelectPartName').val();
            $.ajax({
                type: "get",
                url: "get_qualification_data",
                data: {
                    "part_name" : searchPartName
                    // "process_category" : 1
                },
                dataType: "json",
                success: function (response) {
                    let qualification_data = response['quali_data'];

                    $('#txtSelectPartName').val(searchPartName);
                    $('#txtSearchPartCode').val(qualification_data[0].part_code);
                    // $('#txtSearchMaterialName').val(IpqcData[0].material_name);

                    // let mat_name = IpqcData[0].material_name;
                    // mat_name = mat_name.replace(/ /g,'');

                    // GetBDrawingFromACDCS(mat_name, 'B Drawing', $("#txtSelectDocNoBDrawing"));
                    // GetInspStandardFromACDCS(mat_name, 'Inspection Standard', $("#txtSelectDocNoInspStandard"));
                    // GetUDFromACDCS(mat_name, 'Urgent Direction', $("#txtSelectDocNoUD"));

                    dtQualificationPending.draw();
                    dtQualificationCompleted.draw();
                    dtQualificationResetup.draw();
                }
            });
        // }
    });

    $('input[name="keep_sample"]').click('click', function(e){
        if(frmQualifications.find('#txtKeepSample1').prop('checked')){
            frmQualifications.find('input[name="keep_sample"]').prop('required', false);
        }else if(frmQualifications.find('#txtKeepSample2').prop('checked')){
            frmQualifications.find('input[name="keep_sample"]').prop('required', false);
        }else{
            frmQualifications.find('input[name="keep_sample"]').prop('required', true);
        }
    });

    $(document).on('click', '#btnAddQualificationData',function(e){
        $('#formAddQualiDetails')[0].reset();
        frmQualifications.find("#txtProcessStatus").val(1);

        $("#buttonAddQualiProdModeOfDefect").prop('disabled', true);
        $("#buttonAddQualiQcModeOfDefect").prop('disabled', true);

        $("#btnSaveQualificationDetails").prop('hidden', false);
        $("#ScanProductLot").prop('hidden', true);

        $('#formAddQualiDetails #txtProdRuncardId').val($('#formProductionRuncard #txtProdRuncardId').val());
        $('#formAddQualiDetails #txtProductionLot').val($('#formProductionRuncard #txtProductionLot').val());
        $('#formAddQualiDetails #txtPoNumber').val($('#formProductionRuncard #txtPONumber').val());
        $('#formAddQualiDetails #txtPoQty').val($('#formProductionRuncard #txtPOBalance').val());
        $('#formAddQualiDetails #txtPartCode').val($('#formProductionRuncard #txtPartCode').val());
        $('#formAddQualiDetails #txtPartName').val($('#formProductionRuncard #txtPartName').val());

        $("#QualificationProdDiv").prop('hidden', false);
        $("#QualificationQcDiv").prop('hidden', true);
        $("#QualificationQcEngrDiv").prop('hidden', true);
        $("#QualificationQcEngr2Div").prop('hidden', true);

        frmQualifications.find('#txtQualiProdNameID').prop('disabled', false)
        frmQualifications.find('#txtQualiProdName').prop('disabled', false)
        frmQualifications.find('#txtQualiProdDate').prop('disabled', false)
        frmQualifications.find('#txtQualiProdInputQty').prop('disabled', false)
        frmQualifications.find('#txtQualiProdNgQty').prop('disabled', false)
        frmQualifications.find('#txtQualiProdOutputQty').prop('disabled', false)
        frmQualifications.find('#txtQualiProdJudgement').prop('disabled', false)
        frmQualifications.find('#txtQualiProdActualSample').prop('disabled', false)
        frmQualifications.find('#txtQualiProdRemarks').prop('disabled', false)
        frmQualifications.find('#buttonAddQualiProdModeOfDefect').prop('disabled', false)

        frmQualifications.find("#txtQualiQCName").prop('disabled', true);
        frmQualifications.find("#txtQualiQcDate").prop('disabled', true);
        frmQualifications.find("#txtQualiQcInputQty").prop('disabled', true);
        frmQualifications.find("#txtQualiQcOutputQty").prop('disabled', true);
        frmQualifications.find("#txtQualiQcNgQty").prop('disabled', true);
        frmQualifications.find("#txtQualiQcJudgement").prop('disabled', true);
        frmQualifications.find("#txtQualiQcActualSample").prop('disabled', true);
        frmQualifications.find("#txtQualiQcRemarks").prop('disabled', true);

        frmQualifications.find("#txtCtHeightDataQc").prop('disabled', true);
        frmQualifications.find("#txtCtHeightDataEngr").prop('disabled', true);
        frmQualifications.find("#txtCtHeightDataRemarks").prop('disabled', true);
        frmQualifications.find("#txtDefectCheckpoint").prop('disabled', true);
        frmQualifications.find("#txtDefectCheckpointRemarks").prop('disabled', true);

        // frmQualifications.find("#frmSaveBtn").prop('hidden', false);
        // frmQualifications.find("#ScanProductLot").prop('disabled', false)
        // frmQualifications.find("#txtQcSamples").prop('disabled', false);
        // frmQualifications.find("#txtOkSamples").prop('disabled', false);
        // frmQualifications.find("#txtKeepSample1").prop('disabled', false);
        // frmQualifications.find("#txtKeepSample2").prop('disabled', false);
        // frmQualifications.find("#txtJudgement").prop('disabled', false);
        // frmQualifications.find("#txtSelectDocNoBDrawing").prop({hidden:false, disabled:false, required:true});
        // frmQualifications.find("#txtSelectDocNoInspStandard").prop({hidden:false, disabled:false, required:true});
        // frmQualifications.find("#txtSelectDocNoUD").prop({hidden:false, disabled:false, required:true});
        // frmQualifications.find("#txtRemarks").prop('disabled', false);
        // frmQualifications.find("#btnilqcmlink").prop('disabled', false);
        // frmQualifications.find('input[name="keep_sample"]').prop('hidden', false);

        // frmQualifications.find("#btnViewBDrawings").prop('disabled', true);
        // frmQualifications.find("#btnViewInspStdDrawings").prop('disabled', true);
        // frmQualifications.find("#btnViewUdDrawings").prop('disabled', true);

        // frmQualifications.find("#btnReuploadTriggerDiv").addClass("d-none");
        // frmQualifications.find("#btnPartsDrawingAddRow").addClass("d-none");

        // frmQualifications.find("#txtAddFile").removeClass('d-none');
        // frmQualifications.find("#txtAddFile").attr('required', true);
        // frmQualifications.find("#txtEditUploadedFile").addClass('d-none');
        // frmQualifications.find("#download_file").addClass('d-none');

        // if(frmQualifications.find('#txtKeepSample1').prop('checked')){
        //     frmQualifications.find('input[name="keep_sample"]').prop('required', false);
        // }else if(frmQualifications.find('#txtKeepSample2').prop('checked')){
        //     frmQualifications.find('input[name="keep_sample"]').prop('required', false);
        // }else{
        //     frmQualifications.find('input[name="keep_sample"]').prop('required', true);
        // }
        $('#modalQualiDetails').modal('show');
    });

    // ########################### QUALIFICATION PRODUCTION FUNCTIONS ############################### //
    $('#txtQualiProdInputQty, #txtQualiProdOutputQty').each(function(e){
        $(this).keyup(delay(function(e){
            let input_val = parseFloat($('#txtQualiProdInputQty').val());
            let output_val = parseFloat($('#txtQualiProdOutputQty').val());
            let ng_value;
            if(output_val === "" || isNaN(output_val) || input_val === "" || isNaN(input_val)){
                ng_value = '';
            }else if(output_val != "" || input_val != ""){
                ng_value = input_val - output_val;
                if(ng_value < 0){
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "NG Quantity cannot be less than Zero!",
                        showConfirmButton: false,
                        timer: 1500
                    });

                    $('#txtQualiProdInputQty').val('');
                    $('#txtQualiProdOutputQty').val('');
                    $('#tableQualiProdMOD tbody').empty();

                    ng_value = 0;
                    return;
                }
            }
            $('#txtQualiProdNgQty').val(ng_value);

            if(parseInt(ng_value) > 0){
                $("#buttonAddQualiProdModeOfDefect").prop('disabled', false);
            }
            else{
                $('#tableQualiProdMOD tbody').empty();
                $('#buttonAddQualiProdModeOfDefect').prop('disabled', true);
                $("#QualiProdlabelTotalNumberOfNG").text(parseInt(0));
            }

            if(parseInt(ng_value) === parseInt($('#QualiProdlabelTotalNumberOfNG').text())){
                $('#QualiProdlabelTotalNumberOfNG').css({color: 'green'})
                $('#labelIsTallyProd').css({color: 'green'})
                $('#labelIsTallyProd').addClass('fa-thumbs-up')
                $('#labelIsTallyProd').removeClass('fa-thumbs-down')
                // $("#btnAddRuncardStation").prop('disabled', false);
                $("#buttonAddQualiProdModeOfDefect").prop('disabled', true);
                $("#btnSaveQualificationDetails").prop('disabled', false);
            }else if(parseInt(ng_value) > parseInt($('#QualiProdlabelTotalNumberOfNG').text())){
                console.log('Mode of Defect NG is greater than NG qty');
                $('#QualiProdlabelTotalNumberOfNG').css({color: 'red'})
                $('#labelIsTallyProd').css({color: 'red'})
                $('#labelIsTallyProd').addClass('fa-thumbs-down')
                $('#labelIsTallyProd').removeClass('fa-thumbs-up')

                // $("#btnAddRuncardStation").prop('disabled', true);
                $("#buttonAddQualiProdModeOfDefect").prop('disabled', false);
                $("#btnSaveQualificationDetails").prop('disabled', true);
            }
        }, 1000));
    });

    $('#txtQualiQcInputQty, #txtQualiQcOutputQty').each(function(e){
    $(this).keyup(delay(function(e){
        let input_val = parseFloat($('#txtQualiQcInputQty').val());
        let output_val = parseFloat($('#txtQualiQcOutputQty').val());
        let ng_value;
        if(output_val === "" || isNaN(output_val) || input_val === "" || isNaN(input_val)){
            ng_value = '';
        }else if(output_val != "" || input_val != ""){
            ng_value = input_val - output_val;
            if(ng_value < 0){
                Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "NG Quantity cannot be less than Zero!",
                    showConfirmButton: false,
                    timer: 1500
                });

                $('#txtQualiQcInputQty').val('');
                $('#txtQualiQcOutputQty').val('');
                $('#tableQualiQcMOD tbody').empty();

                ng_value = 0;
                return;
            }
        }
        $('#txtQualiQcNgQty').val(ng_value);

        if(parseInt(ng_value) > 0){
            $("#buttonAddQualiQcModeOfDefect").prop('disabled', false);
        }
        else{
            $('#tableQualiQcMOD tbody').empty();
            $('#buttonAddQualiQcModeOfDefect').prop('disabled', true);
            $("#QualiQclabelTotalNumberOfNG").text(parseInt(0));
        }

        if(parseInt(ng_value) === parseInt($('#QualiQclabelTotalNumberOfNG').text())){
            $('#QualiQclabelTotalNumberOfNG').css({color: 'green'})
            $('#labelIsTallyNg').css({color: 'green'})
            $('#labelIsTallyNg').addClass('fa-thumbs-up')
            $('#labelIsTallyNg').removeClass('fa-thumbs-down')
            // $("#btnAddRuncardStation").prop('disabled', false);
            $("#buttonAddQualiQcModeOfDefect").prop('disabled', true);
            $("#btnSaveQualificationDetails").prop('disabled', false);
        }else if(parseInt(ng_value) > parseInt($('#QualiQclabelTotalNumberOfNG').text())){
            console.log('Mode of Defect NG is greater than NG qty');
            $('#QualiQclabelTotalNumberOfNG').css({color: 'red'})
            $('#labelIsTallyNg').css({color: 'red'})
            $('#labelIsTallyNg').addClass('fa-thumbs-down')
            $('#labelIsTallyNg').removeClass('fa-thumbs-up')

            // $("#btnAddRuncardStation").prop('disabled', true);
            $("#buttonAddQualiQcModeOfDefect").prop('disabled', false);
            $("#btnSaveQualificationDetails").prop('disabled', true);
        }
        console.log(input_val);
        console.log(output_val);
        // CalculateTotalOutputandYield(output_val,input_val);
    }, 1000));
    });

    $("#buttonAddQualiProdModeOfDefect").click(function(){
        let totalNumberOfProdMOD = 0;
        let ngQty = $('#formAddQualiDetails #txtQualiProdNgQty').val();
        let ProdrowModeOfDefect = `
            <tr>
                <td>
                    <select class="form-control select2 select2bs4 selectMOD" name="mod_id[]">
                        <option value="0">N/A</option>
                    </select>
                </td>
                <td id=textProdMODQuantity>
                    <input type="number" class="form-control textProdMODQuantity" name="mod_quantity[]" value="1" min="1">
                </td>
                <td id="buttonRemoveMOD">
                    <center><button class="btn btn-md btn-danger buttonRemoveMOD" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
                </td>
            </tr>`;

        $("#tableQualiProdMOD tbody").append(ProdrowModeOfDefect);

        getModeOfDefect($("#tableQualiProdMOD tr:last").find('.selectMOD'));
        getValidateTotalProdNgQty (ngQty,totalNumberOfProdMOD);
    });


    $("#tableQualiProdMOD").on('click', '.buttonRemoveMOD', function(){
        let totalNumberOfProdMOD = 0;
        let ngQty = $('#txtNgQuantity').val();

        $(this).closest ('tr').remove();
        getValidateTotalProdNgQty (ngQty,totalNumberOfProdMOD);
    });

    $("#buttonAddQualiQcModeOfDefect").click(function(){
        let totalNumberOfQcMOD = 0;
        let ngQty = $('#formAddQualiDetails #txtQualiQcNgQty').val();
        let QcrowModeOfDefect = `
            <tr>
                <td>
                    <select class="form-control select2 select2bs4 selectMOD" name="mod_id[]">
                        <option value="0">N/A</option>
                    </select>
                </td>
                <td id=textQcMODQuantity>
                    <input type="number" class="form-control textQcMODQuantity" name="mod_quantity[]" value="1" min="1">
                </td>
                <td id="buttonRemoveMOD">
                    <center><button class="btn btn-md btn-danger buttonRemoveMOD" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
                </td>
            </tr>`;

        $("#tableQualiQcMOD tbody").append(QcrowModeOfDefect);

        getModeOfDefect($("#tableQualiQcMOD tr:last").find('.selectMOD'));
        getValidateTotalQcNgQty (ngQty,totalNumberOfQcMOD);
    });

    $("#tableQualiQcMOD").on('click', '.buttonRemoveMOD', function(){
        let totalNumberOfQcMOD = 0;
        let ngQty = $('#txtNgQuantity').val();

        $(this).closest ('tr').remove();
        getValidateTotalQcNgQty (ngQty,totalNumberOfQcMOD);
    });

    const getModeOfDefect = (elementId, modeOfDefectId = null) => {
        let result = `<option value="0" selected> N/A </option>`;
        $.ajax({
            url: 'get_mode_of_defect_for_prod',
            method: 'get',
            dataType: 'json',
            beforeSend: function(){
                result = `<option value="0" selected disabled> - Loading - </option>`;
                elementId.html(result);
            },
            success: function(response){
                // result = '';
                result = `<option value="0" selected disabled> Please Select Mode of Defect </option>`;
                if(response['data'].length > 0){
                    for(let index = 0; index < response['data'].length; index++){
                        result += `<option value="${response['data'][index].id}">${response['data'][index].defects}</option>`;
                    }
                }
                else{
                    result = `<option value="0" selected disabled> - No data found - </option>`;
                }
                elementId.html(result);
                if(modeOfDefectId != null){
                    elementId.val(modeOfDefectId).trigger('change');
                }
            },
            error: function(data, xhr, status){
                result = `<option value="0" selected disabled> - Reload Again - </option>`;
                elementId.html(result);
                console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }
        });
    }

    $(document).on('keyup','.textProdMODQuantity', function (e){
        let totalNumberOfProdMOD = 0;
        let ngQty = $('#txtQualiProdNgQty').val();
        let defectQty = $('.textProdMODQuantity').val();
        // console.log('defectQty', defectQty);

        getValidateTotalProdNgQty (ngQty,totalNumberOfProdMOD);
    });

    $(document).on('keyup','.textQcMODQuantity', function (e){
        let totalNumberOfQcMOD = 0;
        let ngQty = $('#txtQualiQcNgQty').val();
        let defectQty = $('.textQcMODQuantity').val();
        // console.log('defectQty', defectQty);

        getValidateTotalQcNgQty (ngQty,totalNumberOfQcMOD);
    });

    const getValidateTotalProdNgQty = function (ngQty,totalNumberOfProdMOD){
        $('#tableQualiProdMOD .textProdMODQuantity').each(function(){
            totalNumberOfProdMOD += parseInt($(this).val());
            if(totalNumberOfProdMOD > ngQty){
                $("#tableQualiProdMOD tbody").empty();
                $("#QualiProdlabelTotalNumberOfNG").text(parseInt(0));
            }
        });

        if(parseInt(ngQty) === totalNumberOfProdMOD){
            $('#QualiProdlabelTotalNumberOfNG').css({color: 'green'})
            $('#labelIsTallyProd').css({color: 'green'})
            $('#labelIsTallyProd').addClass('fa-thumbs-up')
            $('#labelIsTallyProd').removeClass('fa-thumbs-down')
            $('#labelIsTallyProd').attr('title','')
            // $("#btnAddRuncardStation").prop('disabled', false);
            $("#buttonAddQualiProdModeOfDefect").prop('disabled', true);
            $("#btnSaveQualificationDetails").prop('disabled', false);
        }else if(parseInt(ngQty) < totalNumberOfProdMOD){
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Defect Quantity cannot be more than the NG Quantity!",
                showConfirmButton: false,
                timer: 1500
            });
            totalNumberOfProdMOD = 0;
            $('#tableQualiProdMOD .textProdMODQuantity').val(0);
            $('#tableQualiProdMOD tbody').find('tr').remove();
            $("#buttonAddQualiProdModeOfDefect").prop('disabled', false);
            $("#btnSaveQualificationDetails").prop('disabled', true);
        }else if(parseInt(ngQty) > totalNumberOfProdMOD){
            console.log('Mode of Defect & NG Qty not tally!');
            $('#QualiProdlabelTotalNumberOfNG').css({color: 'red'})
            $('#labelIsTallyProd').css({color: 'red'})
            $('#labelIsTallyProd').addClass('fa-thumbs-down')
            $('#labelIsTallyProd').removeClass('fa-thumbs-up')
            $('#labelIsTallyProd').attr('title','Mode of Defect & NG Qty are not tally!')
            // $("#btnAddRuncardStation").prop('disabled', true);
            $("#buttonAddQualiProdModeOfDefect").prop('disabled', false);
            $("#btnSaveQualificationDetails").prop('disabled', true);
        }
        $("#QualiProdlabelTotalNumberOfNG").text(totalNumberOfProdMOD);
    }

    const getValidateTotalQcNgQty = function (ngQty,totalNumberOfQcMOD){
        $('#tableQualiQcMOD .textQcMODQuantity').each(function(){
            totalNumberOfQcMOD += parseInt($(this).val());
            if(totalNumberOfQcMOD > ngQty){
                $("#tableQualiQcMOD tbody").empty();
                $("#QualiQclabelTotalNumberOfNG").text(parseInt(0));
            }
        });

        console.log('qc mod qty', totalNumberOfQcMOD);

        if(parseInt(ngQty) === totalNumberOfQcMOD){
            $('#QualiQclabelTotalNumberOfNG').css({color: 'green'})
            $('#labelIsTallyQc').css({color: 'green'})
            $('#labelIsTallyQc').addClass('fa-thumbs-up')
            $('#labelIsTallyQc').removeClass('fa-thumbs-down')
            $('#labelIsTallyQc').attr('title','')
            // $("#btnAddRuncardStation").prop('disabled', false);
            $("#buttonAddQualiQcModeOfDefect").prop('disabled', true);
            $("#btnSaveQualificationDetails").prop('disabled', false);
        }else if(parseInt(ngQty) < totalNumberOfQcMOD){
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Defect Quantity cannot be more than the NG Quantity!",
                showConfirmButton: false,
                timer: 1500
            });
            totalNumberOfQcMOD = 0;
            $('#tableQualiQcMOD .textProdMODQuantity').val(0);
            $('#tableQualiQcMOD tbody').find('tr').remove();
            $("#buttonAddQualiQcModeOfDefect").prop('disabled', false);
            $("#btnSaveQualificationDetails").prop('disabled', true);
        }else if(parseInt(ngQty) > totalNumberOfQcMOD){
            console.log('Mode of Defect & NG Qty not tally!');
            $('#QualiQclabelTotalNumberOfNG').css({color: 'red'})
            $('#labelIsTallyQc').css({color: 'red'})
            $('#labelIsTallyQc').addClass('fa-thumbs-down')
            $('#labelIsTallyQc').removeClass('fa-thumbs-up')
            $('#labelIsTallyQc').attr('title','Mode of Defect & NG Qty are not tally!')
            // $("#btnAddRuncardStation").prop('disabled', true);
            $("#buttonAddQualiQcModeOfDefect").prop('disabled', false);
            $("#btnSaveQualificationDetails").prop('disabled', true);
        }
        $("#QualiQclabelTotalNumberOfNG").text(totalNumberOfQcMOD);
    }

    $(document).on('click', '.btnUpdateQualiData',function(e){
    // $(document).on('click', '#btnUpdateQualiData',function(e){
        e.preventDefault();
        let quali_id = $(this).attr('ipqc_data-id');

        $.ajax({
            url: "get_qualification_data",
            type: "get",
            data: {
                quali_id: quali_id,
            },
            dataType: "json",
            beforeSend: function(){
                // $('#formAddQualiDetails')[0].reset();
            },
            success: function(response){
                $('#modalQualiDetails').modal('show');
                // $('#formAddQualiDetails input[name="_token"]').val('{{ csrf_token() }}');
                let quali_data = response['quali_data'][0];
                // const quali_mod = response['quali_mode_of_defect'];
                const quali_mod_prod = response['quali_mod_prod'];
                const quali_mod_qc = response['quali_mod_qc'];

                $("#QualificationProdDiv").prop('hidden', false);
                $("#QualificationQcDiv").prop('hidden', false);
                $("#QualificationQcEngrDiv").prop('hidden', false);
                $("#QualificationQcEngr2Div").prop('hidden', false);

                frmQualifications.find('#txtQualiDetailsId').val(quali_data['id']);
                frmQualifications.find('#txtProdRuncardId').val(quali_data['fk_prod_runcard_id']);
                frmQualifications.find('#txtProductionLot').val(quali_data['production_lot']);
                frmQualifications.find('#txtProcessStatus').val(quali_data['process_status']);
                frmQualifications.find('#txtPoNumber').val(quali_data['po_number']);
                frmQualifications.find('#txtPoQty').val(quali_data['po_quantity']);
                frmQualifications.find('#txtPartName').val(quali_data['part_name']);
                frmQualifications.find('#txtPartCode').val(quali_data['part_code']);
                frmQualifications.find('#txtCategory').val(quali_data['prod_category']);

                frmQualifications.find('#txtQualiProdNameID').val(quali_data['prod_name']);
                frmQualifications.find('#txtQualiProdName').val(quali_data['prod_fname'] +' '+ quali_data['prod_lname']);
                frmQualifications.find('#txtQualiProdDate').val(quali_data['prod_date']);
                frmQualifications.find('#txtQualiProdInputQty').val(quali_data['prod_input_qty']);
                frmQualifications.find('#txtQualiProdNgQty').val(quali_data['prod_ng_qty']);
                frmQualifications.find('#txtQualiProdOutputQty').val(quali_data['prod_output_qty']);
                frmQualifications.find('#txtQualiProdJudgement').val(quali_data['prod_actual_sample_result']);
                frmQualifications.find('#txtQualiProdActualSample').val(quali_data['prod_actual_sample_used']);
                frmQualifications.find('#txtQualiProdRemarks').val(quali_data['prod_actual_sample_remarks']);

                if(quali_data['qc_name'] != null){
                    frmQualifications.find('#txtQualiQCNameID').val(quali_data['qc_name']);
                    frmQualifications.find('#txtQualiQCName').val(quali_data['qc_fname'] +' '+ quali_data['qc_lname']);
                }

                frmQualifications.find('#txtQualiQcDate').val(quali_data['qc_date']);
                frmQualifications.find('#txtQualiQcInputQty').val(quali_data['prod_output_qty']); //SET PROD Output Qty as QC Input Qty
                frmQualifications.find('#txtQualiQcInputQty').prop('readonly', true); //SET QC Input as Readonly
                frmQualifications.find('#txtCategory').prop('disabled', true); //SET QC Input as Readonly

                frmQualifications.find('#txtQualiQcNgQty').val(quali_data['qc_ng_qty']);
                frmQualifications.find('#txtQualiQcOutputQty').val(quali_data['qc_output_qty']);
                frmQualifications.find('#txtQualiQcJudgement').val(quali_data['qc_actual_sample_result']);
                frmQualifications.find('#txtQualiQcActualSample').val(quali_data['qc_actual_sample_used']);
                frmQualifications.find('#txtQualiQcRemarks').val(quali_data['qc_actual_sample_remarks']);

                frmQualifications.find('#txtCtHeightDataQc').val(quali_data['qc_ct_height_data']);
                frmQualifications.find('#txtCtHeightDataEngr').val(quali_data['engr_ct_height_data']);
                frmQualifications.find('#txtCtHeightDataRemarks').val(quali_data['engr_ct_height_data_remarks']);

                if(quali_data['defect_checkpoints'] == null || quali_data['defect_checkpoints'] == ""){
                    console.log('walasss');
                }else{
                    console.log('sadsad', quali_data['defect_checkpoints']);
                    let defect_checkpoints = quali_data['defect_checkpoints'].split(',');
                    // console.log('def', defect_checkpoints);
                    $.each(defect_checkpoints, function(key, value){
                        // console.log(value);
                        // $("#txtDefectCheckpoint option[value="+value+"]").prop('selected', true);
                        frmQualifications.find('#txtDefectCheckpoint option[value="0"]').prop('selected', false);
                        frmQualifications.find('#txtDefectCheckpoint option[value='+value+']').prop('selected', true).trigger('change')
                    });
                }

                // if(quali_data['defect_checkpoints'] != null){
                //     console.log('sadsad', quali_data['defect_checkpoints']);
                //     let defect_checkpoints = quali_data['defect_checkpoints'].split(',');
                //     // console.log('def', defect_checkpoints);
                //     $.each(defect_checkpoints, function(key, value){
                //         // console.log(value);
                //         // $("#txtDefectCheckpoint option[value="+value+"]").prop('selected', true);
                //         frmQualifications.find('#txtDefectCheckpoint option[value="0"]').prop('selected', false);
                //         frmQualifications.find('#txtDefectCheckpoint option[value='+value+']').prop('selected', true).trigger('change')
                //     });
                // }else{
                //     // frmQualifications.find('#txtDefectCheckpoint option[value="0"]').prop('selected', true);
                //     console.log('walasss');
                // }

                // if(quali_data['defect_checkpoints'] != ""){
                //     console.log('ditoo', quali_data['defect_checkpoints']);
                //     let defect_checkpoints = quali_data['defect_checkpoints'].split(',');
                //     // console.log('def', defect_checkpoints);
                //     $.each(defect_checkpoints, function(key, value){
                //         // console.log(value);
                //         // $("#txtDefectCheckpoint option[value="+value+"]").prop('selected', true);
                //         frmQualifications.find('#txtDefectCheckpoint option[value="0"]').prop('selected', false);
                //         frmQualifications.find('#txtDefectCheckpoint option[value='+value+']').prop('selected', true).trigger('change')
                //     });
                // }else{
                //     // frmQualifications.find('#txtDefectCheckpoint option[value="0"]').prop('selected', true);
                //     console.log('walasss');
                // }

                // let defect_checkpoints = quali_data['defect_checkpoints'].split(',');
                // // console.log('def', defect_checkpoints);
                // $.each(defect_checkpoints, function(key, value){
                //     $("#txtDefectCheckpoint option[value=0]").prop('selected', false);
                //     frmQualifications.find('#txtDefectCheckpoint option[value='+value+']').prop('selected', true).trigger('change')
                // });

                // ######################## PRODUCTION ########################### //
                for(let index = 0; index < quali_mod_prod.length; index++){
                    let ProdrowModeOfDefect = `
                        <tr>
                            <td>
                                <select class="form-control select2bs5 selectMOD" name="mod_id[]">
                                </select>
                            </td>
                            <td id=textProdMODQuantity>
                                <input type="number" class="form-control textProdMODQuantity" name="mod_quantity[]" value="${quali_mod_prod[index].mod_quantity}" min="1">
                            </td>
                            <td id="buttonRemoveMOD">
                                <center><button class="btn btn-md btn-danger buttonRemoveMOD" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
                            </td>
                        </tr>
                    `;
                    $("#tableQualiProdMOD tbody").append(ProdrowModeOfDefect);
                    getModeOfDefect($("#tableQualiProdMOD tr:last").find('.selectMOD'), quali_mod_prod[index].mod_id);
                }
                getValidateTotalProdNgQty(quali_data['prod_ng_qty'], 0);
                // $("#QualiProdlabelTotalNumberOfNG").text(parseInt(0));

                // ######################## QC ################################## //
                for(let index = 0; index < quali_mod_qc.length; index++){
                    let QcrowModeOfDefect = `
                        <tr>
                            <td>
                                <select class="form-control select2bs5 selectMOD" name="mod_id[]">
                                </select>
                            </td>
                            <td id=textQcMODQuantity>
                                <input type="number" class="form-control textQcMODQuantity" name="mod_quantity[]" value="${quali_mod_qc[index].mod_quantity}" min="1">
                            </td>
                            <td id="buttonRemoveMOD">
                                <center><button class="btn btn-md btn-danger buttonRemoveMOD" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
                            </td>
                        </tr>
                    `;
                    $("#tableQualiQcMOD tbody").append(QcrowModeOfDefect);
                    getModeOfDefect($("#tableQualiQcMOD tr:last").find('.selectMOD'), quali_mod_qc[index].mod_id);
                }
                getValidateTotalQcNgQty(quali_data['qc_ng_qty'], 0);
                // $("#QualiQclabelTotalNumberOfNG").text(parseInt(0));

                // DISABLE BUTTONS FOR MODE OF DEFECT //
                $('#tableQualiProdMOD .selectMOD').prop('disabled', true);
                $('#tableQualiProdMOD .textProdMODQuantity').prop('disabled', true);
                $('#tableQualiProdMOD .buttonRemoveMOD').prop('disabled', true);

                $('#tableQualiQcMOD .selectMOD').prop('disabled', true);
                $('#tableQualiQcMOD .textQcMODQuantity').prop('disabled', true);
                $('#tableQualiQcMOD .buttonRemoveMOD').prop('disabled', true);

                if(quali_data['process_status'] == 1){
                    $('#tableQualiProdMOD .selectMOD').prop('disabled', false);
                    $('#tableQualiProdMOD .textProdMODQuantity').prop('disabled', false);
                    $('#tableQualiProdMOD .buttonRemoveMOD').prop('disabled', false);
                }else if(quali_data['process_status'] == 2){
                    $('#tableQualiQcMOD .selectMOD').prop('disabled', false);
                    $('#tableQualiQcMOD .textQcMODQuantity').prop('disabled', false);
                    $('#tableQualiQcMOD .buttonRemoveMOD').prop('disabled', false);
                }

                frmQualifications.find('#txtDefectCheckpointRemarks').val(quali_data['defect_remarks']);

                //disabled and readonly
                $('#btnSaveQualificationDetails').prop('hidden', false)

                frmQualifications.find('#txtQualiProdNameID').prop('disabled', true)
                frmQualifications.find('#txtQualiProdName').prop('disabled', true)
                frmQualifications.find('#txtQualiProdDate').prop('disabled', true)
                frmQualifications.find('#txtQualiProdInputQty').prop('disabled', true)
                frmQualifications.find('#txtQualiProdNgQty').prop('disabled', true)
                frmQualifications.find('#txtQualiProdOutputQty').prop('disabled', true)
                frmQualifications.find('#txtQualiProdJudgement').prop('disabled', true)
                frmQualifications.find('#txtQualiProdActualSample').prop('disabled', true)
                frmQualifications.find('#txtQualiProdRemarks').prop('disabled', true)
                frmQualifications.find('#buttonAddQualiProdModeOfDefect').prop('disabled', true)

                frmQualifications.find('#txtQualiQCNameID').prop('disabled', true)
                frmQualifications.find('#txtQualiQCName').prop('disabled', true)
                frmQualifications.find('#txtQualiQcDate').prop('disabled', true)
                frmQualifications.find('#txtQualiQcInputQty').prop('disabled', true)
                frmQualifications.find('#txtQualiQcNgQty').prop('disabled', true)
                frmQualifications.find('#txtQualiQcOutputQty').prop('disabled', true)
                frmQualifications.find('#txtQualiQcJudgement').prop('disabled', true)
                frmQualifications.find('#txtQualiQcActualSample').prop('disabled', true)
                frmQualifications.find('#txtQualiQcRemarks').prop('disabled', true)
                frmQualifications.find('#buttonAddQualiQcModeOfDefect').prop('disabled', true)

                frmQualifications.find('#txtCtHeightDataQc').prop('disabled', true)
                frmQualifications.find('#txtCtHeightDataEngr').prop('disabled', true)
                frmQualifications.find('#txtCtHeightDataRemarks').prop('disabled', true)

                frmQualifications.find('#txtDefectCheckpoint').prop('disabled', true)
                frmQualifications.find('#txtDefectCheckpointRemarks').prop('disabled', true)

                if(quali_data['process_status'] == 1){
                    frmQualifications.find('#txtQualiProdNameID').prop('disabled', false)
                    frmQualifications.find('#txtQualiProdName').prop('disabled', false)
                    frmQualifications.find('#txtQualiProdDate').prop('disabled', false)
                    frmQualifications.find('#txtQualiProdInputQty').prop('disabled', false)
                    frmQualifications.find('#txtQualiProdNgQty').prop('disabled', false)
                    frmQualifications.find('#txtQualiProdOutputQty').prop('disabled', false)
                    frmQualifications.find('#txtQualiProdJudgement').prop('disabled', false)
                    frmQualifications.find('#txtQualiProdActualSample').prop('disabled', false)
                    frmQualifications.find('#txtQualiProdRemarks').prop('disabled', false)
                }else if(quali_data['process_status'] == 2){

                    frmQualifications.find('#txtQualiQCNameID').prop('disabled', false)
                    frmQualifications.find('#txtQualiQCName').prop('disabled', false)
                    frmQualifications.find('#txtQualiQcDate').prop('disabled', false)
                    frmQualifications.find('#txtQualiQcInputQty').prop('disabled', false)
                    frmQualifications.find('#txtQualiQcNgQty').prop('disabled', false)
                    frmQualifications.find('#txtQualiQcOutputQty').prop('disabled', false)
                    frmQualifications.find('#txtQualiQcJudgement').prop('disabled', false)
                    frmQualifications.find('#txtQualiQcActualSample').prop('disabled', false)
                    frmQualifications.find('#txtQualiQcRemarks').prop('disabled', false)
                    frmQualifications.find('#txtCtHeightDataQc').prop('disabled', false)
                    frmQualifications.find('#txtDefectCheckpoint').prop('disabled', false)
                    frmQualifications.find('#txtDefectCheckpointRemarks').prop('disabled', false)

                }else if(quali_data['process_status'] == 3 && quali_data['qc_ct_height_data'] == '3'){

                    frmQualifications.find('#txtQualiQCNameID').prop('disabled', false)
                    frmQualifications.find('#txtQualiQCName').prop('disabled', false)
                    frmQualifications.find('#txtQualiQcDate').prop('disabled', false)
                    frmQualifications.find('#txtQualiQcInputQty').prop('disabled', false)
                    frmQualifications.find('#txtQualiQcNgQty').prop('disabled', false)
                    frmQualifications.find('#txtQualiQcOutputQty').prop('disabled', false)
                    frmQualifications.find('#txtQualiQcJudgement').prop('disabled', false)
                    frmQualifications.find('#txtQualiQcActualSample').prop('disabled', false)
                    frmQualifications.find('#txtQualiQcRemarks').prop('disabled', false)

                    frmQualifications.find('#txtCtHeightDataQc').prop('disabled', false)
                    frmQualifications.find('#txtDefectCheckpoint').prop('disabled', false)
                    frmQualifications.find('#txtDefectCheckpointRemarks').prop('disabled', false)

                }else if(quali_data['process_status'] == 3 && quali_data['qc_ct_height_data'] != '3'){

                    frmQualifications.find('#txtCtHeightDataEngr').prop('disabled', false)
                    frmQualifications.find('#txtCtHeightDataRemarks').prop('disabled', false)
                }
            },
            error: function(data, xhr, status){
                toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }
        });
    });

    $("#modalQualiDetails").on('hidden.bs.modal', function () {
        // Reset form values
        $("#formAddQualiDetails")[0].reset();

        $("#tableQualiProdMOD tbody").empty();
        $("#QualiProdlabelTotalNumberOfNG").text(parseInt(0));

        $("#tableQualiQcMOD tbody").empty();
        $("#QualiQclabelTotalNumberOfNG").text(parseInt(0));

        $('#txtQualiDetailsId').val('');
        // Remove invalid & title validation
        $('div').find('input').removeClass('is-invalid');
        $("div").find('input').attr('title', '');
    });

    $('#txtSelectDocNoBDrawing').on('change', function() {
        if($('#txtSelectDocNoBDrawing').val() === null || $('#txtSelectDocNoBDrawing').val() === undefined){
            $("#btnViewBDrawings").prop('disabled', true);
        }else{
            $("#btnViewBDrawings").prop('disabled', false);
        }
    });

    $('#txtSelectDocNoInspStandard').on('change', function() {
        if($('#txtSelectDocNoInspStandard').val() === null || $('#txtSelectDocNoInspStandard').val() === undefined){
            $("#btnViewInspStdDrawings").prop('disabled', true);
        }else{
            $("#btnViewInspStdDrawings").prop('disabled', false);
        }
    });

    $('#txtSelectDocNoUD').on('change', function() {
        if($('#txtSelectDocNoUD').val() === null || $('#txtSelectDocNoUD').val() === undefined){
            $("#btnViewUdDrawings").prop('disabled', true);
        }else{
            $("#btnViewUdDrawings").prop('disabled', false);
        }
    });

    ViewDocument($('#txtSelectDocNoBDrawing').val(), $('#BDrawingDiv'), 'btnViewBDrawings');
    ViewDocument($('#txtSelectDocNoInspStandard').val(), $('#InspStandardDiv'), 'btnViewInspStdDrawings');
    ViewDocument($('#txtSelectDocNoUD').val(), $('#UDDiv'), 'btnViewUdDrawings');

    function GetBDrawingFromACDCS(doc_title, doc_type, cboElement, IpqcDocumentNo){
        GetDocumentNoFromACDCS(doc_title, doc_type, cboElement, IpqcDocumentNo);
    };

    function GetInspStandardFromACDCS(doc_title, doc_type, cboElement, IpqcDocumentNo){
        GetDocumentNoFromACDCS(doc_title, doc_type, cboElement, IpqcDocumentNo);
    };

    function GetUDFromACDCS(doc_title, doc_type, cboElement, IpqcDocumentNo){
        GetDocumentNoFromACDCS(doc_title, doc_type, cboElement, IpqcDocumentNo);
    };

    $('#btnViewBDrawings').on('click', function(){
        redirect_to_drawing($('#txtSelectDocNoBDrawing').val());
    });
    $('#btnViewInspStdDrawings').on('click', function(){
        redirect_to_drawing($('#txtSelectDocNoInspStandard').val());
    });
    $('#btnViewUdDrawings').on('click', function(){
        redirect_to_drawing($('#txtSelectDocNoUD').val());
    });

    function ViewDocument(document_no, div_id, btn_id){
        let doc_no ='<button type="button" id="'+btn_id+'" class="btn btn-primary">';
            doc_no +=     '<i class="fa fa-file" data-bs-toggle="tooltip" data-bs-html="true" title="See Document in ACDCS"></i>';
            doc_no +='</button>';
        div_id.append(doc_no);
    }

    function redirect_to_drawing(drawing) {
        console.log('Drawing No.:',drawing)
        if( drawing  == 'N/A'){
            alert('Document No is Not Existing')
        }
        else{
            window.open("http://rapid/ACDCS/prdn_home_pats_ppd?doc_no="+drawing)
        }
    }

    function GetDocumentNoFromACDCS(doc_title, doc_type, cboElement, IpqcDocumentNo = null){
        let result = '<option value="" disabled selected>--Select Document No.--</option>';

        $.ajax({
            url: 'get_data_from_acdcs',
            method: 'get',
            data: {
                'doc_title': doc_title,
                'doc_type': doc_type
            },
            dataType: 'json',
            beforeSend: function() {
                    result = '<option value="0" disabled selected>--Loading--</option>';
                    cboElement.html(result);
            },
            success: function(response) {
                if (response['acdcs_data'].length > 0) {
                        result = '<option value="" disabled selected>--Select Document No.--</option>';
                    if(response['acdcs_data'][0].doc_type != 'B Drawing'){
                        result += '<option value="N/A"> N/A </option>';
                    }
                    for (let index = 0; index < response['acdcs_data'].length; index++) {
                        result += '<option value="' + response['acdcs_data'][index].doc_no + '">' + response['acdcs_data'][index].doc_no + '</option>';
                    }
                } else {
                    result = '<option value="N/A"> N/A </option>';
                    result += '<option value="0" selected disabled> -- No record found -- </option>';
                }
                cboElement.html(result);
                if(IpqcDocumentNo != null){
                    cboElement.val(IpqcDocumentNo).trigger('change');
                }
            },
            error: function(data, xhr, status) {
                result = '<option value="0" selected disabled> -- Reload Again -- </option>';
                cboElement.html(result);
                console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }
        });
    }

    $('#txtOkSamples, #txtQcSamples').each(function(e){
        $(this).keyup(function(e){
            let ng_value = $('#txtQcSamples').val() - $('#txtOkSamples').val();
            $('#txtNGQty').val(ng_value);
        });
    });

    $(document).on('click', '.btnSubmitIPQCData', function(e){
        let quali_id = $(this).attr('ipqc_data-id');

        $.ajax({
            type: "get",
            url: "get_qualification_data",
            data: {
                quali_id: quali_id,
            },
            dataType: "json",
            success: function (response) {
                let quali_data = response['quali_data'][0];
                $("#cnfrmtxtQualiID").val(quali_data.id);
                $("#cnfrmtxtQualiProdLot").val(quali_data.production_lot);
                $("#cnfrmtxtQualiStatus").val(quali_data.status);
                $("#cnfrmtxtQualiProcessStatus").val(quali_data.process_status);
            }
        });

        $("#modalConfirmSubmitIPQCInspection").modal('show');
    });

    $("#FrmConfirmSubmitIPQCInspection").submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: "update_qualification_details_status",
            method: "post",
            data: $('#FrmConfirmSubmitIPQCInspection').serialize(),
            dataType: "json",
            success: function (response) {
                let result = response['result'];
                if (result == 'Successful') {
                    dtQualificationPending.draw();
                    dtQualificationCompleted.draw();
                    dtQualificationResetup.draw();
                    toastr.success('Successful!');
                    $("#modalConfirmSubmitIPQCInspection").modal('hide');
                }else{
                    toastr.error('Error!, Please Contanct ISS Local 208');
                }
            }
        });
    });

    // $('#formAddQualiDetails').submit(function(e){
    //     e.preventDefault();
    //     // AddIpqcInspection()
    //     $('#modalScanQRSave').modal('show');
    // });

    $('#btnSaveQualificationDetails').click(function(e){
        e.preventDefault();
        // AddIpqcInspection()
        $('#modalScanQRSave').modal('show');
    });

    $(document).on('keyup','#txtScanUserId', function(e){
        if(e.keyCode == 13){
            let auth_position;

            if($('#txtProcessStatus').val() == 1){
                auth_position = [0, 1, 4, 12, 13, 14];
            }else{
                auth_position = [0, 2, 5];
            }

            validateUser($(this).val(), auth_position, function(result){
                if(result == true){
                    AddIpqcInspection();
                }else{ // Error Handler
                    toastr.error('User not authorize!');
                }
            });
            $(this).val('');
        }
    });

    function AddIpqcInspection(){
        let formData = new FormData($('#formAddQualiDetails')[0]);
        console.log('formdata', formData);
        $.ajax({
            url: "add_qualification_details",
            method: "post",
            // data: formData,
            data: $('#formAddQualiDetails').serialize(),
            // processData: false,
            // contentType: false,
            dataType: "json",
            beforeSend: function(){
            },
            success: function (response) {
                let result = response['result'];
                console.log(response);
                // if (result == 'Insert Successful' || result == 'Update Successful') {
                if(response['validation'] == 'hasError'){
                    toastr.error('Saving failed!, Please complete all required fields');
                    if(response['error']['production_lot'] === undefined) {
                        $("#txtProductionLot").removeClass('is-invalid');
                        $("#txtProductionLot").attr('title', '');
                    }else{
                        $("#txtProductionLot").addClass('is-invalid');
                        $("#txtProductionLot").attr('title', response['error']['production_lot']);
                    }

                    //     // $("#txtPoNumber").addClass('is-invalid');
                    //     // $("#txtPoNumber").attr('title', response['error']['po_number']);

                    //     // $("#txtPoQty").addClass('is-invalid');
                    //     // $("#txtPoQty").attr('title', response['error']['po_number']);

                    //     // $("#txtPartName").addClass('is-invalid');
                    //     // $("#txtPartName").attr('title', response['error']['po_number']);

                    //     // $("#txtPartCode").addClass('is-invalid');
                    //     // $("#txtPartCode").attr('title', response['error']['po_number']);
                    //     $('#modalScanQRSave').modal('hide');
                    // }

                    if(response['error']['category'] === undefined){
                        $("#txtCategory").removeClass('is-invalid');
                        $("#txtCategory").attr('title', '');
                    }else{
                        $("#txtCategory").addClass('is-invalid');
                        $("#txtCategory").attr('title', response['error']['category']);
                    }

                    if(response['error']['quali_prod_date'] === undefined){
                        $("#txtQualiProdDate").removeClass('is-invalid');
                        $("#txtQualiProdDate").attr('title', '');
                    }else{
                        $("#txtQualiProdDate").addClass('is-invalid');
                        $("#txtQualiProdDate").attr('title', response['error']['quali_prod_date']);
                    }

                    if(response['error']['quali_prod_input_qty'] === undefined){
                        $("#txtQualiProdInputQty").removeClass('is-invalid');
                        $("#txtQualiProdInputQty").attr('title', '');
                    }else{
                        $("#txtQualiProdInputQty").addClass('is-invalid');
                        $("#txtQualiProdInputQty").attr('title', response['error']['quali_prod_input_qty']);
                    }

                    if(response['error']['quali_prod_output_qty'] === undefined){
                        $("#txtQualiProdOutputQty").removeClass('is-invalid');
                        $("#txtQualiProdOutputQty").attr('title', '');
                    }else{
                        $("#txtQualiProdOutputQty").addClass('is-invalid');
                        $("#txtQualiProdOutputQty").attr('title', response['error']['quali_prod_output_qty']);
                    }

                    if(response['error']['quali_prod_judgement'] === undefined){
                        $("#txtQualiProdJudgement").removeClass('is-invalid');
                        $("#txtQualiProdJudgement").attr('title', '');
                    }else{
                        $("#txtQualiProdJudgement").addClass('is-invalid');
                        $("#txtQualiProdJudgement").attr('title', response['error']['quali_prod_judgement']);
                    }

                    if(response['error']['quali_prod_actual_sample'] === undefined){
                        $("#txtQualiProdActualSample").removeClass('is-invalid');
                        $("#txtQualiProdActualSample").attr('title', '');
                    }else{
                        $("#txtQualiProdActualSample").addClass('is-invalid');
                        $("#txtQualiProdActualSample").attr('title', response['error']['quali_prod_actual_sample']);
                    }

                    $('#modalScanQRSave').modal('hide');
                }else if (result == 'Successful!'){
                    toastr.success('Successful!');
                    $('#modalQualiDetails').modal('hide');
                    $('#modalScanQRSave').modal('hide');
                    $('#btnAddRuncardStation').removeClass('d-none');
                    $('#btnAddQualificationData').addClass('d-none');

                    dtQualificationPending.draw();
                    dtQualificationCompleted.draw();
                    dtQualificationResetup.draw();
                }
                else if(result == 'Duplicate'){
                    toastr.error('Request Already Submitted!');
                }
                else if(result == 'Session Expired') {
                    toastr.error('Session Expired!, Please Log-in again');
                }else if(result == 'Error'){
                    toastr.error('Error!, Please Contanct ISS Local 208');
                }
                frmQualifications.find("#txtSelectSecondMoldingDevice").val(0).trigger('change');
            },
            error: function(data, xhr, status){
                toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }
        });
    };
    // NEW CODE CLARK 02042024 END

    // btnViewQualiData
    $(document).on('click', '.btnViewQualiData',function(e){
        e.preventDefault();
        let quali_id = $(this).attr('ipqc_data-id');
        $.ajax({
            url: "get_qualification_data",
            type: "get",
            data: {
                quali_id: quali_id,
            },
            dataType: "json",
            beforeSend: function(){
                // $('#formAddQualiDetails')[0].reset();
            },
            success: function(response){
                $('#modalQualiDetails').modal('show');
                // $('#formAddQualiDetails input[name="_token"]').val('{{ csrf_token() }}');
                let quali_data = response['quali_data'][0];
                const quali_mod_prod = response['quali_mod_prod'];
                const quali_mod_qc = response['quali_mod_qc'];

                frmQualifications.find('#txtQualiDetailsId').val(quali_data['id']);
                frmQualifications.find('#txtProdRuncardId').val(quali_data['fk_prod_runcard_id']);
                frmQualifications.find('#txtProductionLot').val(quali_data['production_lot']);
                frmQualifications.find('#txtProcessStatus').val(quali_data['process_status']);
                frmQualifications.find('#txtPoNumber').val(quali_data['po_number']);
                frmQualifications.find('#txtPoQty').val(quali_data['po_quantity']);
                frmQualifications.find('#txtPartName').val(quali_data['part_name']);
                frmQualifications.find('#txtPartCode').val(quali_data['part_code']);
                frmQualifications.find('#txtCategory').val(quali_data['prod_category']);

                frmQualifications.find('#txtQualiProdNameID').val(quali_data['prod_name']);
                frmQualifications.find('#txtQualiProdName').val(quali_data['prod_fname'] +' '+ quali_data['prod_lname']);
                frmQualifications.find('#txtQualiProdDate').val(quali_data['prod_date']);
                frmQualifications.find('#txtQualiProdInputQty').val(quali_data['prod_input_qty']);
                frmQualifications.find('#txtQualiProdNgQty').val(quali_data['prod_ng_qty']);
                frmQualifications.find('#txtQualiProdOutputQty').val(quali_data['prod_output_qty']);
                frmQualifications.find('#txtQualiProdJudgement').val(quali_data['prod_actual_sample_result']);
                frmQualifications.find('#txtQualiProdActualSample').val(quali_data['prod_actual_sample_used']);
                frmQualifications.find('#txtQualiProdRemarks').val(quali_data['prod_actual_sample_remarks']);

                frmQualifications.find('#txtQualiQCNameID').val(quali_data['qc_name']);
                frmQualifications.find('#txtQualiQCName').val(quali_data['qc_fname'] +' '+ quali_data['qc_lname']);
                frmQualifications.find('#txtQualiQcDate').val(quali_data['qc_date']);
                frmQualifications.find('#txtQualiQcInputQty').val(quali_data['qc_input_qty']);
                frmQualifications.find('#txtQualiQcNgQty').val(quali_data['qc_ng_qty']);
                frmQualifications.find('#txtQualiQcOutputQty').val(quali_data['qc_output_qty']);
                frmQualifications.find('#txtQualiQcJudgement').val(quali_data['qc_actual_sample_result']);
                frmQualifications.find('#txtQualiQcActualSample').val(quali_data['qc_actual_sample_used']);
                frmQualifications.find('#txtQualiQcRemarks').val(quali_data['qc_actual_sample_remarks']);

                frmQualifications.find('#txtCtHeightDataQc').val(quali_data['qc_ct_height_data']);
                frmQualifications.find('#txtCtHeightDataEngr').val(quali_data['engr_ct_height_data']);
                frmQualifications.find('#txtCtHeightDataRemarks').val(quali_data['engr_ct_height_data_remarks']);

                if(quali_data['defect_checkpoints'] != null){
                    console.log('ditoosoadsad');
                    let defect_checkpoints = quali_data['defect_checkpoints'].split(',');
                    // console.log('def', defect_checkpoints);
                    $.each(defect_checkpoints, function(key, value){
                        // console.log(value);
                        // $("#txtDefectCheckpoint option[value="+value+"]").prop('selected', true);
                        frmQualifications.find('#txtDefectCheckpoint option[value="0"]').prop('selected', false);
                        frmQualifications.find('#txtDefectCheckpoint option[value='+value+']').prop('selected', true).trigger('change')
                    });
                }else{
                    // frmQualifications.find('#txtDefectCheckpoint option[value="0"]').prop('selected', true);
                    console.log('walasss');
                }

                // ######################## PRODUCTION ########################### //
                for(let index = 0; index < quali_mod_prod.length; index++){
                    let ProdrowModeOfDefect = `
                        <tr>
                            <td>
                                <select class="form-control select2bs5 selectMOD" name="mod_id[]">
                                </select>
                            </td>
                            <td id=textProdMODQuantity>
                                <input type="number" class="form-control textProdMODQuantity" name="mod_quantity[]" value="${quali_mod_prod[index].mod_quantity}" min="1">
                            </td>
                            <td id="buttonRemoveMOD">
                                <center><button class="btn btn-md btn-danger buttonRemoveMOD" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
                            </td>
                        </tr>
                    `;
                    $("#tableQualiProdMOD tbody").append(ProdrowModeOfDefect);
                    getModeOfDefect($("#tableQualiProdMOD tr:last").find('.selectMOD'), quali_mod_prod[index].mod_id);
                }
                // getValidateTotalProdNgQty(quali_data['prod_ng_qty'], 0);
                $("#QualiProdlabelTotalNumberOfNG").text(parseInt(quali_data['prod_ng_qty']));

                // ######################## QC ################################## //
                for(let index = 0; index < quali_mod_qc.length; index++){
                    let QcrowModeOfDefect = `
                        <tr>
                            <td>
                                <select class="form-control select2bs5 selectMOD" name="mod_id[]">
                                </select>
                            </td>
                            <td id=textQcMODQuantity>
                                <input type="number" class="form-control textQcMODQuantity" name="mod_quantity[]" value="${quali_mod_qc[index].mod_quantity}" min="1">
                            </td>
                            <td id="buttonRemoveMOD">
                                <center><button class="btn btn-md btn-danger buttonRemoveMOD" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
                            </td>
                        </tr>
                    `;
                    $("#tableQualiQcMOD tbody").append(QcrowModeOfDefect);
                    getModeOfDefect($("#tableQualiQcMOD tr:last").find('.selectMOD'), quali_mod_qc[index].mod_id);
                }
                // getValidateTotalQcNgQty(quali_data['qc_ng_qty'], 0);
                $("#QualiQclabelTotalNumberOfNG").text(parseInt(quali_data['qc_ng_qty']));

                // DISABLE BUTTONS FOR MODE OF DEFECT //
                $('#tableQualiProdMOD .selectMOD').prop('disabled', true);
                $('#tableQualiProdMOD .textProdMODQuantity').prop('disabled', true);
                $('#tableQualiProdMOD .buttonRemoveMOD').prop('disabled', true);

                $('#tableQualiQcMOD .selectMOD').prop('disabled', true);
                $('#tableQualiQcMOD .textQcMODQuantity').prop('disabled', true);
                $('#tableQualiQcMOD .buttonRemoveMOD').prop('disabled', true);

                if(quali_data['process_status'] == 1){
                    $('#tableQualiProdMOD .selectMOD').prop('disabled', false);
                    $('#tableQualiProdMOD .textProdMODQuantity').prop('disabled', false);
                    $('#tableQualiProdMOD .buttonRemoveMOD').prop('disabled', false);
                }else if(quali_data['process_status'] == 2){
                    $('#tableQualiQcMOD .selectMOD').prop('disabled', false);
                    $('#tableQualiQcMOD .textQcMODQuantity').prop('disabled', false);
                    $('#tableQualiQcMOD .buttonRemoveMOD').prop('disabled', false);
                }

                // frmQualifications.find('#txtDefectCheckpoint').val(quali_data['defect_checkpoints']);
                frmQualifications.find('#txtDefectCheckpointRemarks').val(quali_data['defect_remarks']);

                //disabled and readonly
                $("#btnSaveQualificationDetails").prop('hidden', true);

                frmQualifications.find('#txtQualiProdNameID').prop('disabled', true)
                frmQualifications.find('#txtQualiProdName').prop('disabled', true)
                frmQualifications.find('#txtQualiProdDate').prop('disabled', true)
                frmQualifications.find('#txtQualiProdInputQty').prop('disabled', true)
                frmQualifications.find('#txtQualiProdNgQty').prop('disabled', true)
                frmQualifications.find('#txtQualiProdOutputQty').prop('disabled', true)
                frmQualifications.find('#txtQualiProdJudgement').prop('disabled', true)
                frmQualifications.find('#txtQualiProdActualSample').prop('disabled', true)
                frmQualifications.find('#txtQualiProdRemarks').prop('disabled', true)
                frmQualifications.find('#buttonAddQualiProdModeOfDefect').prop('disabled', true)

                frmQualifications.find('#txtQualiQCNameID').prop('disabled', true)
                frmQualifications.find('#txtQualiQCName').prop('disabled', true)
                frmQualifications.find('#txtQualiQcDate').prop('disabled', true)
                frmQualifications.find('#txtQualiQcInputQty').prop('disabled', true)
                frmQualifications.find('#txtQualiQcNgQty').prop('disabled', true)
                frmQualifications.find('#txtQualiQcOutputQty').prop('disabled', true)
                frmQualifications.find('#txtQualiQcJudgement').prop('disabled', true)
                frmQualifications.find('#txtQualiQcActualSample').prop('disabled', true)
                frmQualifications.find('#txtQualiQcRemarks').prop('disabled', true)
                frmQualifications.find('#buttonAddQualiQcModeOfDefect').prop('disabled', true)

                frmQualifications.find('#txtCtHeightDataQc').prop('disabled', true)
                frmQualifications.find('#txtCtHeightDataEngr').prop('disabled', true)
                frmQualifications.find('#txtCtHeightDataRemarks').prop('disabled', true)

                frmQualifications.find('#txtDefectCheckpoint').prop('disabled', true)
                frmQualifications.find('#txtDefectCheckpointRemarks').prop('disabled', true)
            },
            error: function(data, xhr, status){
                toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }
        });
    });

    // ================================= RE-UPLOAD FILE =================================
    $('#btnReuploadTrigger').on('click', function() {
        $('#btnReuploadTrigger').attr('checked', 'checked');
        if($(this).is(":checked")){
            $("#txtAddFile").removeClass('d-none');
            $("#txtAddFile").attr('required', true);
            $("#txtEditUploadedFile").addClass('d-none');
            $("#download_file").addClass('d-none');
        }
        else{
            $("#txtAddFile").addClass('d-none');
            $("#txtAddFile").removeAttr('required');
            $("#txtAddFile").val('');
            $("#txtEditUploadedFile").removeClass('d-none');
            $("#download_file").removeClass('d-none');
        }
    });
});
