@extends('admin.base_layout')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Supplier List</h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="{{ route('admin.create.supplier') }}" class="btn btn-primary btn-icon-split float-right btn-sm">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">New Supplier</span>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Supplier Name</th>
                            <th>Address</th>
                            <th>Contact Person</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Supplier Name</th>
                            <th>Address</th>
                            <th>Contact Person</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <tr>
                            <td>Tiger Corporation</td>
                            <td>123 Main St, Anytown</td>
                            <td>John Doe</td>
                            <td>123-456-7890</td>
                            <td>john@example.com</td>
                        </tr>
                        <tr>
                            <td>ABC Suppliers</td>
                            <td>456 Elm St, Othertown</td>
                            <td>Jane Smith</td>
                            <td>987-654-3210</td>
                            <td>jane@example.com</td>
                        </tr>
                        <!-- Add more supplier data rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
@endsection
