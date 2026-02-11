$(document).ready(function () {

});

//============================== GET USER ID ==============================
function GetUserId(userId) {
    $.ajax({
        url: "get_id_edit_user",
        method: "get",
        data: {
            user_id: userId
        },
        dataType: "json",
        beforeSend: function () {
            //GET DEFAULT SELECTED SECTION
            // $("#editUserForm select[id='selSection']").val('0');

            //GET DEFAULT SELECTED POSITION
            // $("#editUserForm select[id='selPosition']").val('0');
        },
        success: function (JsonObject) {
            // let userData = response['user_data'];
            if (JsonObject['user_data'].length > 0) {
                //GET RAPIDX ID
                $("#selEditUserAccessUserId").val(JsonObject['user_data'][0].rapidx_id);

                //GET SECTION
                var sec_array = ['BOD','IAS','FIN','HRD','ESS','LOG','FAC','ISS','QAD','EMS','TS','CN','YF','PPS','PPS-TS','PPS-CN'];
                for(var index = 0; index < sec_array.length; index++){
                    if(JsonObject['user_data'][0].section == (index + 1)){
                        // $("#txtEditUserSectionId").val(sec_array[index]);
                        // $("#selSection").val((index + 1));
                        $("#editUserForm select[id='selSection']").val((index + 1));

                    }
                }

                //GET POSITION
                var pos_array = ['POS 1','POS 2','POS 3','POS 4','POS 5'];
                for(var index = 0; index < pos_array.length; index++){
                    if(JsonObject['user_data'][0].position == (index + 1)){
                        // $("#txtEditUserPositionId").val(pos_array[index]);
                        $("#editUserForm select[id='selPosition']").val((index + 1));
                    }
                }

                // $("#selPosition").val(JsonObject['user_data'][0].position);
                //GET USERLEVEL ID
                $("#selEditUserLevelId").val(JsonObject['user_data'][0].user_level_id);
            }
            else {
                toastr.warning('No Record Found!');
            }
        },
        error: function (data, xhr, status) {
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

//============================== GET USER BY POSITION ==============================
function GetProductionUsers(position, cboElement, CurrentValue){
    GetUserByPosition(position, cboElement, CurrentValue);
}

function GetTechnicianUser(position, cboElement, CurrentValue){
    GetUserByPosition(position, cboElement, CurrentValue);
}

function GetQcInspectorUser(position, cboElement, CurrentValue){
    GetUserByPosition(position, cboElement, CurrentValue);
}

function GetSupervisorEngrUser(position, cboElement, CurrentValue){
    GetUserByPosition(position, cboElement, CurrentValue);
}

function GetUserByPosition(position, cboElement, CurrentValue){
    let result;
    // if(position == 1){
    // if(position.includes('1') || position.includes('4') || position.includes('12') || position.includes('13') || position.includes('14')){
    //     let result = '<option value="" disabled selected>--Select Production--</option>';
    // }else if(position.includes('9') || position.includes('11') || position.includes('15')){
    //     let result = '<option value="" disabled selected>--Select Technician--</option>';
    // }else if(position.includes('1') || position.includes('9') || position.includes('15') || position.includes('16') || position.includes('17')){
    //     let result = '<option value="" disabled selected>--Select Supervisor/Engr.--</option>';
    // }else if(position.includes('2') || position.includes('5')){
    //     let result = '<option value="" disabled selected>--Select QC Inspector--</option>';
    // }else{
    //     let result = '<option value="" disabled selected>--Select ISS--</option>';
    // }

    $.ajax({
        url: 'get_users_by_position',
        method: 'get',
        data: {
            'position': position
        },
        dataType: 'json',
        beforeSend: function() {
                result = '<option value="0" disabled selected>--Loading--</option>';
                cboElement.html(result);
        },
        success: function(JsonObject) {
            if (JsonObject['users'].length > 0) {
                let user_position = JSON.stringify(JsonObject['users'][0].position)
                if(user_position.includes('1') || user_position.includes('4') || user_position.includes('12') || user_position.includes('13') || user_position.includes('14')){
                    result = '<option value="" disabled selected>--Select Production--</option>';
                }else if(user_position.includes('9') || user_position.includes('11') || user_position.includes('15')){
                    result = '<option value="" disabled selected>--Select Technician--</option>';
                }else if(user_position.includes('1') || user_position.includes('9') || user_position.includes('15') || user_position.includes('16') || user_position.includes('17')){
                    result = '<option value="" disabled selected>--Select Supervisor/Engr.--</option>';
                }else if(user_position.includes('2') || user_position.includes('5')){
                    result = '<option value="" disabled selected>--Select QC Inspector--</option>';
                }else{
                    result = '<option value="" disabled selected>--Select ISS--</option>';
                }

                // if(JsonObject['users'][0].position == 1){
                // result = '<option value="" disabled selected>--Select Production--</option>';
                // }else if(JsonObject['users'][0].position == 3){
                // result = '<option value="" disabled selected>--Select Technician--</option>';
                // }else if(JsonObject['users'][0].position == 4){
                // result = '<option value="" disabled selected>--Select Supervisor/Engr.--</option>';
                // }else if(JsonObject['users'][0].position == 5){
                // result = '<option value="" disabled selected>--Select QC Inspector--</option>';
                // }else{
                // result = '<option value="" disabled selected>--Select ISS--</option>';
                // }

                result += '<option value="N/A"> N/A </option>';
                // result = '<option value="" selected>-- N/A --</option>';
                for (let index = 0; index < JsonObject['users'].length; index++) {
                    result += '<option value="' + JsonObject['users'][index].id + '">' + JsonObject['users'][index].firstname+' '+JsonObject['users'][index].lastname + '</option>';
                }
            }else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            cboElement.html(result);
            if(CurrentValue != null){
                cboElement.val(CurrentValue).trigger('change');
                // GetPPSDBDataByPO(PoNumber, device_name, 1);
            }
            // selUserPositionElement.select2();
        },
        error: function(data, xhr, status) {
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}
