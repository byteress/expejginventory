<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a wire:navigate class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">

        <div class="sidebar-brand-icon d-none">
            <img src="{{ asset('assets/img/final_logo.png') }}" alt="" style = "width:40px;">
        </div>
        {{-- <div class="sidebar-brand-text mx-2"><span class ="primary-text">JENNY</span><span class ="secondary-text">Joy</span><sup>GRACE</sup></div> --}}
        <div class="sidebar-brand-text mt-2"><img src="{{ asset('assets/img/left_logo.png') }}" alt="" style = "width:200px;"></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a wire:navigate class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    @hasrole('admin')
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Manage
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ request()->routeIs('admin.product', 'admin.create.product') ? 'active' : '' }}">
        <a class="nav-link {{ request()->routeIs('admin.product', 'admin.create.product') ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseZero"
            aria-expanded="true" aria-controls="collapseZero">
            <i class="fas fa-fw fa-chair"></i>
            <span>Products</span>
        </a>
        <div id="collapseZero" class="collapse {{ request()->routeIs('admin.product', 'admin.create.product') ? 'show' : '' }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item {{ request()->routeIs('admin.product') ? 'active' : '' }}" href="{{ route('admin.product') }}">List</a>
                <a wire:navigate class="collapse-item {{ request()->routeIs('admin.create.product') ? 'active' : '' }}" href="{{ route('admin.create.product') }}">New Product</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne"
            aria-expanded="true" aria-controls="collapseOne">
            <i class="fas fa-fw fa-code-branch"></i>
            <span>Branch</span>
        </a>
        <div id="collapseOne" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item" href="{{ route('admin.branch') }}">List</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.create.branch') }}">New Branch</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-box-open"></i>
            <span>Supplier</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item" href="{{ route('admin.supplier') }}">List</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.create.supplier') }}">New Supplier</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree"
            aria-expanded="true" aria-controls="collapseThree">
            <i class="fas fa-fw fa-users"></i>
            <span>Users</span>
        </a>
        <div id="collapseThree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item" href="{{ route('admin.users') }}">List</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.create.user') }}">New User</a>
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

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseStockManage"
           aria-expanded="true" aria-controls="collapseStockManage">
            <i class="fas fa-fw fa-users"></i>
            <span>Manage</span>
        </a>
        <div id="collapseStockManage" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item" href="{{ route('admin.manage.stock') }}">Products</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.receive.product.history') }}">History</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBatchReceive"
           aria-expanded="true" aria-controls="collapseBatchReceive">
            <i class="fas fa-fw fa-users"></i>
            <span>Batch Receive</span>
        </a>
        <div id="collapseBatchReceive" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item" href="{{ route('admin.receive.product') }}">Receive</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.receive.history') }}">History</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFive"
            aria-expanded="true" aria-controls="collapseFive">
            <i class="fas fa-fw fa-users"></i>
            <span>Request Transfer</span>
        </a>
        <div id="collapseFive" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item" href="{{ route('admin.request.transfer') }}">Request Transfer</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.view.request') }}">View Requests</a>
                {{-- <a wire:navigate class="collapse-item" href="{{ route('admin.for.transfer') }}">For Transfer</a> --}}
{{--                <a wire:navigate class="collapse-item" href="{{ route('admin.incoming') }}">Incoming</a>--}}
                <a wire:navigate class="collapse-item" href="{{ route('admin.transfer.history') }}">Transfer History</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDelivery"
           aria-expanded="true" aria-controls="collapseDelivery">
            <i class="fas fa-fw fa-users"></i>
            <span>Delivery</span>
        </a>
        <div id="collapseDelivery" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item" href="{{ route('admin.delivery.to-ship') }}">To Ship</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.delivery.out-for-delivery') }}">Out for Delivery</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.delivery.delivered') }}">Delivered</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.delivery.history') }}">History</a>
            </div>
        </div>
    </li>
    @endhasanyrole

    @hasanyrole('admin|sales_rep|cashier')
    <hr class="sidebar-divider">

    <li class="nav-item ">
        <a wire:navigate class="nav-link" href="{{ route('admin.browse.product') }}">
            <i class="fas fa-fw fa-store"></i>
            <span>Browse Products</span></a>
    </li>
    @endhasanyrole

    @hasanyrole('admin|cashier')
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Orders
    </div>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSix"
            aria-expanded="true" aria-controls="collapseSix">
            <i class="fas fa-fw fa-users"></i>
            <span>Regular Order</span>
        </a>
        <div id="collapseSix" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item" href="{{ route('admin.orders', ['type' => 'regular', 'status' => 'pending']) }}">Pending</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.orders', ['type' => 'regular', 'status' => 'processed']) }}">Processed</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.orders', ['type' => 'regular', 'status' => 'cancelled']) }}">Cancelled</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.orders', ['type' => 'regular', 'status' => 'refunded']) }}">Refunded</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#purchaseOrder"
           aria-expanded="true" aria-controls="purchaseOrder">
            <i class="fas fa-fw fa-users"></i>
            <span>Purchase Order</span>
        </a>
        <div id="purchaseOrder" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item" href="{{ route('admin.orders', ['type' => 'purchase', 'status' => 'pending']) }}">Pending</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.orders', ['type' => 'purchase', 'status' => 'processed']) }}">Processed</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.orders', ['type' => 'purchase', 'status' => 'cancelled']) }}">Cancelled</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.orders', ['type' => 'purchase', 'status' => 'refunded']) }}">Refunded</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#customORder"
           aria-expanded="true" aria-controls="customORder">
            <i class="fas fa-fw fa-users"></i>
            <span>Custom Order</span>
        </a>
        <div id="customORder" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item" href="{{ route('admin.orders', ['type' => 'custom', 'status' => 'pending']) }}">Pending</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.orders', ['type' => 'custom', 'status' => 'processed']) }}">Processed</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.orders', ['type' => 'custom', 'status' => 'cancelled']) }}">Cancelled</a>
                <a wire:navigate class="collapse-item" href="{{ route('admin.orders', ['type' => 'custom', 'status' => 'refunded']) }}">Refunded</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#customer"
           aria-expanded="true" aria-controls="customer">
            <i class="fas fa-fw fa-users"></i>
            <span>Customer</span>
        </a>
        <div id="customer" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a wire:navigate class="collapse-item" href="{{ route('admin.customer.list') }}">List</a>
            </div>
        </div>
    </li>

    <hr class="sidebar-divider">

    <li class="nav-item ">
        <a wire:navigate class="nav-link" href="{{ route('admin.expense') }}">
            <i class="fas fa-fw fa-store"></i>
            <span>Manage Expenses</span></a>
    </li>
    @endhasanyrole

    @hasanyrole('admin|cashier')
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
            aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Reports</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a href="{{ route('admin.reports.daily') }}" wire:navigate class="collapse-item" href="#">Daily Report</a>
                <a href="{{ route('admin.reports.daily.items') }}" wire:navigate class="collapse-item" href="#">Daily Item Report</a>
                <a href="{{ route('admin.reports.monthly') }}" wire:navigate class="collapse-item" href="#">Monthly Report</a>
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
