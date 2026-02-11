function AddShipmentData(){
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };

    let form_data = new FormData($('#formAddShipmentData')[0]);

    $.ajax({
        url: "add_shipment_data",
        method: "post",
        processData: false,
        contentType: false,
        data: form_data,
        dataType: "json",
        beforeSend: function(){
            $("#btnSubmitShipmentDataDefIcon").removeClass('fa fa-upload')
            $("#btnSubmitShipmentDataDefIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnSubmitShipmentData").prop('disabled', 'disabled');
        },
        success: function(JsonObject){

            $("#btnSubmitShipmentDataDefIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnSubmitShipmentData").removeAttr('disabled');
            $("#btnSubmitShipmentDataDefIcon").addClass('fa fa-upload');

            if(JsonObject['result'] == 1){
              $("#modalAddShipmentData").modal('hide');
              $("#formAddShipmentData")[0].reset();
              
              dtShipmentTable.draw();
              toastr.success('Data succesfully saved!');       
            }
            else{
                toastr.error(' Saving Request Failed!');

                // if(JsonObject['result']['shipment_date'] === undefined){
                //     $("#txtShipmentDate").removeClass('is-invalid');
                //     $("#txtShipmentDate").attr('title', '');
                // }
                // else{
                //     $("#txtShipmentDate").addClass('is-invalid');
                //     $("#txtShipmentDate").attr('title', JsonObject['error']['shipment_date']);
                // }

                // if(JsonObject['result']['sold_to'] === undefined){
                //     $("#txtSoldTo").removeClass('is-invalid');
                //     $("#txtSoldTo").attr('title', '');
                // }
                // else{
                //     $("#txtSoldTo").addClass('is-invalid');
                //     $("#txtSoldTo").attr('title', JsonObject['result']['sold_to']);
                // }

                // if(JsonObject['result']['preShipment_ctrl'] === undefined){
                //     $("#txtPreShipmentCtrl").removeClass('is-invalid');
                //     $("#txtPreShipmentCtrl").attr('title', '');
                // }
                // else{
                //     $("#txtPreShipmentCtrl").addClass('is-invalid');
                //     $("#txtPreShipmentCtrl").attr('title', JsonObject['result']['preShipment_ctrl']);
                // }

                // if(JsonObject['result']['cut_off_date'] === undefined){
                //     $("#txtCutOffDate").removeClass('is-invalid');
                //     $("#txtCutOffDate").attr('title', '');
                // }
                // else{
                //     $("#txtCutOffDate").addClass('is-invalid');
                //     $("#txtCutOffDate").attr('title', JsonObject['result']['cut_off_date']);
                // }
            }

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);      
            $("#btnSubmitShipmentDataDefIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnSubmitShipmentData").removeAttr('disabled');
            $("#btnSubmitShipmentDataDefIcon").addClass('fa fa-upload');
        }
    });
}