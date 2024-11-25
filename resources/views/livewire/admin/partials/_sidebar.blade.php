<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a wire:navigate class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon d-none">
            <img src="{{ asset('assets/img/final_logo.png') }}" alt="Logo" style="width:40px;">
        </div>
        <div class="sidebar-brand-text mt-2">
            <img src="{{ asset('assets/img/left_logo.png') }}" alt="Brand" style="width:200px;">
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ isActiveRoute('admin.dashboard') }}">
        <a wire:navigate class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    @hasrole('admin')
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Manage
    </div>

    <!-- Products Collapse Menu -->
    <li class="nav-item {{ isActiveRoute(['admin.product', 'admin.create.product']) }}">
        <a class="nav-link {{ isActiveCollapse(['admin.product', 'admin.create.product']) }}" href="#" data-toggle="collapse" data-target="#collapseProducts"
           aria-expanded="{{ isActiveRoute(['admin.product', 'admin.create.product']) ? 'true' : 'false' }}" aria-controls="collapseProducts">
            <i class="fas fa-fw fa-chair"></i>
            <span>Products</span>
        </a>
        <div id="collapseProducts" class="collapse {{ isActiveRoute(['admin.product', 'admin.create.product']) ? 'show' : '' }}" aria-labelledby="headingProducts" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.product') }}" href="{{ route('admin.product') }}">List</a>
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.create.product') }}" href="{{ route('admin.create.product') }}">New Product</a>
            </div>
        </div>
    </li>

    <!-- Branches Collapse Menu -->
    <li class="nav-item {{ isActiveRoute(['admin.branch', 'admin.create.branch']) }}">
        <a class="nav-link {{ isActiveCollapse(['admin.branch', 'admin.create.branch']) }}" href="#" data-toggle="collapse" data-target="#collapseBranches"
           aria-expanded="{{ isActiveRoute(['admin.branch', 'admin.create.branch']) ? 'true' : 'false' }}" aria-controls="collapseBranches">
            <i class="fas fa-fw fa-code-branch"></i>
            <span>Branch</span>
        </a>
        <div id="collapseBranches" class="collapse {{ isActiveRoute(['admin.branch', 'admin.create.branch']) ? 'show' : '' }}" aria-labelledby="headingBranches" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.branch') }}" href="{{ route('admin.branch') }}">List</a>
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.create.branch') }}" href="{{ route('admin.create.branch') }}">New Branch</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ isActiveRoute(['admin.supplier', 'admin.create.supplier']) }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
           aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-box-open"></i>
            <span>Supplier</span>
        </a>
        <div id="collapseTwo" class="collapse {{ isActiveRoute(['admin.supplier', 'admin.create.supplier']) ? 'show' : ''  }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.supplier') }}" href="{{ route('admin.supplier') }}">List</a>
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.create.supplier') }}" href="{{ route('admin.create.supplier') }}">New Supplier</a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ isActiveRoute(['admin.users', 'admin.create.user']) }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree"
           aria-expanded="true" aria-controls="collapseThree">
            <i class="fas fa-fw fa-users"></i>
            <span>Users</span>
        </a>
        <div id="collapseThree" class="collapse {{ isActiveRoute(['admin.users', 'admin.create.user']) ? 'show' : ''  }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.users') }}" href="{{ route('admin.users') }}">List</a>
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.create.user') }}" href="{{ route('admin.create.user') }}">New User</a>
            </div>
        </div>
    </li>


    @endhasrole

    @hasanyrole('admin|inventory_head')
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Stocks
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    {{--    <li class="nav-item">--}}
    {{--        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFour"--}}
    {{--            aria-expanded="true" aria-controls="collapseFour">--}}
    {{--            <i class="fas fa-fw fa-users"></i>--}}
    {{--            <span>Receive Product</span>--}}
    {{--        </a>--}}
    {{--        <div id="collapseFour" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">--}}
    {{--            <div class="bg-white py-2 collapse-inner rounded">--}}
    {{--                <a wire:navigate class="collapse-item" href="{{ route('admin.receive.product') }}">Receive</a>--}}
    {{--                <a wire:navigate class="collapse-item" href="{{ route('admin.receive.product.history') }}">History</a>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </li>--}}

    <!-- Manage Stocks Collapse Menu -->
    <li class="nav-item {{ isActiveRoute(['admin.manage.stock', 'admin.receive.product.history']) }}">
        <a class="nav-link {{ isActiveCollapse(['admin.manage.stock', 'admin.receive.product.history']) }}" href="#" data-toggle="collapse" data-target="#collapseStockManage"
           aria-expanded="{{ isActiveRoute(['admin.manage.stock', 'admin.receive.product.history']) ? 'true' : 'false' }}" aria-controls="collapseStockManage">
            <i class="fas fa-fw fa-box-open"></i>
            <span>Manage</span>
        </a>
        <div id="collapseStockManage" class="collapse {{ isActiveRoute(['admin.manage.stock', 'admin.receive.product.history']) ? 'show' : '' }}" aria-labelledby="headingStockManage" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.manage.stock') }}" href="{{ route('admin.manage.stock') }}">Products</a>
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.receive.product.history') }}" href="{{ route('admin.receive.product.history') }}">History</a>
            </div>
        </div>
    </li>

    <!-- Batch Receive Collapse Menu -->
    <li class="nav-item {{ isActiveRoute(['admin.receive.product', 'admin.receive.history']) }}">
        <a class="nav-link {{ isActiveCollapse(['admin.receive.product', 'admin.receive.history']) }}" href="#" data-toggle="collapse" data-target="#collapseBatchReceive"
           aria-expanded="{{ isActiveRoute(['admin.receive.product', 'admin.receive.history']) ? 'true' : 'false' }}" aria-controls="collapseBatchReceive">
            <i class="fas fa-fw fa-users"></i>
            <span>Batch Receive</span>
        </a>
        <div id="collapseBatchReceive" class="collapse {{ isActiveRoute(['admin.receive.product', 'admin.receive.history']) ? 'show' : '' }}" aria-labelledby="headingBatchReceive" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.receive.product') }}" href="{{ route('admin.receive.product') }}">Receive</a>
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.receive.history') }}" href="{{ route('admin.receive.history') }}">History</a>
            </div>
        </div>
    </li>

    <!-- Request Transfer Collapse Menu -->
    <li class="nav-item {{ isActiveRoute(['admin.request.transfer', 'admin.view.request', 'admin.transfer.history']) }}">
        <a class="nav-link {{ isActiveCollapse(['admin.request.transfer', 'admin.view.request', 'admin.transfer.history']) }}" href="#" data-toggle="collapse" data-target="#collapseRequestTransfer"
           aria-expanded="{{ isActiveRoute(['admin.request.transfer', 'admin.view.request', 'admin.transfer.history']) ? 'true' : 'false' }}" aria-controls="collapseRequestTransfer">
            <i class="fas fa-fw fa-users"></i>
            <span>Request Transfer</span>
        </a>
        <div id="collapseRequestTransfer" class="collapse {{ isActiveRoute(['admin.request.transfer', 'admin.view.request', 'admin.transfer.history']) ? 'show' : '' }}" aria-labelledby="headingRequestTransfer" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.request.transfer') }}" href="{{ route('admin.request.transfer') }}">Request Transfer</a>
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.view.request') }}" href="{{ route('admin.view.request') }}">View Requests</a>
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.transfer.history') }}" href="{{ route('admin.transfer.history') }}">Transfer History</a>
            </div>
        </div>
    </li>

    <!-- Delivery Collapse Menu -->
    <li class="nav-item {{ isActiveRoute(['admin.delivery.to-ship', 'admin.delivery.out-for-delivery', 'admin.delivery.delivered', 'admin.delivery.history']) }}">
        <a class="nav-link {{ isActiveCollapse(['admin.delivery.to-ship', 'admin.delivery.out-for-delivery', 'admin.delivery.delivered', 'admin.delivery.history']) }}" href="#" data-toggle="collapse" data-target="#collapseDelivery"
           aria-expanded="{{ isActiveRoute(['admin.delivery.to-ship', 'admin.delivery.out-for-delivery', 'admin.delivery.delivered', 'admin.delivery.history']) ? 'true' : 'false' }}" aria-controls="collapseDelivery">
            <i class="fas fa-fw fa-users"></i>
            <span>Delivery</span>
        </a>
        <div id="collapseDelivery" class="collapse {{ isActiveRoute(['admin.delivery.to-ship', 'admin.delivery.out-for-delivery', 'admin.delivery.delivered', 'admin.delivery.history']) ? 'show' : '' }}" aria-labelledby="headingDelivery" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.delivery.to-ship') }}" href="{{ route('admin.delivery.to-ship') }}">To Ship</a>
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.delivery.out-for-delivery') }}" href="{{ route('admin.delivery.out-for-delivery') }}">Out for Delivery</a>
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.delivery.delivered') }}" href="{{ route('admin.delivery.delivered') }}">Delivered</a>
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.delivery.history') }}" href="{{ route('admin.delivery.history') }}">History</a>
            </div>
        </div>
    </li>

    @endhasanyrole

    @hasanyrole('admin|sales_rep|cashier')
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Browse Products -->
    <li class="nav-item {{ isActiveRoute('admin.browse.product') }}">
        <a wire:navigate class="nav-link" href="{{ route('admin.browse.product') }}">
            <i class="fas fa-fw fa-store"></i>
            <span>Browse Products</span>
        </a>
    </li>
    @endhasanyrole

    @hasanyrole('admin|cashier')
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Orders
    </div>

    <!-- Regular Order Collapse Menu -->
    <li class="nav-item {{ isActiveRoute(['admin.orders']) }}">
        <a class="nav-link {{ isActiveCollapse(['admin.orders']) }}" href="#" data-toggle="collapse" data-target="#collapseOrders"
           aria-expanded="{{ isActiveRoute(['admin.orders']) ? 'true' : 'false' }}" aria-controls="collapseOrders">
            <i class="fas fa-fw fa-users"></i>
            <span>Regular Order</span>
        </a>
        <div id="collapseOrders" class="collapse {{ isActiveRoute(['admin.orders']) ? 'show' : '' }}" aria-labelledby="headingOrders" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item {{ request()->query('type') == 'regular' && request()->query('status') == 'pending' ? 'active' : '' }}" href="{{ route('admin.orders', ['type' => 'regular', 'status' => 'pending']) }}">Pending</a>
                <a wire:navigate class="collapse-item {{ request()->query('type') == 'regular' && request()->query('status') == 'processed' ? 'active' : '' }}" href="{{ route('admin.orders', ['type' => 'regular', 'status' => 'processed']) }}">Processed</a>
                <a wire:navigate class="collapse-item {{ request()->query('type') == 'regular' && request()->query('status') == 'cancelled' ? 'active' : '' }}" href="{{ route('admin.orders', ['type' => 'regular', 'status' => 'cancelled']) }}">Cancelled</a>
                <a wire:navigate class="collapse-item {{ request()->query('type') == 'regular' && request()->query('status') == 'refunded' ? 'active' : '' }}" href="{{ route('admin.orders', ['type' => 'regular', 'status' => 'refunded']) }}">Refunded</a>
            </div>
        </div>
    </li>

    <!-- Purchase Order Collapse Menu -->
    <li class="nav-item {{ isActiveRoute(['admin.orders']) }}">
        <a class="nav-link {{ isActiveCollapse(['admin.orders']) }}" href="#" data-toggle="collapse" data-target="#collapsePurchaseOrder"
           aria-expanded="{{ isActiveRoute(['admin.orders']) ? 'true' : 'false' }}" aria-controls="collapsePurchaseOrder">
            <i class="fas fa-fw fa-users"></i>
            <span>Purchase Order</span>
        </a>
        <div id="collapsePurchaseOrder" class="collapse {{ isActiveRoute(['admin.orders']) ? 'show' : '' }}" aria-labelledby="headingPurchaseOrder" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item {{ request()->query('type') == 'purchase' && request()->query('status') == 'pending' ? 'active' : '' }}" href="{{ route('admin.orders', ['type' => 'purchase', 'status' => 'pending']) }}">Pending</a>
                <a wire:navigate class="collapse-item {{ request()->query('type') == 'purchase' && request()->query('status') == 'processed' ? 'active' : '' }}" href="{{ route('admin.orders', ['type' => 'purchase', 'status' => 'processed']) }}">Processed</a>
                <a wire:navigate class="collapse-item {{ request()->query('type') == 'purchase' && request()->query('status') == 'cancelled' ? 'active' : '' }}" href="{{ route('admin.orders', ['type' => 'purchase', 'status' => 'cancelled']) }}">Cancelled</a>
                <a wire:navigate class="collapse-item {{ request()->query('type') == 'purchase' && request()->query('status') == 'refunded' ? 'active' : '' }}" href="{{ route('admin.orders', ['type' => 'purchase', 'status' => 'refunded']) }}">Refunded</a>
            </div>
        </div>
    </li>

    <!-- Custom Order Collapse Menu -->
    <li class="nav-item {{ isActiveRoute(['admin.orders']) }}">
        <a class="nav-link {{ isActiveCollapse(['admin.orders']) }}" href="#" data-toggle="collapse" data-target="#collapseCustomOrder"
           aria-expanded="{{ isActiveRoute(['admin.orders']) ? 'true' : 'false' }}" aria-controls="collapseCustomOrder">
            <i class="fas fa-fw fa-users"></i>
            <span>Custom Order</span>
        </a>
        <div id="collapseCustomOrder" class="collapse {{ isActiveRoute(['admin.orders']) ? 'show' : '' }}" aria-labelledby="headingCustomOrder" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item {{ request()->query('type') == 'custom' && request()->query('status') == 'pending' ? 'active' : '' }}" href="{{ route('admin.orders', ['type' => 'custom', 'status' => 'pending']) }}">Pending</a>
                <a wire:navigate class="collapse-item {{ request()->query('type') == 'custom' && request()->query('status') == 'processed' ? 'active' : '' }}" href="{{ route('admin.orders', ['type' => 'custom', 'status' => 'processed']) }}">Processed</a>
                <a wire:navigate class="collapse-item {{ request()->query('type') == 'custom' && request()->query('status') == 'cancelled' ? 'active' : '' }}" href="{{ route('admin.orders', ['type' => 'custom', 'status' => 'cancelled']) }}">Cancelled</a>
                <a wire:navigate class="collapse-item {{ request()->query('type') == 'custom' && request()->query('status') == 'refunded' ? 'active' : '' }}" href="{{ route('admin.orders', ['type' => 'custom', 'status' => 'refunded']) }}">Refunded</a>
            </div>
        </div>
    </li>

    <!-- Customer Collapse Menu -->
    <li class="nav-item {{ isActiveRoute(['admin.customer.list']) }}">
        <a class="nav-link {{ isActiveCollapse(['admin.customer.list']) }}" href="#" data-toggle="collapse" data-target="#collapseCustomer"
           aria-expanded="{{ isActiveRoute(['admin.customer.list']) ? 'true' : 'false' }}" aria-controls="collapseCustomer">
            <i class="fas fa-fw fa-users"></i>
            <span>Customer</span>
        </a>
        <div id="collapseCustomer" class="collapse {{ isActiveRoute(['admin.customer.list']) ? 'show' : '' }}" aria-labelledby="headingCustomer" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.customer.list') }}" href="{{ route('admin.customer.list') }}">List</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Manage Expenses -->
    <li class="nav-item {{ isActiveRoute('admin.expense') }}">
        <a wire:navigate class="nav-link" href="{{ route('admin.expense') }}">
            <i class="fas fa-fw fa-store"></i>
            <span>Manage Expenses</span>
        </a>
    </li>
    @endhasanyrole

    @hasanyrole('admin|cashier')
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Reports Collapse Menu -->
    <li class="nav-item {{ isActiveRoute(['admin.reports.daily', 'admin.reports.monthly']) }}">
        <a class="nav-link {{ isActiveCollapse(['admin.reports.daily', 'admin.reports.monthly']) }}" href="#" data-toggle="collapse" data-target="#collapseReports"
           aria-expanded="{{ isActiveRoute(['admin.reports.daily', 'admin.reports.monthly']) ? 'true' : 'false' }}" aria-controls="collapseReports">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Reports</span>
        </a>
        <div id="collapseReports" class="collapse {{ isActiveRoute(['admin.reports.daily', 'admin.reports.monthly']) ? 'show' : '' }}" aria-labelledby="headingReports" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.reports.daily') }}" href="{{ route('admin.reports.daily') }}">Daily Report</a>
                <a wire:navigate class="collapse-item {{ isActiveRoute('admin.reports.monthly') }}" href="{{ route('admin.reports.monthly') }}">Monthly Report</a>
            </div>
        </div>
    </li>
    @endhasanyrole

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
