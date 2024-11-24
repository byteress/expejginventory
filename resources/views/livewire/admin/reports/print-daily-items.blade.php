<div class="container-fluid">

    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h3 mb-2 text-primary admin-title"><strong>Daily Items Reports</strong></h1>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <table class="table">
                <thead style="background-color: #f4b083;color:#fff">
                <tr>
                    <th>Item Name: {{ $item_name  }}</th>
                    <th>Opening Quantity:</th>
                    <th>Closing Quantity:</th>
                    <th>Branch: {{ $branch_name  }}</th>
                </tr>
                </thead>
                <tbody>
                <!-- Sold Section -->
                <tr class="table-a text-center" style="background-color: #8eaadb;color:#FFF">
                    <th colspan="4">SOLD</th>
                </tr>
                <tr>
                    <th>Quantity:</th>
                    <th>Cashier:</th>
                    <th>Receipt:</th>
                    <th>Date/Time:</th>
                </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <tr>
                    <td>Total:</td>
                    <td colspan="3"></td>
                </tr>

                <!-- Received Section -->
                <tr class="table-a text-center" style="background-color: #8eaadb;color:#FFF">
                    <th colspan="4">RECEIVED</th>
                </tr>
                <tr>
                    <th>Quantity:</th>
                    <th>From:</th>
                    <th>Date/Time:</th>
                    <th></th>

                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Total:</td>
                    <td colspan="3"></td>
                </tr>

                <!-- Transferred Section -->
                <tr class="table-a text-center" style="background-color: #8eaadb;color:#FFF">
                    <th colspan="4">TRANSFERRED</th>
                </tr>
                <tr>
                    <th>Quantity:</th>
                    <th>To:</th>
                    <th>Date/Time:</th>
                    <th></th>
                </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <tr>
                    <td>Total:</td>
                    <td colspan="3"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
