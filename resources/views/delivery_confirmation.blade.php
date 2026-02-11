@php $layout = 'layouts.admin_layout'; @endphp

@auth
@extends($layout)

@section('title', 'Delivery Confirmation')

@section('content_page')
    <style>
        table.table tbody td{
            padding: 4px 4px;
            margin: 1px 1px;
            font-size: 13px;
            /* text-align: center; */
            vertical-align: middle;
        }

        table.table thead th{
            padding: 4px 4px;
            margin: 1px 1px;
            font-size: 15px;
            text-align: center;
            vertical-align: middle;
        }

        .pointer{
            pointer-events: none;
        }

        .scanner {
            position: absolute;
            opacity: 0;
        }
    </style>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Delivery Confirmation</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">Delivery Confirmation</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Delivery Details</h3>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <input type="date" class="form-control" id="txtShipmentDate" name="shipment_date1">
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="d-flex">
                                                <input type="date" class="form-control me-2" id="txtShipmentDate2" name="shipment_date2">
                                                <button class="btn btn-primary btn-sm" id="loadPOReceivedDetailsByDate" type="button">
                                                    Load
                                                </button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="d-flex">
                                        <div class="input-group input-group-sm mb-3 w-100">
                                            <span class="input-group-text w-50" id="basic-addon1">Order No.</span>
                                            <input type="text" class="form-control" name="ps_ctrl_number" id="txtPsCtrlNumber" aria-describedby="basic-addon1" />
                                            <button class="btn btn-primary btn-sm" id="loadPOReceivedDetailsByPrNumber" type="button">
                                                Load
                                            </button>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <!-- Button to open modal -->
                            <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" id="addShipmentDate" data-bs-target="#shipmentDateModal">
                            Add Shipment Date
                            </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-hover w-100" id="tblDeliveryUpdate">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Item Code</th>
                                                <th>Item Name</th>
                                                <th>Order No.</th>
                                                <th>Order Balance</th>
                                                <th>Shipment Date</th>
                                                <th>Target for S/O</th>
                                                <th>Actual SO</th>
                                                <th>Variance</th>
                                                <th>Remarks</th>
                                                <th>WIP</th>
                                                <th>FGS</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

<!-- Modal -->
<div class="modal fade" id="shipmentDateModal" tabindex="-1" aria-labelledby="shipmentDateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="shipmentDateForm" method="POST">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="shipmentDateModalLabel">Add Shipment Date</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="shipment_date" class="form-label">Shipment Date</label>
              <input type="date" class="form-control" id="shipment_date" name="shipment_date" required>
            </div>
            <input type="hidden" id="po_id" name="po_id">
            <input type="hidden" id="item_name" name="item_name">
            <input type="hidden" id="item_code" name="item_code">
            <input type="hidden" id="order_no" name="order_no">
            <input type="hidden" id="order_balance" name="order_balance">
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Date</button>
          </div>
        </div>
      </form>
    </div>
  </div>


@endsection

@section('js_content')
<script>
    $(document).ready(function(){
        $('#tblDeliveryUpdate').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "search_po_received_details",
                type: "GET",
                data: function (d) {
                    d.shipment_date1 = $('#txtShipmentDate').val();
                    d.shipment_date2 = $('#txtShipmentDate2').val();
                    d.ps_ctrl_number = $('#txtPsCtrlNumber').val();
                }
            },
            pageLength: -1, // display all
            lengthMenu: [[-1], ["All"]],
            columns: [
                {data: 'action', name: 'action', orderable: false, searchable: false},
                {data: 'item_code', name: 'item_code'},
                {data: 'item_name', name: 'item_name'},
                {data: 'order_no', name: 'order_no'},
                {data: 'order_balance', name: 'order_balance'},
                {data: 'shipment_date', name: 'shipment_date'},
                {data: 'order_balance', name: 'target_so'},
                {data: 'lot_no', name: 'lot_no'},
                {data: 'variance', name: 'variance'},
                {data: 'remarks', name: 'remarks'},
                {data: 'wip', name: 'wip'},
                {data: 'fgs', name: 'fgs'}
            ],
            "columnDefs": [
                {
                    "targets": 2,  // "Item Name" column (0-based index)
                    "width": "450px"  // Adjust width as needed
                }
            ],
            paging: false, // disables pagination UI
            order:[[1, "asc"]],
        });

        $('#loadPOReceivedDetailsByDate').click(function(){
            $('#tblDeliveryUpdate').DataTable().ajax.reload();
        });

        $('#loadPOReceivedDetailsByPrNumber').click(function(){
            $('#tblDeliveryUpdate').DataTable().ajax.reload();
        });

        // $('.po-checkbox').prop('checked', false);

                // Open the modal when the "Add Shipment Date" button is clicked
        $('#addShipmentDate').on('click', function () {
            const selectedIds = [];
            const selectedItemName = [];
            const selectedItemCode = [];
            const selectedOrderNo = [];
            const selectedOrderBalance = [];
            // const selectedPoNumbers = [];

            // Collect selected PO IDs and PO numbers
            $('.po-checkbox:checked').each(function () {
                const poId = $(this).data('id');
                const itemName = $(this).data('item_name');
                const itemCode = $(this).data('item_code');
                const orderNo = $(this).data('order_no');
                const orderBalance = $(this).data('order_balance');
                // const poNumber = $(this).data('po-number');
                selectedIds.push(poId);
                selectedItemName.push(itemName);
                selectedItemCode.push(itemCode);
                selectedOrderNo.push(orderNo);
                selectedOrderBalance.push(orderBalance);
                // selectedPoNumbers.push(poNumber);
            });

            // If no checkboxes are selected, show an alert and stop
            if (selectedIds.length === 0) {
                alert('Please select at least one PO.');
                return;
            }

            // Display the selected PO numbers in the modal
            // $('#selectedPoList').html('Selected PO Numbers: ' + selectedPoNumbers.join(', '));

            // Store the selected PO IDs in the hidden input field for form submission
            $('#po_id').val(selectedIds.join(','));
            $('#item_name').val(selectedItemName.join(','));
            $('#item_code').val(selectedItemCode.join(','));
            $('#order_no').val(selectedOrderNo.join(','));
            $('#order_balance').val(selectedOrderBalance.join(','));

            // Show the modal
            $('#shipmentDateModal').modal('show');
        });

        // Handle form submission
        $('#shipmentDateForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the form from refreshing the page

            // Collect form data (including selected PO IDs and shipment date)
            const formData = $(this).serialize();

            $.ajax({
                url: 'add_shipment_date', // Change to your route
                method: 'POST',
                data: formData,
                success: function (response) {
                    $('#shipmentDateModal').modal('hide'); // Hide the modal
                    $('#tblDeliveryUpdate').DataTable().ajax.reload(); // Reload the table (if needed)
                    alert('Shipment date added successfully!');
                },
                error: function () {
                    alert('Something went wrong.');
                }
            });
        });

        // Select or deselect all checkboxes
        $('#selectAll').on('click', function () {
            const isChecked = $(this).prop('checked');
            $('.po-checkbox').prop('checked', isChecked);
        });

    });

</script>
@endsection
@endauth
