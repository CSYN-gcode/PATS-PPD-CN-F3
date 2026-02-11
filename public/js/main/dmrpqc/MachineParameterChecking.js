function UpdateMachineParamCheckingData(request_id, process_status, user_id, _token){
    // let _token = "{{ csrf_token() }}";
    var data = $.param({ _token, request_id, process_status, user_id}) + '&' + $('.machine_param_checking_data').serialize();
    console.log(data);

    $.ajax({
        url: "update_machine_param_checking_data",
        method: "post",
        // _token: _token,
        data: data,
        dataType: "json",
        beforeSend: function(){
        },
        success: function(JsonObject){
            if(JsonObject['error'] == "Please Select Action Done"){
                // console.log(JsonObject['error']);
                toastr.error('Please Select Action Done');
                ActionDonePartsNoArr  = [];
                ActionDoneQuantityArr = [];
            }else if(JsonObject['result'] == 'Success'){
                $("#modalProdIdentification").modal('hide');
                $("#frm_prod_identification")[0].reset();
                frmProdIdentification.find('input').removeClass('is-invalid');
                frmProdIdentification.find('input').attr('title','');
                frmProdIdentification.find('select').removeClass('is-invalid');
                frmProdIdentification.find('select').attr('title','');
                toastr.success('Succesfully Saved!');
            }else if(JsonObject['result'] == 2){
                toastr.error('Saving Failed! Item Code Still Ongoing Preparation');
                ActionDonePartsNoArr  = [];
                ActionDoneQuantityArr = [];
            }else{
                toastr.error('Saving Failed! Please check all fields. Put N/A if not applicable. Check all radio button.');
                ActionDonePartsNoArr  = [];
                ActionDoneQuantityArr = [];
            }
            dataTableDmrpqc.draw();
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}


function MachineParameterEdittingMode(data){
    // $("#tbl_machine_param_chckng_reference .machine_param_checking_data").attr('disabled', false);
    // $("#tbl_machine_param_chckng .machine_param_checking_data").attr('disabled', false);

    $.ajax({
        url: "get_dmrpqc_details_id",
        method: "get",
        data: data,
        dataType: "json",
        beforeSend(){
            $("#tbl_machine_param_chckng_reference .machine_param_checking_data").attr('disabled', true);
            $("#tbl_machine_param_chckng .engr_input").attr('disabled', true);
            $("#tbl_machine_param_chckng .qc_input").attr('disabled', true);
        },
        success: function(response){
            let machine_param_checking_details = response['machine_param_checking_details'];
            console.log('p6', machine_param_checking_details);
            if(machine_param_checking_details[0].status == 0){
                $("#tbl_machine_param_chckng_reference .machine_param_checking_data").attr('disabled', false);
                $("#tbl_machine_param_chckng .engr_input").attr('disabled', false);
            }else if(machine_param_checking_details[0].status == 1){
                $("#tbl_machine_param_chckng .qc_input").attr('disabled', false);
            }
        }
    });
}

function MachineParameterViewingMode(){
    $("#tbl_machine_param_chckng_reference .machine_param_checking_data").attr('disabled', true);
    $("#tbl_machine_param_chckng .machine_param_checking_data").attr('disabled', true);
}
