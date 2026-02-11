$(document).ready(function () {

    let SpecificationArr = [];
    let ActualMeasurementArr = [];

    //Assign Value
    $('#frm_txt_action_7a').click(function(){
        $('#frm_txt_action_7b').prop('checked', false);
    });

    $('#frm_txt_action_7b').click(function(){
        $('#frm_txt_action_7a').prop('checked', false);
    });

    //FUNCTION FOR ActionDoneChckbox(Action Done 1 - 7)
    $("#tbl_action_done").on('click', '.ActionDoneChckbox', function(e){
        let value = $(this).attr('value');
        let index = $(this).attr('index');
        // console.log('click action done', index);
        if($('.ActionDoneChckbox[index="'+index+'"][value="'+value+'"]').prop('checked')){
            // console.log('required false');
            $("#tbl_action_done .ActionDoneChckbox").attr('required', false);
        }else{
            // console.log('required true');
            $("#tbl_action_done .ActionDoneChckbox").attr('required', true);
        }
    });

    //FUNCTION FOR #frm_txt_action_4(Action Done 4)
    // function ActionDone4CheckBoxControls(){
    //     if($('#tbl_action_done #frm_txt_action_4').prop('checked')){
    //         $("#tbl_check_points .dieset_condition_data").attr('disabled', false);
    //         $("#frm_check_point_remarks").attr('disabled', false);
    //         $("#frm_check_point_remarks").attr('readonly', false);
    //         $("#tbl_action_done .Action4Chckbox").attr('required', true);
    //         $("#tbl_check_points .dieset_condition_data").attr('required', true);
    //         $("#tbl_action_done .Action4Chckbox").prop('disabled', false);
    //     }else{
    //         $("#tbl_check_points .dieset_condition_data").attr('disabled', true);
    //         $("#frm_check_point_remarks").attr('disabled', true);
    //         $("#tbl_action_done .Action4Chckbox").attr('required', false);
    //         $("#tbl_action_done .Action4Chckbox").prop('disabled', true);
    //     }
    // }

    $("#tbl_action_done").on('click', '#frm_txt_action_4', function(e){
        ActionDone4CheckBoxControls();
        // console.log('test 4');
        // $('.dieset_condition_data[index="1.'+action_index+'"]').prop('checked', true);

    });

    //FUNCTION FOR ActionDone4ChckBox(Action Done 4a, 4b, 4c, 4d)
    $("#tbl_action_done").on('click', '.Action4Chckbox', function(e){
        require_if_unchecked($(this));
    });

    //FUNCTION FOR ActionDone7ChckBox(Action Done 7a, 7b, 7c, 7d)
    $("#tbl_action_done").on('click', '.Action7Chckbox', function(e){
        if($('#tbl_action_done .Action7Chckbox').prop('checked')){
            $("#tbl_action_done .Action7Chckbox").attr('required', false);
        }else{
            $("#tbl_action_done .Action7Chckbox").attr('required', true);
        }
    });

    //FUNCTION FOR #frm_txt_action_7(Action Done 7)
    // function ActionDone7CheckBoxControls(){
    //     if($('#tbl_action_done #frm_txt_action_7').prop('checked')){
    //         $("#tbl_action_done .Action7Chckbox").attr('required', true);
    //         $("#tbl_action_done .Action7Chckbox").prop('disabled', false);
    //         $("#tbl_parts_no_and_qty .dieset_condition_data[name='parts_no']").attr('required', true);
    //         $("#tbl_parts_no_and_qty .dieset_condition_data[name='quantity']").attr('required', true);
    //             $("#tbl_action_done").on('click', '#frm_txt_action_7b', function(e){
    //                 if($('#tbl_action_done #frm_txt_action_7b').prop('checked') && $('#tbl_action_done #frm_txt_action_7').prop('checked')){
    //                     $('#modalPartsDrawing').modal('show');
    //                     $('#tblPartsDrawingData tbody .addRowforEdit').remove();
    //                     $('#btnPartsDrawingRemoveRow').addClass('d-none');

    //                     $("#txtEditUploadedFile").addClass('d-none');
    //                     $("#btnReuploadTrigger").addClass('d-none');
    //                     $("#btnReuploadTriggerDiv").addClass('d-none');
    //                     $("#btnReuploadTriggerLabel").addClass('d-none');
    //                     $("#txtAddFile").removeClass('d-none');
    //                     $("#download_file").addClass('d-none');

    //                     SpecificationArr     = [];
    //                     ActualMeasurementArr = [];
    //                 }else{
    //                     $('#modalPartsDrawing').modal('hide');
    //                 }
    //             });
    //     }else{
    //         console.log('click off');
    //         $("#tbl_action_done .Action7Chckbox").prop('disabled', true);
    //         $("#tbl_parts_no_and_qty .dieset_condition_data[name='parts_no']").attr('required', false);
    //         $("#tbl_parts_no_and_qty .dieset_condition_data[name='quantity']").attr('required', false);
    //         $("#tbl_action_done .Action7Chckbox").attr('required', false);
    //     }
    // }

    $("#tbl_action_done").on('click', '#frm_txt_action_7', function(e){
        ActionDone7CheckBoxControls();
        // if($('#tbl_action_done #frm_txt_action_7').prop('checked')){
        //     $("#tbl_action_done .Action7Chckbox").attr('required', true);
        //     $("#tbl_action_done .Action7Chckbox").prop('disabled', false);
        //     $("#tbl_parts_no_and_qty .dieset_condition_data[name='parts_no']").attr('required', true);
        //     $("#tbl_parts_no_and_qty .dieset_condition_data[name='quantity']").attr('required', true);
        //         $("#tbl_action_done").on('click', '#frm_txt_action_7b', function(e){
        //             if($('#tbl_action_done #frm_txt_action_7b').prop('checked') && $('#tbl_action_done #frm_txt_action_7').prop('checked')){
        //                 $('#modalPartsDrawing').modal('show');
        //                 $('#tblPartsDrawingData tbody .addRowforEdit').remove();
        //                 $('#btnPartsDrawingRemoveRow').addClass('d-none');

        //                 $("#txtEditUploadedFile").addClass('d-none');
        //                 $("#btnReuploadTrigger").addClass('d-none');
        //                 $("#btnReuploadTriggerDiv").addClass('d-none');
        //                 $("#btnReuploadTriggerLabel").addClass('d-none');
        //                 $("#txtAddFile").removeClass('d-none');
        //                 $("#download_file").addClass('d-none');

        //                 SpecificationArr     = [];
        //                 ActualMeasurementArr = [];
        //             }else{
        //                 $('#modalPartsDrawing').modal('hide');
        //             }
        //         });
        // }else{
        //     console.log('click off');
        //     $("#tbl_action_done .Action7Chckbox").prop('disabled', true);
        //     $("#tbl_parts_no_and_qty .dieset_condition_data[name='parts_no']").attr('required', false);
        //     $("#tbl_parts_no_and_qty .dieset_condition_data[name='quantity']").attr('required', false);
        //     $("#tbl_action_done .Action7Chckbox").attr('required', false);
        // }
    });

    $('#modalPartsDrawing').on('click', '#CloseBtnFrmPartsDrawing', function(e){
        console.log('cancel botton');
        if($("#frm_txt_request_id_for_parts_drawing").val() === ''){
            // console.log('walang laman');
            $('#tbl_action_done #frm_txt_action_7').prop('checked', false);
            $('#tbl_action_done #frm_txt_action_7b').prop('checked', false);
            $("#tbl_action_done .Action7Chckbox").attr('required', false);
            $("#tbl_parts_no_and_qty .dieset_condition_data[name='parts_no']").attr('required', false);
            $("#tbl_parts_no_and_qty .dieset_condition_data[name='quantity']").attr('required', false);
        }else{
            // console.log('norem');
            $('#tbl_action_done #frm_txt_action_7').prop('checked', true);
            $('#tbl_action_done #frm_txt_action_7a').prop('checked', false);
            $('#tbl_action_done #frm_txt_action_7b').prop('checked', true);
            $("#tbl_action_done .Action7Chckbox").attr('required', true);
            $("#tbl_parts_no_and_qty .dieset_condition_data[name='parts_no']").attr('required', true);
            $("#tbl_parts_no_and_qty .dieset_condition_data[name='quantity']").attr('required', true);
        }
        $("#FrmPartsDrawing")[0].reset();
    });

    $("#FrmPartsDrawing").submit(function(event){
        event.preventDefault();
        let process_status = $('#txt_global_status').val();

        if(process_status == 2){
            for(let index = 1; index <= $('#PartsDrawingRowCounter').val(); index++){
                let specification = $('.PartsDrawing[index="10.'+index+'"][name="specification"]').val();
                let actual_measurement = $('.PartsDrawing[index="10.'+index+'"][name="actual_measurement"]').val();

                SpecificationArr.push(specification);
                ActualMeasurementArr.push(actual_measurement);
            }
            UpdatePartsDrawingData(SpecificationArr, ActualMeasurementArr, $('#txt_global_dmrpqc_id').val(), $('#txt_global_status').val(), $('#txt_user_id').val());
        }
    });

    $('#btn_add_row').click(function(){
        let row_counter = $('#row_counter').val();
        row_counter++;
        console.log('test click add');
        if(row_counter > 1){
            $('#btn_remove_row').removeClass('d-none');
        }
        var html = '<tr class="addRowforEdit" id="row_'+row_counter+'">';
                // html += '<td>';
                //     html += '<input type="text" class="form-control" name="row_counter" id="row_counter" value="'+row_counter+'" readonly>';
                // html += '</td>';
                html += '<td hidden></td>';
                html += '<td class="text-center align-middle">'+row_counter+'.</td>';
                html += '<td>';
                    html += '<input type="text" class="form-control dieset_condition_data" index="4.'+row_counter+'" name="parts_no" id="frm_parts_no_'+row_counter+'" required>';
                html += '</td>';
                html += '<td>';
                    html += '<input type="text" class="form-control dieset_condition_data" index="4.'+row_counter+'" name="quantity" id="frm_quantity_'+row_counter+'" required>';
                html += '</td>';
            html += '</tr>';

        $('#row_counter').val(row_counter);
        $('#tbl_parts_no_and_qty tbody').append(html);
    });

    $('#btn_remove_row').click(function(){
        let row_counter =  $('#row_counter').val();
        $('#tbl_parts_no_and_qty tbody').find('#row_'+row_counter).remove();
        console.log('Total of row_counter before removing row: ', row_counter);
        row_counter--;
        $('#row_counter').val(row_counter).trigger('change');
        console.log('Total of row_counter after removing row: ' + row_counter);

        if(row_counter < 2){
                $('#btn_remove_row').addClass('d-none');
            }
    });

    $('#btnPartsDrawingAddRow').click(function(){
        let PartsDrawingRowCounter = $('#PartsDrawingRowCounter').val();
        PartsDrawingRowCounter++;
        console.log('test click add');
        if(PartsDrawingRowCounter > 1){
            $('#btnPartsDrawingRemoveRow').removeClass('d-none');
        }
        var html = '<tr class="addRowforEdit" id="row_'+PartsDrawingRowCounter+'">';
                // html += '<td>';
                //     html += '<input type="text" class="form-control" name="PartsDrawingRowCounter" id="PartsDrawingRowCounter" value="'+PartsDrawingRowCounter+'" readonly>';
                // html += '</td>';
                html += '<td hidden></td>';
                html += '<td class="text-center align-middle">'+PartsDrawingRowCounter+'.</td>';
                html += '<td>';
                    html += '<input type="text" class="form-control PartsDrawing" index="10.'+PartsDrawingRowCounter+'" name="specification" id="frm_specification_'+PartsDrawingRowCounter+'" required>';
                html += '</td>';
                html += '<td>';
                    html += '<input type="text" class="form-control PartsDrawing" index="10.'+PartsDrawingRowCounter+'" name="actual_measurement" id="frm_actual_measurement_'+PartsDrawingRowCounter+'" required>';
                html += '</td>';
            html += '</tr>';

        $('#PartsDrawingRowCounter').val(PartsDrawingRowCounter);
        $('#tblPartsDrawingData tbody').append(html);
    });

    $('#btnPartsDrawingRemoveRow').click(function(){
        let PartsDrawingRowCounter =  $('#PartsDrawingRowCounter').val();
        $('#tblPartsDrawingData tbody').find('#row_'+PartsDrawingRowCounter).remove();
        console.log('Total of PartsDrawingRowCounter before removing row: ', PartsDrawingRowCounter);
        PartsDrawingRowCounter--;
        $('#PartsDrawingRowCounter').val(PartsDrawingRowCounter).trigger('change');
        console.log('Total of PartsDrawingRowCounter after removing row: ' + PartsDrawingRowCounter);

        if(PartsDrawingRowCounter < 2){
            $('#btnPartsDrawingRemoveRow').addClass('d-none');
        }
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

    // OPEN PARTS DRAWING
    $(document).on('click', '#btnPartsDrawing', function(e){
        let id = $('#txt_global_dmrpqc_id').val();
        let process_status = $('#txt_global_status').val();

        var data = {
            'id' : id,
            'process_status' : process_status
        }

        SpecificationArr     = [];
        ActualMeasurementArr = [];

        // GetDmrpqcDetails(data);
        $.ajax({
            url: "get_dmrpqc_details_id",
            method: "get",
            data: data,
            dataType: "json",
            beforeSend(){
                $('#tblPartsDrawingData tbody .addRowforEdit').remove();
                $('#btnPartsDrawingRemoveRow').addClass('d-none');
                // $('#tbl_parts_no_and_qty tbody').append(html);
            },
            success: function(response){
                    let dmrpqc_details = response['dmrpqc_details'];
                    let dieset_condition_details = response['dieset_condition_details'];

                    if(dmrpqc_details[0].process_status == 2){
                        $("#btnReuploadTriggerDiv").removeClass('d-none');
                        $("#btnReuploadTrigger").removeClass('d-none');
                        $("#btnReuploadTrigger").prop('checked', false);
                        $("#btnReuploadTriggerLabel").removeClass('d-none');
                        $("#btnPartsDrawingRemoveRow").removeClass("d-none");
                        $("#selFabricatedBy").prop("disabled", false);
                        $("#selValidatedBy").prop("disabled", false);
                    }else{
                        $("#btnReuploadTriggerDiv").addClass("d-none");
                        $("#btnPartsDrawingAddRow").addClass("d-none");
                        $("#selFabricatedBy").prop("disabled", true);
                        $("#selValidatedBy").prop("disabled", true);
                    }
                        // $("#txt_global_dmrpqc_id").val(dmrpqc_details[0].id);
                        // $("#txt_global_status").val(dmrpqc_details[0].process_status);

                        $("#txtEditUploadedFile").removeClass('d-none');
                        $("#txtAddFile").addClass('d-none');
                        $("#txtAddFile").removeAttr('required');
                        $("#txtEditUploadedFile").val(dieset_condition_details[0].parts_drawing);

                        let drawing_specification;
                        let drawing_actual_measurement;
                        // console.log('valuessssss', dieset_condition_details[0].drawing_specification);

                        if(dieset_condition_details[0].drawing_specification !== null){
                            drawing_specification = dieset_condition_details[0].drawing_specification.split(",");
                        }else{
                            drawing_specification = '';
                        }

                        if(dieset_condition_details[0].drawing_specification !== null){
                            drawing_actual_measurement = dieset_condition_details[0].drawing_actual_measurement.split(",");
                        }else{
                            drawing_actual_measurement = '';
                        }

                        console.log('selFabricatedBy', dieset_condition_details[0].drawing_fabricated_by);
                        console.log('selFabricatedBy',dieset_condition_details[0].drawing_validated_by);

                        $("#selFabricatedBy").val(dieset_condition_details[0].drawing_fabricated_by).trigger('change');
                        $("#selValidatedBy").val(dieset_condition_details[0].drawing_validated_by).trigger('change');

                        // if(dieset_condition_details[0].drawing_fabricated_by == null){
                        //     $("#frm_txt_fabricated_by_id").val($("#txt_user_id").val());
                        //     $("#frm_txt_fabricated_by").val($("#txt_user_name").val());
                        // }else{
                        //     $("#frm_txt_fabricated_by_id").val(dieset_condition_details[0].drawing_fabricated_by.id);
                        //     $("#frm_txt_fabricated_by").val(dieset_condition_details[0].drawing_fabricated_by.rapidx_user_details.name);
                        // }

                        // if(dieset_condition_details[0].drawing_validated_by == null){
                        //     $("#frm_m_validated_by_id").val($("#txt_user_id").val());
                        //     $("#frm_m_validated_by").val($("#txt_user_name").val());
                        // }else{
                        //     $("#frm_m_validated_by_id").val(dieset_condition_details[0].drawing_validated_by.id);
                        //     $("#frm_m_validated_by").val(dieset_condition_details[0].drawing_validated_by.rapidx_user_details.name);
                        // }

                        $('.PartsDrawing[index="10.1"][name="specification"]').val(drawing_specification[0]); //static set of value to static attr:index
                        $('.PartsDrawing[index="10.1"][name="actual_measurement"]').val(drawing_actual_measurement[0]); //static set of value to static attr:index

                        let edit_row_counter = (drawing_specification.length);
                        if(edit_row_counter > 1){
                            if(dmrpqc_details[0].process_status == 2){
                                $('#btnPartsDrawingRemoveRow').removeClass('d-none');
                            }else{
                                $('#btnPartsDrawingRemoveRow').addClass('d-none');
                            }
                        }else{
                            $('#btnPartsDrawingRemoveRow').addClass('d-none');
                        }
                        for(let index = 1; index < drawing_specification.length && index < drawing_actual_measurement.length; index++){
                            let loop_ctrn = 1;
                            let html = '<tr class="addRowforEdit" id="row_'+(index + loop_ctrn)+'">';
                                    // html += '<td>';
                                    //     html += '<input type="text" class="form-control" name="row_counter" id="row_counter" value="'+row_counter+'" readonly>';
                                    // html += '</td>';
                                    html += '<td hidden></td>';
                                    html += '<td class="text-center align-middle">'+(index + loop_ctrn)+'.</td>';
                                    html += '<td>';
                                        html += '<input type="text" class="form-control PartsDrawing" index="10.'+(index + loop_ctrn)+'" name="specification" id="frm_specification_'+(index + loop_ctrn)+'" value="'+drawing_specification[index]+'">';
                                    html += '</td>';
                                    html += '<td>';
                                        html += '<input type="text" class="form-control PartsDrawing" index="10.'+(index + loop_ctrn)+'" name="actual_measurement" id="frm_actual_measurement_'+(index + loop_ctrn)+'" value="'+drawing_actual_measurement[index]+'">';
                                    html += '</td>';
                                html += '</tr>';

                            // loop_ctrn = index + 1;
                            $('#PartsDrawingRowCounter').val(edit_row_counter);
                            $('#tblPartsDrawingData tbody').append(html);
                        }

                        if(dmrpqc_details[0].process_status != 2){
                            $("#tblPartsDrawingData .PartsDrawing").attr('disabled', true);
                        }else{
                            $("#tblPartsDrawingData .PartsDrawing").attr('disabled', false);
                        }

                        var download ='<a href="download_file/'+dieset_condition_details[0].request_id+'">';
                            download +='<button type="button" id="download_file" name="download_file" class="btn btn-primary btn-sm d-none">';
                            download +=     '<i class="fa-solid fa-file-arrow-down"></i>';
                            download +=         '&nbsp;';
                            download +=         'See Attachment';
                            download +='</button>';
                            download +='</a>';

                        $('#AttachmentDiv').append(download);
                        $("#download_file").removeClass('d-none');
                        $('#frm_txt_request_id_for_parts_drawing').val(dieset_condition_details[0].request_id);
                        $("#modalPartsDrawing").modal('show');
                    // }
            },
            error: function(data, xhr, status){
                toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }
        });
    });
});

function ActionDone4CheckBoxControls(){
    $("#tbl_action_done").on('click', '#frm_txt_action_4', function(e){
        if($('#tbl_action_done #frm_txt_action_4').prop('checked')){
            $("#tbl_check_points .dieset_condition_data").attr('disabled', false);
            $("#frm_check_point_remarks").attr('disabled', false);
            $("#frm_check_point_remarks").attr('readonly', false);
            $("#tbl_action_done .Action4Chckbox").attr('required', true);
            $("#tbl_check_points .dieset_condition_data").attr('required', true);
            $("#tbl_action_done .Action4Chckbox").prop('disabled', false);
        }else{
            $("#tbl_check_points .dieset_condition_data").attr('disabled', true);
            $("#frm_check_point_remarks").attr('disabled', true);
            $("#tbl_action_done .Action4Chckbox").attr('required', false);
            $("#tbl_action_done .Action4Chckbox").prop('disabled', true);
        }
    });
}

function ActionDone7CheckBoxControls(){
    if($('#tbl_action_done #frm_txt_action_7').prop('checked')){
        $("#tbl_action_done .Action7Chckbox").attr('required', true);
        $("#tbl_action_done .Action7Chckbox").prop('disabled', false);
        $("#tbl_parts_no_and_qty .dieset_condition_data[name='parts_no']").attr('required', true);
        $("#tbl_parts_no_and_qty .dieset_condition_data[name='quantity']").attr('required', true);
            $("#tbl_action_done").on('click', '#frm_txt_action_7b', function(e){
                if($('#tbl_action_done #frm_txt_action_7b').prop('checked') && $('#tbl_action_done #frm_txt_action_7').prop('checked')){
                    $('#modalPartsDrawing').modal('show');
                    $('#tblPartsDrawingData tbody .addRowforEdit').remove();
                    $('#btnPartsDrawingRemoveRow').addClass('d-none');

                    $("#txtEditUploadedFile").addClass('d-none');
                    $("#btnReuploadTrigger").addClass('d-none');
                    $("#btnReuploadTriggerDiv").addClass('d-none');
                    $("#btnReuploadTriggerLabel").addClass('d-none');
                    $("#txtAddFile").removeClass('d-none');
                    $("#download_file").addClass('d-none');

                    SpecificationArr     = [];
                    ActualMeasurementArr = [];
                }else{
                    $('#modalPartsDrawing').modal('hide');
                }
            });
    }else{
        console.log('click off');
        $("#tbl_action_done .Action7Chckbox").prop('disabled', true);
        $("#tbl_parts_no_and_qty .dieset_condition_data[name='parts_no']").attr('required', false);
        $("#tbl_parts_no_and_qty .dieset_condition_data[name='quantity']").attr('required', false);
        $("#tbl_action_done .Action7Chckbox").attr('required', false);
    }
}

function UpdateDiesetConditionData(PartsNoArr, QuantityArr, request_id, process_status, user_id, _token){
// function UpdateDiesetConditionData(){
    // let _token = "{{ csrf_token() }}";
    var data = $.param({ _token, PartsNoArr, QuantityArr, request_id, process_status, user_id}) + '&' + $('.dieset_condition_data').serialize();
    $.ajax({
        url: "update_dieset_conditon_data",
        method: "post",
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
                toastr.success('New Request was succesfully saved!');
            }else if(JsonObject['result'] == 2){
                toastr.error('Saving Failed! Item Code Still Ongoing Preparation');
                ActionDonePartsNoArr  = [];
                ActionDoneQuantityArr = [];
            }
            else{
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

function UpdatePartsDrawingData(Specification, ActualMeasurement, request_id, process_status, user_id){
    var formData = new FormData($('#FrmPartsDrawing')[0]);
        formData.append('specification', Specification);
        formData.append('actual_measurement', ActualMeasurement);
        formData.append('request_id', request_id);
        formData.append('process_status', process_status);
        formData.append('user_id', user_id);

    $.ajax({
        url: "update_parts_drawing_data",
        method: "post",
        // _token: _token,
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        beforeSend: function(){
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 'Success'){
                $("#modalPartsDrawing").modal('hide');
                $("#FrmPartsDrawing")[0].reset();
                toastr.success('Drawing Succesfully Saved!');
            }else if(JsonObject['result'] == 'File Name Already Exists'){
                toastr.error('File Name Already Exists!');
            }else{
                toastr.error('Saving Failed!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });

    SpecificationArr     = [];
    ActualMeasurementArr = [];
}

function DiesetConditionAndCheckingEdittingMode(){
    $('#frm_txt_details_of_activity').removeAttr('readonly');
    $('#frm_mold_check_remarks').removeAttr('readonly');
    $("#btn_add_row").removeClass("d-none");

    $("#tbl_action_done .dieset_condition_data").attr('disabled', false);
    $("#tbl_dieset_condition_checking .dieset_condition_checking_data").attr('disabled', false);
    $("#tbl_action_done .ActionDoneChckbox").attr('required', true);

    $("#frm_txt_details_of_activity").attr('disabled', false);
    $("#tbl_parts_no_and_qty .dieset_condition_data").attr('disabled', false);
    $("#tbl_date_start_finish .dieset_condition_data").attr('disabled', false);
    $("#tbl_mold_check .dieset_condition_data").attr('disabled', false);
    $("#frm_mold_check_remarks").attr('disabled', false);
    $("#tbl_references_used .dieset_condition_data").attr('disabled', false);
    $("#tbl_checked_by .dieset_condition_data").attr('disabled', false);
    $("#tbl_final_remarks .dieset_condition_data").attr('disabled', false);

    $('#btnPartsDrawing').addClass('d-none');
    $("#tbl_action_done .dieset_condition_data").css("color", "");
    $("#tbl_action_done .dieset_condition_data").css("opacity", "");
    ActionDonePartsNoArr = [];
    ActionDoneQuantityArr = [];
}

function DiesetConditionAndCheckingViewingMode(){
    $("#tbl_dieset_condition_checking .dieset_condition_checking_data").attr('disabled', true);
    $('#btnPartsDrawing').removeClass('d-none');
    $("#tbl_action_done .dieset_condition_data").attr('disabled', true);
    $("#tbl_action_done .dieset_condition_data").css("color", "#6C757D");
    $("#tbl_action_done .dieset_condition_data").css("opacity", ".5");
    $("#tbl_check_points .dieset_condition_data").attr('disabled', true);
    $("#tbl_mold_check .dieset_condition_data").attr('disabled', true);
    $("#tbl_references_used .dieset_condition_data").attr('disabled', true);
    $("#tbl_checked_by .dieset_condition_data").attr('disabled', true);
    $("#tbl_date_start_finish .dieset_condition_data").attr('disabled', true);
    $("#tbl_final_remarks .dieset_condition_data").attr('disabled', true);
    $("#btn_add_row").addClass("d-none");
    $("#btn_remove_row").addClass("d-none");
    $("#btnSavePartsDrawing").addClass("d-none");
    $("#frm_txt_details_of_activity").attr('disabled', true);
    $("#frm_check_point_remarks").attr('disabled', true);
    $("#frm_mold_check_remarks").attr('disabled', true);
    $("#tbl_parts_no_and_qty .dieset_condition_data").attr('disabled', true);
}
