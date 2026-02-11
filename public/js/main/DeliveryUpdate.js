const loadDeliveryUpdateSearchPo = (poNumber) => {
    $.ajax({
        type: "get",
        url: "search_po_delivery_update",
        data: {
            "po_number": poNumber
        },
        dataType: "json",
        success: function (response) {
            let runcardInfos = response['runcardInfos']
            let poReceivedInfo = response['poReceivedInfo']
            let sumActualSo = response['sumActualSo']

            $('#txtPoQty').val(poReceivedInfo[0].OrderQty)

            if (runcardInfos != null && runcardInfos.oqc_inspection_info != null) {
                $('#txtSearchPO').val(runcardInfos.po_number)
                $('#txtDeviceName').val(runcardInfos.part_name)
                $('#txtDeviceCode').val(runcardInfos.part_code)
                $('#txtMatDrawingNo').val(runcardInfos.drawing_no)
                $('#txtMatDrawingRev').val(runcardInfos.drawing_rev)
                $('#txtShipmentDate').val(new Date().toLocaleDateString('default', { year: 'numeric', month: 'long', day: 'numeric' }))
                $('#txtTargetSO').val(runcardInfos.po_quantity)
                dtDeliveryUpdate.draw();
            }else if(runcardInfos.oqc_inspection_info == null) {
                toastr.error('Runcard is not done in OQC inspection!, Please Inspect First');
            }else {
                toastr.error('PO Not Found!');
                dtDeliveryUpdate.draw();
            }
            setTimeout(() => {
                let wqe = $('table tbody tr:first');
                let sad = wqe.find('td:eq(2)').text();
                $('#txtVariance').val(sad);
                $('#txtTotalSO').val(sumActualSo)
            }, 500);

        }
    });
}

const GetEmployeeName = (list) => {
    let result = '<option value="">N/A</option>';
    $.ajax({
        url: "get_employee_name",
        method: "get",
        dataType: "json",

        beforeSend: function(){
            result = '<option value="" selected disabled> -- Loading -- </option>';
            list.html(result);
        },
        success: function(response){
            result = '';

            if(response['userDetails'].length > 0){
                result = '<option selected value="" disabled> Scan Employee ID </option>';
                for(let index = 0; index < response['userDetails'].length; index++){
                    result += '<option value="' + response['userDetails'][index].employee_id+'">'+ response['userDetails'][index].firstname+' '+ response['userDetails'][index].lastname+'</option>';
                }
            }
            else{
                result = '<option value="0" selected disabled> No record found </option>';
            }
            list.html(result);
        }
    });
}

const GetLotNo = (record, poNumber, checking) => {
    let result = '<option value="">N/A</option>';
    $.ajax({
        url: "get_lot_no",
        method: "get",
        dataType: "json",
        data: {
            "poNumber": poNumber,
            "checking": checking
        },
        beforeSend: function(){
            result = '<option value="" selected disabled> -- Loading -- </option>';
            record.html(result);
        },
        success: function(response){
            result = '';

            if(response['runcardDetails'].length > 0){
                result = '<option selected value="" disabled> Scan QR Code Sticker </option>';
                for(let index = 0; index < response['runcardDetails'].length; index++){
                    result += '<option value="' + response['runcardDetails'][index].id+'">'+ response['runcardDetails'][index].production_lot +'</option>';
                }
            }
            else{
                result = '<option value="0" selected disabled> No record found </option>';
            }
            record.html(result);
        }
    });
}

const GetDeliveryUpdateInfoByIdToEdit = (deliveryUpdateId) => {
	$.ajax({
        url: "get_delivery_update_by_id",
        method: "get",
        data: {
            'deliveryUpdateId'  :   deliveryUpdateId,
        },
        dataType: "json",
        beforeSend: function(){
            GetLotNo($('.lot-no'), $('#txtSearchPO').val(), 0);
            GetEmployeeName($('.select-user'))
        },

        success: function(response){
            let deliveryUpdateDetails   = response['deliveryUpdateDetails']
            if(deliveryUpdateDetails.length > 0){
                setTimeout(() => {
                    $('#txtPo').val(deliveryUpdateDetails[0].po_no)
                    $('#slctRuncardNum').val(deliveryUpdateDetails[0].runcard_id).trigger('change')
                    $('#txtDeliveryUpdateLotNum').val(deliveryUpdateDetails[0].lot_no)
                    $('#txtDeliveryUpdateLotNumExt').val(deliveryUpdateDetails[0].lot_no_ext)
                    $('#txtDeliveryUpdateRemarks').val(deliveryUpdateDetails[0].remarks)
                    $('#checkPrevActualSo').val(deliveryUpdateDetails[0].actual_so)
                    $('#slctPackageCategory').val(deliveryUpdateDetails[0].package_category).trigger('change')
                    $('#scanBy').val(deliveryUpdateDetails[0].created_by).trigger('change')

                    if(deliveryUpdateDetails[0].lot_category == 1){
                        $('#checkBoxLotCategory').prop('checked', true)
                        $('#txtDeliveryUpdateLotNum').attr('readonly', false).removeClass('pointer')
                        $('#txtDeliveryUpdateLotNumExt').prop('disabled', true)
                    }else{
                        $('#checkBoxLotCategory').prop('checked', false)
                        $('#txtDeliveryUpdateLotNum').attr('readonly', true).addClass('pointer')
                        $('#txtDeliveryUpdateLotNumExt').prop('disabled', false)
                    }
                    $('#checkBoxLotCategory').trigger('change');
                }, 777);
            }
        },
    })
}

const saveDeliveryUpdate = (form) => {
    $.ajax({
        type: "post",
        url: "save_delivery_update",
        data: form.serialize(),
        dataType: "json",
        success: function (response) {
            let actualso = response['actualso']
            if(response['hasError'] == 0){
                toastr.success('Succesfully saved!')

                $("#formSaveDeliveryUpdate")[0].reset()
                $('#modalDeliveryUpdate').modal('hide')

                dtDeliveryUpdate.draw();
                let sum = Number(actualso) + Number($('#txtTotalSO').val());
                $('#txtTotalSO').val(sum)
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status)
        }
    });
}

