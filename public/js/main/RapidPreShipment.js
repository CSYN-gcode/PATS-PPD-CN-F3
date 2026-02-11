function GetDestinations(cboElement){
    let result = '<option value="" disabled selected> Select Destination </option>';
    $.ajax({
        type: "get",
        url: "get_preshipment_destination",
        dataType: "json",
        beforeSend: function(){
            result = '<option value="0" disabled selected>--Loading--</option>';
        },
        success: function (response) {
            let Destinations = response['result'];
            if(Destinations.length > 0) {
                    result = '<option value="" disabled selected> Select Destination </option>';
                for (let index = 0; index < Destinations.length; index++) {
                    result += '<option value="' + Destinations[index].destination + '">' + Destinations[index].destination + '</option>';
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

function GetControlNo(cboElement){
    let result = '<option value="" disabled selected> Select Control No </option>';
    $.ajax({
        type: "get",
        url: "get_control_numbers",
        dataType: "json",
        beforeSend: function(){
            result = '<option value="0" disabled selected>--Loading--</option>';
        },
        success: function (response) {
            let ControlNo = response['result'];
            if(ControlNo.length > 0) {
                    result = '<option value="" disabled selected> Select Control No </option>';
                for (let index = 0; index < ControlNo.length; index++) {
                    result += '<option value="' + ControlNo[index].control_no + '">' + ControlNo[index].control_no + '</option>';
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

const getUsers = (cboElement, position) => {
    $.ajax({
        type: "get",
        url: "get_users_by_pos",
        data: {
            'position': position,
        },
        dataType: "json",
        success: function(response){
            let user_details = response['users'];
            if(user_details.length > 0){
                    result = '<option value="" disabled selected> Select Name </option>';
                for (let i = 0; i < user_details.length; i++) {
                    result += '<option value="'+user_details[i].id+'">'+user_details[i].full_name+'</option>';
                }
            }else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const getPoNoByPreShipId = (cboElement, preShipId) => {
    $.ajax({
        type: "get",
        url: "get_preshipment_by_id",
        data: {
            'pre_shipment_id': preShipId,
        },
        dataType: "json",
        success: function(response){
            let preship_details = response['result'];
            if(preship_details.length > 0){
                    result = '<option value="" disabled selected> Select PO No </option>';
                for (let i = 0; i < preship_details.length; i++) {
                    result += '<option value="'+preship_details[i].po_no+'">'+preship_details[i].po_no+'</option>';
                }
            }else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const getLotNoByPreShipId = (cboElement, preShipId) => {
    $.ajax({
        type: "get",
        url: "get_preshipment_by_id",
        data: {
            'pre_shipment_id': preShipId,
        },
        dataType: "json",
        success: function(response){
            let preship_details = response['result'];
            if(preship_details.length > 0){
                    result = '<option value="" disabled selected> Select Lot No </option>';
                for (let i = 0; i < preship_details.length; i++) {
                    result += '<option value="'+preship_details[i].lot_no+'">'+preship_details[i].lot_no+'</option>';
                }
            }else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

// function GetPOFromPPSDB(cboElement, device_name, PoNumber = null){
//     let result = '<option value="" disabled selected> Select PO Number </option>';
//     $.ajax({
//         method: "get",
//         url: "get_po_from_ppsdb",
//         data: {
//             'device_name': device_name,
//             'po_number': PoNumber,
//         },
//         dataType: "json",
//         beforeSend: function(){
//             result = '<option value="0" disabled selected>--Loading--</option>';
//         },
//         success: function (response) {
//             let po_details = response['po_details'];
//             if(po_details.length > 0) {
//                     result = '<option value="" disabled selected> Select PO Number </option>';
//                 for (let index = 0; index < po_details.length; index++) {
//                     if(po_details[index]['po_number'] == PoNumber || po_details[index]['po_quantity'] > 0){
//                         result += '<option value="' + po_details[index]['po_number'] + '">' + po_details[index]['po_number'] + '</option>';
//                     }
//                 }
//             }else{
//                 result = '<option value="0" selected disabled> -- No record found -- </option>';
//             }
//             cboElement.html(result);
//             if(PoNumber != null){
//                 cboElement.val(PoNumber).trigger('change');
//                 // GetPPSDBDataByPO(PoNumber, device_name, 1);
//             }
//         },
//         error: function(data, xhr, status) {
//             result = '<option value="0" selected disabled> -- Reload Again -- </option>';
//             cboElement.html(result);
//             console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//         }
//     });
// }

// const getPOFromDeliveryUpdate = (cboElement, PoNumber) => {
//     // console.log('getPOFromDeliveryUpdate', PoNumber);

//     let result = '<option value="" disabled selected> Select PO Number </option>';
//     $.ajax({
//         type: "get",
//         url: "get_po_from_delivery_update",
//         dataType: "json",
//         beforeSend: function(){
//             result = `<option value="0" selected disabled> - Loading - </option>`;
//         },
//         success: function (response) {
//             let po_details = response['po_details'];
//             if(po_details.length > 0) {
//                     result = '<option value="" disabled selected> Select PO Number </option>';
//                 for (let i = 0; i < po_details.length; i++) {
//                     result += '<option value="'+po_details[i].po_no+'">'+po_details[i].po_no+'</option>';
//                 }
//             }else{
//                 result = '<option value="0" selected disabled> -- No record found -- </option>';
//             }
//             cboElement.html(result);
//             if(PoNumber != null){
//                 console.log('po found', PoNumber);
//                 cboElement.val(PoNumber).trigger('change');
//             }
//             // ✅ Call the callback after populating the dropdown
//             // if ($.isFunction(callback)) {
//             //     callback();
//             // }
//         },
//         error: function(data, xhr, status) {
//             result = '<option value="0" selected disabled> -- Reload Again -- </option>';
//             cboElement.html(result);
//             console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//         }
//     });
// }

const getDestination = (cboElement1, PoNumber = null) => {
    // console.log('getPOFromDeliveryUpdate', PoNumber);

    let result = '<option value="" disabled selected> Select PO Number </option>';
    $.ajax({
        type: "get",
        url: "get_po_from_delivery_update",
        dataType: "json",
        beforeSend: function(){
            result = `<option value="0" selected disabled> - Loading - </option>`;
        },
        success: function (response) {
            let po_details = response['po_details'];
            if(po_details.length > 0) {
                    result = '<option value="" disabled selected> Select PO Number </option>';
                for (let i = 0; i < po_details.length; i++) {
                    result += `<option value="${po_details[i].po_no}">${po_details[i].po_no}</option>`;
                }
            }else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            cboElement1.html(result);
            if(PoNumber != null) {
                $('#txtPONumber').find('option[value="'+PoNumber+'"]').attr('selected', 'selected');
            }
        },
        error: function(data, xhr, status) {
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement1.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const getPOFromDeliveryUpdate = (cboElement1, PoNumber = null) => {
    // console.log('getPOFromDeliveryUpdate', PoNumber);

    let result = '<option value="" disabled selected> Select PO Number </option>';
    $.ajax({
        type: "get",
        url: "get_po_from_delivery_update",
        dataType: "json",
        beforeSend: function(){
            result = `<option value="0" selected disabled> - Loading - </option>`;
        },
        success: function (response) {
            let po_details = response['po_details'];
            if(po_details.length > 0) {
                    result = '<option value="" disabled selected> Select PO Number </option>';
                for (let i = 0; i < po_details.length; i++) {
                    result += `<option value="${po_details[i].po_no}">${po_details[i].po_no}</option>`;
                }
            }else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            cboElement1.html(result);
            if(PoNumber != null) {
                $('#txtPONumber').find('option[value="'+PoNumber+'"]').attr('selected', 'selected');
            }
        },
        error: function(data, xhr, status) {
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement1.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

$(document).ready(function(){

    getUsers($('#txtWeighedBy'), '0,1,2,3,4,5');
    getUsers($('#txtPackedBy'), '0,1,2,3,4,5');
    getUsers($('#txtCheckedBy'), '0,1,2,3,4,5');
    getPOFromDeliveryUpdate($('#txtPONumber'));

    // Apply Select2 to all select elements inside any modal dynamically
    $('.modal').on('shown.bs.modal', function () {
        $(this).find('.select2bs5').each(function(){
            $(this).select2({
                theme: 'bootstrap-5',
                dropdownParent: $(this).closest('.modal') // Ensures correct parent modal
            });
        });
    });

    $('#txtSelectControlNumber').on('change', function(e){
        let controlNo = $('#txtSelectControlNumber').val();
        $.ajax({
            type: "get",
            url: "get_control_numbers",
            data: {
                "control_no" : controlNo
            },
            dataType: "json",
            beforeSend: function(){
            },
            success: function (response) {
                let control_no_details = response['result'];

                // if(control_no_details == 0 && material_details == 0){
                //     toastr.error('No Process Found!, Please Insert Process');
                //     // $('#btnAddPreShipment').prop('disabled', true);
                // }else{
                    $('#txtSearchShipmentDate').val(control_no_details[0].shipment_date);
                    dtPreShipment.draw();
                // }
            }
        });
    });

    dtPreShipment = $("#tblPreShipment").DataTable({
        "processing" : true,
        "serverSide" : true,
        "lengthMenu": [ [25, -1], [25, "All"] ],
        "ajax" : {
            url: "view_pre_shipment",
            data: function (param){
                param.control_no = $("#txtSelectControlNumber").val();
            }
        },
        fixedHeader: true,
        "columns":[
            { "data" : "id", searchable:false,visible:false },
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "status" },
            { "data" : "date" },
            { "data" : "station" },
            { "data" : "control_no" },
            { "data" : "shipment_date" },
            { "data" : "destination" },
        ],
        "columnDefs": [
            {"className": "dt-center", "targets": "_all"},
            {
                "targets": [2],
                "data": null,
                "defaultContent": "---"
            },
        ],
        "order": [0, 'desc']
    });

    dtPreShipmentDetials = $("#tblPreShipmentDetails").DataTable({
        "processing" : true,
        "serverSide" : true,
        "ajax" : {
            url: "view_pre_shipment_details",
            data: function (param){
                param.pre_shipment_id = $('#formPreshipment #txtFrmDataPreShipmentId').val();
            },
        },
        fixedHeader: true,
        "columns":[
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "status" },
            { "data" : "master_carton_no" },
            { "data" : "item_no" },
            { "data" : "po_no" },
            { "data" : "parts_code" },
            { "data" : "device_name" },
            { "data" : "lot_no" },
            { "data" : "qty" },
            { "data" : "package_category" },
            { "data" : "package_qty" },
            { "data" : "weighed_by" },
            { "data" : "packed_by" },
            { "data" : "checked_by" },
            { "data" : "remarks" },
        ],
        "columnDefs": [
            {"className": "dt-center", "targets": "_all"},
            {
                "targets": [2],
                "data": null,
                "defaultContent": "---"
            },
        ],
    });

    dtPOSearchResult = $("#tblPOSearchResult").DataTable({
        "processing" : true,
        "serverSide" : true,
        "ajax" : {
            url: "view_search_po_result",
            data: function (param){
                param.po_number = $('#formAddPreShipmentDetails #txtPONumber').val();
                param.pre_ship_details_id = $('#formAddPreShipmentDetails #txtPreShipmentDetailsId').val();
            },
        },
        fixedHeader: true,
        "columns":[
            { "data" : "checkbox", searchable: false, orderable: false },
            { "data" : "row_master_carton_no" },
            { "data" : "item_no" },
            { "data" : "lot_numbers" },
            { "data" : "quantities" },
            { "data" : "row_package_cat" },
            { "data" : "row_package_qty" },
            { "data" : "row_remarks" },
            { "data" : "remove_btn" },
        ],
        "columnDefs": [
            {"className": "dt-center", "targets": "_all"},
            {
                "targets": [2],
                "data": null,
                "defaultContent": "---"
            },
        ],
        "drawCallback": function(settings) {
        // For each row in the table body
        $('#tblPOSearchResult tbody tr').each(function() {
            // Get all cells in the row
            let cells = $(this).find('td');

            // Enable inputs in column 0 (checkbox) and 4 (actual_so)
            cells.eq(0).find('input').prop('disabled', false); // Checkbox
            cells.eq(8).find('input').prop('disabled', false); // actual_so

            // Disable inputs in columns 1, 2, 3 (indices 1, 2, 3)
            cells.eq(1).find('input').prop('disabled', true);
            cells.eq(2).find('input').prop('disabled', true);
            cells.eq(3).find('input').prop('readonly', true);
            cells.eq(4).find('input').prop('readonly', true);
            cells.eq(5).find('input').prop('disabled', true);
            cells.eq(6).find('input').prop('disabled', true);
            cells.eq(7).find('input').prop('disabled', true);
        });
    }
    });

    // CHECK ALL ITEMS
    $("#tblPOSearchResult #chkAllItems").click(function(){
        if($(this).prop('checked')) {
            $(".itemCheckbox").prop('checked', 'checked');//check all result
            // $('#tblPOSearchResult').find('tr input[type="text"], input[type="number"]').prop('disabled', false);
            $('#tblPOSearchResult').find('.classToDisable').prop('disabled', false);
            $('#tblPOSearchResult').find('.lockReadOnly').prop('readonly', true);
            // arrSelectedItems = 0;
        }else{
            dtPOSearchResult.draw(); //reload table to uncheck all
        }
    });

    // When checkbox changes state
    $('#tblPOSearchResult').on('change', '.itemCheckbox', function(){
        // Get the row of the current checkbox
        let row = $(this).closest('tr');
        // Enable/disable inputs based on checkbox state
        row.find('input[type="text"], input[type="number"]').prop('disabled', !this.checked);
    });

    // Optionally disable all inputs on page load
    $('#tblPOSearchResult tr').each(function(){
        let checkbox = $(this).find('.itemCheckbox');
        console.log('checkbox disabled', checkbox);
        if(!checkbox.prop('checked')){
            $(this).find('input[type="text"], input[type="number"]').prop('disabled', true);
        }
    });

    $("#tblPOSearchResult").on('click', '.buttonRemoveRow', function(){
        $(this).closest ('tr').remove();
    });

    $('#btnAddPreShipment').on('click', function(e){
        if($('#txtSelectControlNumber').val() != "" && $('#txtSearchMaterialName').val() != ""){
            $('#modalPreShipment').modal('show');
            $('#txtPartName').val($('#txtSelectControlNumber').val());
            $('#txtPartCode').val($('#txtSearchDeviceCode').val());
            $('#txtRequiredOutput').val($('#txtSearchReqOutput').val());

            if($('#txtFrmDataPreShipmentId').val() == ''){
                $('#btnAddPreShipmentDetails').prop('disabled', true);
                $('#btnAddPreShipmentDetails').prop('hidden', true);
            }else{
                $('#btnAddPreShipmentDetails').prop('disabled', false);
                $('#btnAddPreShipmentDetails').prop('hidden', false);
            }

            $('#btnSavePreShipment').prop('hidden', false);

            $('#btnRuncardDetails').prop('hidden', false);
            $('#txtPONumber').prop('disabled', false);
            $('#btnSubmitPreShipmentData').prop('hidden', true);

            // GetMachineNo($('.SelMachineNo'), $('#txtSelectControlNumber').val());
        }else{
            toastr.error('Please Select Device Name')
        }
    });

    $('#btnSubmitPreShipmentData').on('click', function(e){
        let token = $('#formPreshipment input[name="_token"]').val();
        let preshipment_id = $('#txtFrmDataPreShipmentId').val();
        // CheckExistingStations(preshipment_id);
        $.ajax({
            type: "post",
            url: "update_prod_runcard_status",
            data: {
                '_token': token,
                'preshipment_id': preshipment_id
            },
            dataType: "json",
            success: function (response) {
                if (response['result'] == 1 ) {
                    toastr.success('Successful!');
                    $("#modalPreShipment").modal('hide');
                    dtPreShipment.draw();
                }else{
                    toastr.error('Error!, Please Contanct ISS Local 208');
                }
            }
        });
    });

    const delay = (fn, ms) => {
        let timer = 0
        return function(...args) {
            clearTimeout(timer)
            timer = setTimeout(fn.bind(this, ...args), ms || 0)
        }
    }

    $('#formAddPreShipmentDetails #txtPONumber').change(delay(function(e){
        let po_number = $(this).val();
        if(po_number != ''){
            GetDataByPOFromDeliveryUpdate(po_number);
        }
    }, 300));

    function GetDataByPOFromDeliveryUpdate(po_number){
        $.ajax({
            url: "get_data_from_delivery_update",
            method: "get",
            data: {
                'po_number': po_number
            },
            dataType: "json",
            success: function(response){
                if(response['result'] == '1'){
                    let po_details = response['po_details'];

                    $("#formAddPreShipmentDetails #txtDeviceName").val(po_details.part_name);
                    $("#formAddPreShipmentDetails #txtPartsCode").val(po_details.part_code);
                    $("#formAddPreShipmentDetails #txtPackageCategory").val(po_details.package_category);
                    dtPOSearchResult.draw();

                    $('#tblPOSearchResult #chkAllItems').prop('disabled', false);
                }
            }
        });
    }

    $("#modalPreShipment").on('hidden.bs.modal', function () {
        // Reset form values
        $("#formPreshipment")[0].reset();
        $("#formAddPreShipmentDetails")[0].reset();

        // Remove invalid & title validation
        $('div').find('input').removeClass('is-invalid');
        $("div").find('input').attr('title', '');
        dtPreShipmentDetials.draw();
    });

    $('#btnSavePreShipment').click( function(e){
        e.preventDefault();
        $.ajax({
            type:"POST",
            url: "add_pre_shipment_data",
            data: $('#formPreshipment').serialize(),
            dataType: "json",
            success: function(response){
                if(response['validation'] == 'hasError'){
                    toastr.error('Saving failed!, Please complete all required fields');
                    if (response['error']['date'] === undefined) {
                        $("#txtDate").removeClass('is-invalid');
                        $("#txtDate").attr('title', '');
                    } else {
                        $("#txtDate").addClass('is-invalid');
                        $("#txtDate").attr('title', response['error']['date']);
                    }

                    if (response['error']['destination'] === undefined) {
                        $("#txtDestination").removeClass('is-invalid');
                        $("#txtDestination").attr('title', '');
                    } else {
                        $("#txtDestination").addClass('is-invalid');
                        $("#txtDestination").attr('title', response['error']['destination']);
                    }

                    if (response['error']['category'] === undefined) {
                        $("#txtCategory").removeClass('is-invalid');
                        $("#txtCategory").attr('title', '');
                    } else {
                        $("#txtCategory").addClass('is-invalid');
                        $("#txtCategory").attr('title', response['error']['category']);
                    }

                    if (response['error']['station'] === undefined) {
                        $("#txtStation").removeClass('is-invalid');
                        $("#txtStation").attr('title', '');
                    } else {
                        $("#txtStation").addClass('is-invalid');
                        $("#txtStation").attr('title', response['error']['station']);
                    }

                    if (response['error']['shipment_date'] === undefined) {
                        $("#txtShipmentDate").removeClass('is-invalid');
                        $("#txtShipmentDate").attr('title', '');
                    } else {
                        $("#txtShipmentDate").addClass('is-invalid');
                        $("#txtShipmentDate").attr('title', response['error']['shipment_date']);
                    }
                }else if (response['result'] == 1 ) {
                    toastr.success('Successful!');
                    $("#modalPreShipment").modal('hide');
                    dtPreShipment.draw();
                }else{
                    toastr.error('Error!, Please Contanct ISS Local 208');
                }
            }
        });
    });

    $(document).on('click', '.btnUpdatePreShipment',function(e){
        e.preventDefault();
        let PreShipmentId = $(this).attr('pre_shipment-id');
        $.ajax({
            url: "get_pre_shipment_data",
            type: "get",
            data: {
                pre_shipment_id: PreShipmentId
            },
            dataType: "json",
            beforeSend: function(){
            },
            success: function(response){
                const PreShipmentData = response['pre_shipment_data'];

                // CheckExistingStations(PreShipmentId, 'updating');
                // CheckExistingSubStations(PreShipmentId);

                $('#btnAddPreShipmentDetails').attr('preshipment_id', PreShipmentData.id);
                $('#btnAddPreShipmentDetails').prop('hidden', false);
                $('#btnAddPreShipmentDetails').prop('disabled', false);
                $('#btnSubmitPreShipmentData').prop('hidden', false); //uncomment save button

                // $('#btnRuncardDetails').prop('hidden', false);
                // $('#txtPONumber').prop('disabled', false);
                // $('#btnScanMaterialLot').prop('disabled', false);
                // $('#btnScanContactLot').prop('disabled', false);
                // $('#btnScanMELot').prop('disabled', false);
                // $('#btnSubmitPreShipmentData').prop('hidden', true);
                // $('#btnSavePreShipment').prop('hidden', false);
                // $('#btnSavePreShipmentDetails').prop('hidden', false);
                // GetPOFromPPSDB($('#txtPONumber'), $('#txtSelectControlNumber').val(), PreShipmentData.po_number);
                // GetMachineNo($('.SelMachineNo'), $('#txtSelectControlNumber').val(), PreShipmentData.machine_no);

                $('#formPreshipment #txtFrmDataPreShipmentId').val(PreShipmentData.id);
                $('#formPreshipment #txtDate').val(PreShipmentData.date);
                $('#formPreshipment #txtControlNo').val(PreShipmentData.control_no);
                $('#formPreshipment #SelectSalesCutOff').val(PreShipmentData.sales_cutoff).trigger('change');
                // $('#formPreshipment #txtDestination').val(PreShipmentData.destination);
                $('#formPreshipment #txtSelectDestination').val(PreShipmentData.destination).trigger('change');
                $('#formPreshipment #txtCategory').val(PreShipmentData.category);
                $('#formPreshipment #txtStation').val(PreShipmentData.station);
                $('#formPreshipment #txtShipmentDate').val(PreShipmentData.shipment_date);

                dtPreShipmentDetials.draw();
                $('#modalPreShipment').modal('show');
            },
            error: function(data, xhr, status){
                toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }

        });
    });

    $('#btnAddPreShipmentDetails').on('click', function(e){
        $('#modalAddPreShipmentDetails').modal('show');
        let preshipment_id = $(this).attr('preshipment_id');
        // CheckExistingStations(preshipment_id);
        // CheckExistingSubStations(preshipment_id);

        // $('#buttonAddRuncardModeOfDefect').prop('hidden', false);
         $('#formAddPreShipmentDetails #txtFrmDetailsPreShipmentId').val(preshipment_id);
         $("#buttonAddRuncardModeOfDefect").prop('disabled', true);

        $("#txtInputQuantity").prop('disabled', false);
        $("#txtOutputQuantity").prop('disabled', false);
        $("#txtNgQuantity").prop('disabled', false);
        $("#txtRemarks").prop('disabled', false);
        // GetPOFromDeliveryUpdate($('#txtPONumber'));
    });

    $('#txtOutputQuantity, #txtInputQuantity').each(function(e){
        $(this).keyup(delay(function(e){
            let input_val = parseFloat($('#txtInputQuantity').val());
            let output_val = parseFloat($('#txtOutputQuantity').val());
            let ng_value;
            if(output_val === "" || isNaN(output_val) || input_val === "" || isNaN(input_val)){
                ng_value = '';
            }else if(output_val != "" || input_val != ""){
                ng_value = input_val - output_val;
                if(ng_value < 0){
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Output Quantity cannot be less than Zero!",
                        showConfirmButton: false,
                        timer: 1500
                    });

                    // $('#txtInputQuantity').val('');
                    $('#txtOutputQuantity').val('');

                    ng_value = 0;
                    return;
                }
            }
            $('#txtNgQuantity').val(ng_value);

            if(parseInt(ng_value) > 0){
                $("#buttonAddRuncardModeOfDefect").prop('disabled', false);
            }else{
                $('#tableRuncardStationMOD tbody').empty();
                $('#buttonAddRuncardModeOfDefect').prop('disabled', true);
                $("#labelTotalNumberOfNG").text(parseInt(0));
            }

            if(parseInt(ng_value) === parseInt($('#labelTotalNumberOfNG').text())){
                $('#labelTotalNumberOfNG').css({color: 'green'})
                $('#labelIsTally').css({color: 'green'})
                $('#labelIsTally').addClass('fa-thumbs-up')
                $('#labelIsTally').removeClass('fa-thumbs-down')
                // $("#btnAddPreShipmentDetails").prop('disabled', false);
                $("#buttonAddRuncardModeOfDefect").prop('disabled', true);
                $("#btnSavePreShipmentDetails").prop('disabled', false);
            }else if(parseInt(ng_value) > parseInt($('#labelTotalNumberOfNG').text())){
                console.log('Mode of Defect NG is greater than NG qty');
                $('#labelTotalNumberOfNG').css({color: 'red'})
                $('#labelIsTally').css({color: 'red'})
                $('#labelIsTally').addClass('fa-thumbs-down')
                $('#labelIsTally').removeClass('fa-thumbs-up')

                // $("#btnAddPreShipmentDetails").prop('disabled', true);
                $("#buttonAddRuncardModeOfDefect").prop('disabled', false);
                $("#btnSavePreShipmentDetails").prop('disabled', true);
            }

            // console.log(input_val);
            // console.log(output_val);
            // CalculateTotalOutputandYield(output_val,input_val);
        }, 500));
    });

    $(document).on('click', '#btnSavePreShipmentDetails',function(e){
        e.preventDefault();
        $.ajax({
            type:"POST",
            url: "add_preshipmt_details_data",
            data: $('#formAddPreShipmentDetails').serialize(),
            dataType: "json",
            success: function(response){
                if(response['result'] == 1){
                    toastr.success('Successful!');
                    $('#formPreshipment #txtShipmentOutput').val(response['shipment_output']);
                    $("#modalAddPreShipmentDetails").modal('hide');
                    dtPreShipmentDetials.draw();
                    // CheckExistingStations($('#txtPreShipmentId').val(), 'updating');
                    // CheckExistingSubStations($('#txtPreShipmentId').val(), 'updating');
                }else{
                    toastr.error('Error!, Please Contanct ISS Local 208');
                }
            }
        });
    });

    $(document).on('click', '.btnUpdatePreShipmentDetails',function(e){
        e.preventDefault();
        let pre_shipment_id = $(this).attr('pre_shipment-id');
        let pre_shipment_details_id = $(this).attr('pre_shipment_details-id');
        GetPreShipmentData(pre_shipment_id, pre_shipment_details_id);
        // $('#txtOutputQuantity').prop('disabled', false);
        // $('#txtNgQuantity').prop('disabled', false);
        // $('#txtRemarks').prop('disabled', false);
        // $('#buttonAddRuncardModeOfDefect').prop('hidden', false);
        // $('#btnSavePreShipmentDetails').prop('hidden', false);
        $('#modalAddPreShipmentDetails').modal('show');
    });

    $(document).on('click', '.btnViewProdRuncardStationData',function(e){
        e.preventDefault();
        let pre_shipment_id = $(this).attr('pre_shipment-id');
        let pre_shipment_details_id = $(this).attr('prod_runcard_stations-id');

        GetPreShipmentData(pre_shipment_id, pre_shipment_details_id);

        $('#txtInputQuantity').prop('disabled', true);
        $('#txtOutputQuantity').prop('disabled', true);
        $('#txtNgQuantity').prop('disabled', true);
        // $('#txtSelectDocNoRDrawing').prop('disabled', true);
        // $('#txtSelectDocNoADrawing').prop('disabled', true);
        // $('#txtSelectDocNoGDrawing').prop('disabled', true);
        $('#txtRemarks').prop('disabled', true);
        $('#buttonAddRuncardModeOfDefect').prop('hidden', true);
        // $('#tableRuncardStationMOD').prop('disabled', true);
        // $("#tableRuncardStationMOD").find("input,button,textarea,select").attr("disabled", "disabled");
        // $('.buttonRemoveMOD').attr('disabled', true);
        $('#btnSavePreShipmentDetails').prop('hidden', true);
        $('#modalAddPreShipmentDetails').modal('show');
    });

    $("#modalAddPreShipmentDetails").on('hidden.bs.modal', function(){
        $("#formAddPreShipmentDetails")[0].reset();
        $('#tblPOSearchResult tbody').empty();
        $('div').find('input').removeClass('is-invalid');
        $("div").find('input').attr('title', '');
    });

    function GetPreShipmentData(preShipmentId, preShipmentDetailsId){
        $.ajax({
            url: "get_pre_shipment_data",
            type: "get",
            data: {
                pre_shipment_id: preShipmentId,
                pre_shipment_details_id: preShipmentDetailsId
            },
            dataType: "json",
            beforeSend: function(){
            },
            success: function(response){
                const preshipment_details = response['pre_shipment_data'];

                $('#formAddPreShipmentDetails #txtFrmDetailsPreShipmentId').val(preshipment_details.id);
                $('#formAddPreShipmentDetails #txtPreShipmentDetailsId').val(preshipment_details.preshipdetails_id);
                $('#formAddPreShipmentDetails #txtMasterCartonNo').val(preshipment_details.master_carton_no);
                $('#formAddPreShipmentDetails #txtItemNo').val(preshipment_details.item_no);
                $('#formAddPreShipmentDetails #txtPartsCode').val(preshipment_details.parts_code);
                $('#formAddPreShipmentDetails #txtDeviceName').val(preshipment_details.device_name);
                $('#formAddPreShipmentDetails #txtLotNo').val(preshipment_details.lot_no);
                $('#formAddPreShipmentDetails #txtQuantity').val(preshipment_details.qty);
                $('#formAddPreShipmentDetails #txtPackageCategory').val(preshipment_details.package_category);
                $('#formAddPreShipmentDetails #txtPackageQty').val(preshipment_details.package_qty);
                $('#formAddPreShipmentDetails #txtRemarks').val(preshipment_details.remarks);

                // $('#formAddPreShipmentDetails #txtPoNumber').val(preshipment_details.po_no).trigger('change');
                $('#formAddPreShipmentDetails #txtWeighedBy').val(preshipment_details.weighed_by).trigger('change');
                $('#formAddPreShipmentDetails #txtPackedBy').val(preshipment_details.packed_by).trigger('change');
                $('#formAddPreShipmentDetails #txtCheckedBy').val(preshipment_details.checked_by).trigger('change');

                $('#formAddPreShipmentDetails #txtPoNumber option').each(function () {
                    console.log('Option:', $(this).val());
                });

                getPOFromDeliveryUpdate($('#txtPoNumber'), preshipment_details.po_no);
                dtPOSearchResult.draw(); //reload table to uncheck all

                // getPOFromDeliveryUpdate($('#formAddPreShipmentDetails #txtPoNumber'), function () {
                //     // ✅ This runs ONLY AFTER the select is populated
                //     $('#formAddPreShipmentDetails #txtPoNumber')
                //         .val(preshipment_details.po_no)
                //         .trigger('change');
                // });

                // $('#formAddPreShipmentDetails #txtWeighedBy').val(preshipment_details.weighed_by_name);
                // $('#formAddPreShipmentDetails #txtPackedBy').val(preshipment_details.packed_by_name);
                // $('#formAddPreShipmentDetails #txtCheckedBy').val(preshipment_details.checked_by_name);

                // GetStations($('#txtSelectRuncardStation'), preshipment_details.station_step);
                // GetSubStations($('#txtSelectRuncardSubStation'), preshipment_details.sub_station_step);
            },
            error: function(data, xhr, status){
                toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }
        });
    }

    $(document).on('click', '#btnPrintPreShipment', function(e){
        // e.preventDefault();
        let preshipmentId = $(this).attr('pre_shipment-id');

        // getPoNoByPreShipId($('#txtPrintDeliveryKeyNo'), preshipmentId);
        // getLotNoByPreShipId($('#txtPrintLotNo'), preshipmentId);
        // getUsers($('#txtPrintPackedBy'), '0,1,2,3,4,5');
        // $('#modalPreShipmentPrintQr').modal('show');

        // Ensure both general sections are always visible
        $(".generalDiv").show();

        // Handle tab switching
        $("#formPrintTabs button").click(function(){
            // Remove active class from all tabs
            $("#formPrintTabs button").removeClass("active");
            $(this).addClass("active");

            // Hide all tab-specific divs
            $(".tab-content-div").hide();

            // Show the selected tab's content
            let targetDiv = $(this).data("tab");
            $("#" + targetDiv).show();

            console.log('targetDiv', targetDiv);

            if(targetDiv == 'normalDiv'){
                $('#txtPrintCategory').val(1);
            }else if(targetDiv == 'dynamicDiv'){
                $('#txtPrintCategory').val(2);
            }else if(targetDiv == 'customizedDiv'){
                $('#txtPrintCategory').val(3);
            }

            $("#formPrintPreShipmentQrCode").find("input, select, textarea").each(function(){
                if ($(this).is("select")) {
                    $(this).val("").trigger("change"); // Important for Select2
                } else if ($(this).is(":checkbox") || $(this).is(":radio")) {
                    $(this).prop("checked", false);
                } else if($(this).is(":date") && $(this).val() != ""){
                    // If it's a date input and has a value, keep it
                    $(this).val($(this).val());
                } else {
                    $(this).val("");
                }
            });

        });

        $.ajax({
            type: "get",
            url: "get_preshipment_to_print",
            data: {
                pre_shipment_id: preshipmentId
            },
            dataType: "json",
            success: function (response){
                let print_preship_details = response['print_preship_details'];

                // getPoNoByPreShipId($('#txtPrintDeliveryKeyNo'), preshipment_details.id);
                // getLotNoByPreShipId($('#txtPrintLotNo'), preshipment_details.id);
                // getUsers($('#txtPrintPackedBy'), '0,1,2,3,4,5');
                console.log('is_length', print_preship_details.length > 0);

                if(print_preship_details.length > 0 && print_preship_details[0].po_no != null){
                        result_po = '<option value="" disabled selected> Select PO No </option>';
                        result_lot_no = '<option value="" disabled selected> Select Lot No </option>';
                        result_packed_by = '<option value="" disabled selected> Select Packer </option>';

                    // Use a Set to track unique packed_by values
                    let uniquePackedByDetails = print_preship_details.filter((value, index, self) =>
                        index === self.findIndex((item) => item.packed_by === value.packed_by)
                    );

                    let uniquePODetails = print_preship_details.filter((value, index, self) =>
                        index === self.findIndex((item) => item.po_no === value.po_no)
                    );

                    let uniqueLotNoDetails = print_preship_details.filter((value, index, self) =>
                        index === self.findIndex((item) => item.lot_no === value.lot_no)
                    );

                    for (let u = 0; u < uniquePackedByDetails.length; u++){
                        result_packed_by += '<option name="'+uniquePackedByDetails[u].firstname+'" value="'+uniquePackedByDetails[u].packed_by+'">'+uniquePackedByDetails[u].packed_by_name+'</option>';
                    }

                    for (let i = 0; i < uniquePODetails.length; i++){
                        // if(uniquePODetails[i].po_no != null){
                            result_po += '<option device_name="'+uniquePODetails[i].device_name+'" parts_code="'+uniquePODetails[i].parts_code+'" value="'+uniquePODetails[i].po_no+'">'+uniquePODetails[i].po_no+'</option>';
                        // }

                        // if(uniquePODetails[i].lot_no != null){
                            result_lot_no += '<option value="'+uniquePODetails[i].lot_no+'">'+uniquePODetails[i].lot_no+'</option>';
                        // }
                    }
                }else{
                    result_po = '<option value="0" selected disabled> -- No record found -- </option>';
                    result_lot_no = '<option value="0" selected disabled> -- No record found -- </option>';
                    result_packed_by = '<option value="0" selected disabled> -- No record found -- </option>';
                }

                $('#txtPrintDeliveryKeyNo').html(result_po);
                $('#txtPrintLotNo').html(result_lot_no);
                $('#txtPrintPackedBy').html(result_packed_by);

                // $('#formPrintPreShipmentQrCode #txtPrintDeliveryKeyNo').val(preshipment_details.weighed_by).trigger('change');
                // $('#formPrintPreShipmentQrCode #txtPrintLotNo').val(preshipment_details.packed_by).trigger('change');

                $('#formPrintPreShipmentQrCode #txtPrintShipmentDate').val(print_preship_details[0].preship_shipment_date);
                // $('#formPrintPreShipmentQrCode #txtPrintDeliveryPlace').val(print_preship_details[0].preship_destination).trigger('change');
                // $('#formPrintPreShipmentQrCode #txtCheckedBy').val(preshipment_details.checked_by).trigger('change');

                $('#modalPreShipmentPrintQr').modal('show');
            }
        });

        $('#btnPrintThroughRapid').on('click', function(e){
            e.preventDefault();
            let selected_name = $("#txtPrintPackedBy option:selected").attr("name");
            let packed_by;

            if(selected_name != undefined){
                if (selected_name.includes(' ')) {
                    let exploded_fname = selected_name.split(' ');
                    packed_by = exploded_fname[0];
                } else {
                    packed_by = selected_name;
                }
            }else{
                toastr.error('Please select a Packer');
            }

            // NORMAL
            let check_reprint = 0; //Static to Zero based on RAPID ticketing form
            let printPreShipmentForm = $('#formPrintPreShipmentQrCode').serialize();
            printPreShipmentForm += '&check_reprint=' + encodeURIComponent(check_reprint);
            printPreShipmentForm += '&print_packed_by=' + encodeURIComponent(packed_by);
            console.log('printPreShipmentForm', printPreShipmentForm);

            //                         + '&print_parts_code=' + encodeURIComponent(partsCode)
            //                         + '&num_of_stickers=' + encodeURIComponent(num_of_stickers);
            //                         + '&package_count=' + encodeURIComponent(package_count);
            //                         + '&check_reprint=' + encodeURIComponent(check_reprint);
            //                         + '&max_qty=' + encodeURIComponent(max_qty);
            //                         + '&ship_qty=' + encodeURIComponent(ship_qty);
            //                         + '&customized_qty=' + encodeURIComponent(print_custom_qty);
            //                         + '&customized_ship_qty=' + encodeURIComponent(print_custom_total_qty);
            // console.log('printPreShipmentForm', printPreShipmentForm.print_shipment_date);

            // commented for testing, uncomment when ready to use
            $.ajax({
                url: "api/send-data",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") // Add CSRF token here
                },
                data: printPreShipmentForm,
                success: function(response){
                    console.log('response ', response);
                },
                error: function(response){
                    // console.log('nanito', response);
                    let validation = response.responseJSON['validation'];
                    let errorMessage = response.responseJSON['error'];
                    if(validation == 'hasError'){
                        toastr.error('Saving failed!, Please complete all required fields');
                        if (errorMessage['print_delivery_key_no'] === undefined) {
                            $("#txtPrintDeliveryKeyNo").removeClass('is-invalid');
                            $("#txtPrintDeliveryKeyNo").attr('title', '');
                        } else {
                            $("#txtPrintDeliveryKeyNo").addClass('is-invalid');
                            $("#txtPrintDeliveryKeyNo").attr('title', errorMessage['print_delivery_key_no']);
                        }

                        if (errorMessage['print_lot_no'] === undefined) {
                            $("#txtPrintLotNo").removeClass('is-invalid');
                            $("#txtPrintLotNo").attr('title', '');
                        } else {
                            $("#txtPrintLotNo").addClass('is-invalid');
                            $("#txtPrintLotNo").attr('title', errorMessage['print_lot_no']);
                        }

                        if (errorMessage['print_package_category'] === undefined) {
                            $("#txtPrintPackageCategory").removeClass('is-invalid');
                            $("#txtPrintPackageCategory").attr('title', '');
                        } else {
                            $("#txtPrintPackageCategory").addClass('is-invalid');
                            $("#txtPrintPackageCategory").attr('title', errorMessage['print_package_category']);
                        }

                        // if (errorMessage['print_packed_by'] === undefined) {
                        //     $("#txtPrintPackedBy").removeClass('is-invalid');
                        //     $("#txtPrintPackedBy").attr('title', '');
                        // } else {
                        //     $("#txtPrintPackedBy").addClass('is-invalid');
                        //     $("#txtPrintPackedBy").attr('title', errorMessage['print_packed_by']);
                        // }

                        if (errorMessage['print_normal_qty'] === undefined) {
                            $("#txtPrintNormalQty").removeClass('is-invalid');
                            $("#txtPrintNormalQty").attr('title', '');
                        } else {
                            $("#txtPrintNormalQty").addClass('is-invalid');
                            $("#txtPrintNormalQty").attr('title', errorMessage['print_normal_qty']);
                        }

                        if (errorMessage['print_normal_total_qty'] === undefined) {
                            $("#txtPrintNormalTotalQty").removeClass('is-invalid');
                            $("#txtPrintNormalTotalQty").attr('title', '');
                        } else {
                            $("#txtPrintNormalTotalQty").addClass('is-invalid');
                            $("#txtPrintNormalTotalQty").attr('title', errorMessage['print_normal_total_qty']);
                        }

                        if (errorMessage['print_dynamic_sticker_count'] === undefined) {
                            $("#txtPrintDynamicStickerCount").removeClass('is-invalid');
                            $("#txtPrintDynamicStickerCount").attr('title', '');
                        } else {
                            $("#txtPrintDynamicStickerCount").addClass('is-invalid');
                            $("#txtPrintDynamicStickerCount").attr('title', errorMessage['print_dynamic_sticker_count']);
                        }

                        if (errorMessage['print_custom_qty'] === undefined) {
                            $("#txtPrintCustomQty").removeClass('is-invalid');
                            $("#txtPrintCustomQty").attr('title', '');
                        } else {
                            $("#txtPrintCustomQty").addClass('is-invalid');
                            $("#txtPrintCustomQty").attr('title', errorMessage['print_custom_qty']);
                        }

                        if (errorMessage['print_custom_total_qty'] === undefined) {
                            $("#txtPrintCustomTotalQty").removeClass('is-invalid');
                            $("#txtPrintCustomTotalQty").attr('title', '');
                        } else {
                            $("#txtPrintCustomTotalQty").addClass('is-invalid');
                            $("#txtPrintCustomTotalQty").attr('title', errorMessage['print_custom_total_qty']);
                        }

                        if (errorMessage['print_custom_package_count'] === undefined) {
                            $("#txtPrintCustomPackageCount").removeClass('is-invalid');
                            $("#txtPrintCustomPackageCount").attr('title', '');
                        } else {
                            $("#txtPrintCustomPackageCount").addClass('is-invalid');
                            $("#txtPrintCustomPackageCount").attr('title', errorMessage['print_custom_package_count']);
                        }
                    }
                }
            });
        });
    });
});
