<div>
    <!-- Begin Page Content -->
    <div class="container-fluid">

    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <h1 class="h3 text-primary admin-title mb-0"><strong>Customers</strong></h1>
                </div>
            </div>
        </div>
    </div>
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-3 offset-md-9">
                        <div class="form-group">
                            <input type="text" class="float-left form-control" placeholder="Search..."
                                   wire:model.live="search">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" width="100%" cellspacing="0">
                        <thead>
                        <tr class="bg-secondary font-w">
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Birthday</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Balance</th>
                            <th><center>Action</center></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td>{{ $customer->first_name }}</td>
                                <td>{{ $customer->last_name }}</td>
                                <td>{{ $customer->dob }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>@money($customer->balance ?? 0)</td>
                                <td align="center"><a href="{{ route('admin.customer.details', ['customer' => $customer->id]) }}"
                                       class="btn btn-primary">View</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">No data found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $customers->links() }}
                </div>
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->

</div>
