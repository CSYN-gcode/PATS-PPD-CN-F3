$(document).ready(function () {
    $('#frm_txt_engr_head_go_production').click(function(){
        $('#frm_txt_engr_head_stop_production').prop('checked', false);
    });

    $('#frm_txt_engr_head_stop_production').click(function(){
        $('#frm_txt_engr_head_go_production').prop('checked', false);
    });
});

function UpdateProdReqCheckingData(request_id, process_status, user_id, _token){
    // let _token = "{{ csrf_token() }}";
    var data = $.param({ _token, request_id, process_status, user_id}) + '&' + $('.prod_req_checking_data').serialize() + '&' + $('.machine_setup_sample_data').serialize();
    console.log(data);

    $.ajax({
        url: "update_product_req_checking_data",
        method: "post",
        data: data,
        dataType: "json",
        beforeSend: function(){
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 'Success'){
                $("#modalProdIdentification").modal('hide');
                $("#frm_prod_identification")[0].reset();
                frmProdIdentification.find('input').removeClass('is-invalid');
                frmProdIdentification.find('input').attr('title','');
                frmProdIdentification.find('select').removeClass('is-invalid');
                frmProdIdentification.find('select').attr('title','');
                toastr.success('New Request was succesfully saved!');
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
            console.log('data', data);
            let errors = data.responseJSON.error;
            let process_status = data.responseJSON.process_status;
            console.log('errors', errors);

            toastr.error('Saving Failed, Please fill up all required fields');
            // $('#modal-loading').modal('hide');
            if(data.status === 422){
                if(process_status == 0){
                    errorHandlerDmrpqc(errors.prod_visual_insp_name, $("#selProductionVisualUser"));
                    errorHandlerDmrpqc(errors.prod_visual_insp_result, $("input[name='prod_visual_insp_result']"));
                    errorHandlerDmrpqc(errors.prod_dimension_insp_name,$("#selProductionDimentionUser"));
                    errorHandlerDmrpqc(errors.prod_dimension_insp_result,$("input[name='prod_dimension_insp_result']"));
                    errorHandlerDmrpqc(errors.pic, $("#selMachineSetupSamplesPIC"));
                }else if(process_status == 1){
                    errorHandlerDmrpqc(errors.engr_tech_visual_insp_name, $("#selTechnicianVisualUser"));
                    errorHandlerDmrpqc(errors.engr_tech_visual_insp_result, $("input[name='engr_tech_visual_insp_result']"));
                    errorHandlerDmrpqc(errors.engr_tech_dimension_insp_name, $("#selTechnicianDimensionUser"));
                    errorHandlerDmrpqc(errors.engr_tech_dimension_insp_result, $("input[name='engr_tech_dimension_insp_result']"));
                }else if(process_status == 2){
                    errorHandlerDmrpqc(errors.lqc_visual_insp_name, $("#selQcVisualUser"));
                    errorHandlerDmrpqc(errors.lqc_visual_insp_result, $("input[name='lqc_visual_insp_result']"));
                    errorHandlerDmrpqc(errors.lqc_dimension_insp_name, $("#selQcDimensionUser"));
                    errorHandlerDmrpqc(errors.lqc_dimension_insp_result, $("input[name='lqc_dimension_insp_result']"));
                    errorHandlerDmrpqc(errors.checked_by_qc, $("#selMachineSetupSamplesQc"));
                }else if(process_status == 3){
                    errorHandlerDmrpqc(errors.process_engr_visual_insp_name, $("#selEngrVisualUser"));
                    errorHandlerDmrpqc(errors.process_engr_visual_insp_result, $("input[name='process_engr_visual_insp_result']"));
                    errorHandlerDmrpqc(errors.process_engr_dimension_insp_name, $("#selEngrDimensionUser"));
                    errorHandlerDmrpqc(errors.process_engr_dimension_insp_result, $("input[name='process_engr_dimension_insp_result']"));
                    errorHandlerDmrpqc(errors.checked_by_engr, $("#selMachineSetupSamplesEngr"));
                }
            }else{
                toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                toastr.error(`Error: ${data.status}`);
            }
        }
    });
}

function ProdReqCheckingEdittingMode(data){
    $.ajax({
        url: "get_dmrpqc_details_id",
        method: "get",
        data: data,
        dataType: "json",
        beforeSend(){
            $("#tblProdReqProduction .prod_req_checking_data").attr('disabled', true);
            $("#tblProdReqEngr .prod_req_checking_data").attr('disabled', true);
            $("#tblProdReqQC .prod_req_checking_data").attr('disabled', true);
            $("#tblProdReqProcessEngr .prod_req_checking_data").attr('disabled', true);
            $("#tbl_machine_samples .machine_setup_sample_data").attr('disabled', true);
        },
        success: function(response){
            let prod_req_checking_data = response['product_req_checking_details'][0];
            let p1_details = response['dmrpqc_details'][0];

            console.log('p5', prod_req_checking_data);
            if(prod_req_checking_data.status == 0){
                $("#tblProdReqProduction .prod_req_checking_data").attr('disabled', false);
                $("#tbl_machine_samples .machine_setup_sample_data").attr('disabled', false);
                $("#tbl_machine_samples #selMachineSetupSamplesQc").attr('disabled', true);
                $("#tbl_machine_samples #selMachineSetupSamplesEngr").attr('disabled', true);
                // $("#tblProdReqProduction .prod_req _checking_data").css("color", "");
                // $("#tblProdReqProduction .prod_req_checking_data").css("opacity", "");
            }else if(prod_req_checking_data.status == 1){
                $("#tblProdReqEngr .prod_req_checking_data").attr('disabled', false);
                $("#tblProdReqEngr #frm_txt_engr_tech_material_drawing_no").val(p1_details.drawing_no);
                $("#tblProdReqEngr #frm_txt_engr_tech_material_rev_no").val(p1_details.rev_no);
                $("#tblProdReqEngr #frm_txt_engr_tech_insp_guide_drawing_no").val(p1_details.drawing_no);
                $("#tblProdReqEngr #frm_txt_engr_tech_insp_guide_rev_no").val(p1_details.rev_no);

                // $("#tblProdReqEngr .prod_req_checking_data").css("color", "");
                // $("#tblProdReqEngr .prod_req_checking_data").css("opacity", "");
            }else if(prod_req_checking_data.status == 2){
                $("#tblProdReqQC .prod_req_checking_data").attr('disabled', false);
                $("#tbl_machine_samples #selMachineSetupSamplesQc").attr('disabled', false);

                $("#tblProdReqQC #frm_txt_lqc_material_drawing_no").val(p1_details.drawing_no);
                $("#tblProdReqQC #frm_txt_lqc_material_rev_no").val(p1_details.rev_no);
                $("#tblProdReqQC #frm_txt_lqc_insp_guide_drawing_no").val(p1_details.drawing_no);
                $("#tblProdReqQC #frm_txt_lqc_insp_guide_rev_no").val(p1_details.rev_no);

                // $("#tblProdReqQC .prod_req_checking_data").css("color", "");
                // $("#tblProdReqQC .prod_req_checking_data").css("opacity", "");
            }else if(prod_req_checking_data.status == 3){
                $("#tblProdReqProcessEngr .prod_req_checking_data").attr('disabled', false);
                $("#tbl_machine_samples #selMachineSetupSamplesEngr").attr('disabled', true);
                $("#tblProdReqProcessEngr #frm_txt_process_engr_material_drawing_no").val(p1_details.drawing_no);
                $("#tblProdReqProcessEngr #frm_txt_process_engr_material_rev_no").val(p1_details.rev_no);
                $("#tblProdReqProcessEngr #frm_txt_process_engr_insp_guide_drawing_no").val(p1_details.drawing_no);
                $("#tblProdReqProcessEngr #frm_txt_process_engr_insp_guide_rev_no").val(p1_details.rev_no);
                // $("#tblProdReqProcessEngr .prod_req_checking_data").css("color", "");
                // $("#tblProdReqProcessEngr .prod_req_checking_data").css("opacity", "");
            }

            // if(prod_req_checking_data.status <= 3){
                // $("#tbl_machine_samples .machine_setup_sample_data").attr('disabled', false);
            // }

            // $("#tblProdReqProduction .prod_req_checking_data").attr('disabled', false);
            // $("#tblProdReqEngr .prod_req_checking_data").attr('disabled', false);
            // $("#tblProdReqQC .prod_req_checking_data").attr('disabled', false);
            // $("#tblProdReqProcessEngr .prod_req_checking_data").attr('disabled', false);
        }
    });
}

function ProdReqCheckingViewingMode(){
    // $("#tblProdReqProduction .prod_req_checking_data").attr('disabled', true);
    // $("#tblProdReqProduction .prod_req_checking_data").css("color", "#6C757D");
    // $("#tblProdReqProduction .prod_req_checking_data").css("opacity", ".5");

    // $("#tblProdReqEngr .prod_req_checking_data").attr('disabled', true);
    // $("#tblProdReqEngr .prod_req_checking_data").css("color", "#6C757D");
    // $("#tblProdReqEngr .prod_req_checking_data").css("opacity", ".5");

    // $("#tblProdReqQC .prod_req_checking_data").attr('disabled', true);
    // $("#tblProdReqQC .prod_req_checking_data").css("color", "#6C757D");
    // $("#tblProdReqQC .prod_req_checking_data").css("opacity", ".5");

    // $("#tblProdReqProcessEngr .prod_req_checking_data").attr('disabled', true);
    // $("#tblProdReqProcessEngr .prod_req_checking_data").css("color", "#6C757D");
    // $("#tblProdReqProcessEngr .prod_req_checking_data").css("opacity", ".5");

    // $("#tbl_machine_samples .machine_setup_sample_data").attr('disabled', true);
    // $("#tbl_machine_samples .machine_setup_sample_data").css("color", "#6C757D");
    // $("#tbl_machine_samples .machine_setup_sample_data").css("opacity", ".5");

    $("#tblProdReqProduction .prod_req_checking_data").attr('disabled', true);
    $("#tblProdReqEngr .prod_req_checking_data").attr('disabled', true);
    $("#tblProdReqQC .prod_req_checking_data").attr('disabled', true);
    $("#tblProdReqProcessEngr .prod_req_checking_data").attr('disabled', true);
    $("#tbl_machine_samples .machine_setup_sample_data").attr('disabled', true);
}
