// let dtDHDMonitoring;
// $(document).ready(function(){

//     const dtDHDMonitoring = $("#tblDHDMonitoring").DataTable({
//         "processing" : true,
//         "bDestroy" : true,
//         "serverSide" : true,
//         "lengthMenu": [ [25, -1], [25, "All"] ],
//         "ajax" : {
//             url: "view_dhd_monitoring",
//             type: "GET",
//             // data: function (param){
//                 // param.device_name = $("#txtSelectDeviceName").val();
//             // }
//         },
//         // fixedHeader: true,
//         "columns":[
//             { "data" : "action", orderable:false, searchable:false },
//             { "data" : "created_at" },
//             { "data" : "dhd_no" },
//             { "data" : "device_code" },
//             { "data" : "device_name" },
//             { "data" : "device_name" },
//             // { "data" : "device_name" },
//             // { "data" : "device_name" },
//             // { "data" : "device_name" },
//             // { "data" : "device_name" },
//             // { "data" : "device_name" },
//             // { "data" : "device_name" },
//             // { "data" : "device_name" },
//             // { "data" : "device_name" },
//             // { "data" : "person_incharge" },
//             // { "data" : "qc_inspector" },
//             // { "data" : "remarks" },
//         ],
//         // "columnDefs": [
//         //     {"className": "dt-center", "targets": "_all"},
//         //     {
//         //         "targets": [2],
//         //         "data": null,
//         //         "defaultContent": "---"
//         //     },
//         // ],
//         // "order": [0, 'desc']
//     });

//     // dtDHDMonitoring = $("#tblDHDMonitoring").DataTable({
//     //     "processing" : true,
//     //     "serverSide" : true,
//     //     "ajax" : {
//     //         url: "view_dhd_monitoring",
//     //     },
//     //     fixedHeader: true,
//     //     "columns":[

//     //         { "data" : "action", orderable:false, searchable:false },
//     //         { "data" : "created_at" },
//     //         { "data" : "dhd_no" },
//     //         { "data" : "device_code" },
//     //         { "data" : "device_name" },
//     //         { "data" : "person_incharge" },
//     //         { "data" : "qc_inspector" },
//     //         { "data" : "remarks" }
//     //     ],
//     // });

//     $('#formDHDMonitoring').submit(function (e) {
//         e.preventDefault();
//         console.log('sfsdf');

//         $.ajax({
//             type: "post",
//             url: "add_dhd_monitoring",
//             data: $('#formDHDMonitoring').serialize(),
//             dataType: "json",
//             success: function (response) {
//                 if(response['result'] == 1){
//                     dtDHDMonitoring.draw();
//                     $('#modalAddDHD').modal('hide');
//                 }
//             }
//         });

//     });

//     // $(document).on('click', '.btnEdit', function(e){
//     //     let id = $(this).data('id');
//     //     $.ajax({
//     //         type: "get",
//     //         url: "get_dhd_monitoring",
//     //         data: {
//     //             "id" : id
//     //         },
//     //         dataType: "json",
//     //         success: function (response) {

//     //             $('#txtDHDId').val(response['id']);
//     //             $('#txtDHDNo').val(response['dhd_no']);
//     //             $('#txtDeviceName').val(response['device_name']);
//     //             $('#txtDeviceCode').val(response['device_code']);
//     //             $('#txtMaterialName').val(response['mtl_name']);
//     //             $('#txtMaterialLotVirgin').val(response['mtl_lot_virgin']);
//     //             $('#txtMaterialLotRecycle').val(response['mtl_lot_recycle']);
//     //             $('#txtMaterialMixVirgin').val(response['mtl_mix_virgin']);
//     //             $('#txtMaterialMixRecycle').val(response['mtl_mix_recycle']);
//     //             $('#txtMaterialTotalMixing').val(response['mtl_ttl_mixing']);
//     //             $('#txtMaterialDrySetting').val(response['mtl_dry_setting']);
//     //             $('#txtMaterialDryActual').val(response['mtl_dry_actual']);
//     //             $('#txtMaterialDryTimeIn').val(response['mtl_dry_timeIn']);
//     //             $('#txtMaterialDryTimeOut').val(response['mtl_dry_timeOut']);
//     //             $('#txtDHDAActualTemp').val(response['dhd_ashift_actual_temp']);
//     //             $('#txtDHDAMtlLevel').val(response['dhd_ashift_mtl_level']);
//     //             $('#txtDHDATime').val(response['dhd_ashift_time']);
//     //             $('#txtDHDBActualTemp').val(response['dhd_bshift_actual_temp']);
//     //             $('#txtDHDBMtlLevel').val(response['dhd_bshift_mtl_level']);
//     //             $('#txtDHDBTime').val(response['dhd_bshift_time']);
//     //             $('#txtPersonIncharge').val(response['person_incharge']);
//     //             $('#txtQCInspector').val(response['qc_inspector']);
//     //             $('#txtRemarks').val(response['remarks']);

//     //             $('#modalAddDHD').modal('show');

//     //         }
//     //     });
//     // });

//     // $("#txtMaterialMixRecycle").keyup(function(){
//     //     $("#txtMaterialTotalMixing").val(parseInt($("#txtMaterialMixVirgin").val()) + parseInt($(this).val()));
//     // });
// });
