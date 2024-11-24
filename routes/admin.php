<?php

use App\Livewire\Admin\Delivery\Delivered;
use App\Livewire\Admin\Delivery\DeliveryDetails;
use App\Livewire\Admin\Delivery\OutForDelivery;
use App\Livewire\Admin\Delivery\ToShip;
use App\Livewire\Admin\Payment\CustomerList;
use App\Livewire\Admin\Reports\Monthly;
use App\Livewire\Admin\Stock\BatchDetails;
use App\Livewire\Admin\Stock\BatchHistory;
use App\Livewire\Admin\Stock\StockManagement;
use App\Livewire\Admin\Stock\TransferHistory;
use App\Livewire\Admin\Stock\TransferHistoryDetails;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Branch\Branches;
use App\Livewire\Admin\Branch\CreateBranch;
use App\Livewire\Admin\Branch\EditBranch;
use App\Livewire\Admin\User\CreateUser;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Order\History;
use App\Livewire\Admin\Product\CreateProduct;
use App\Livewire\Admin\Product\EditProduct;
use App\Livewire\Admin\Product\Products;
use App\Livewire\Admin\Supplier\CreateSupplier;
use App\Livewire\Admin\Supplier\EditSupplier;
use App\Livewire\Admin\Supplier\Suppliers;
use App\Livewire\Admin\Stock\ReceiveProducts;
use App\Livewire\Admin\Stock\ReceiveProductsHistory;
use App\Livewire\Admin\Stock\ViewRequest;
use App\Livewire\Admin\Stock\ForTransfer;
use App\Livewire\Admin\Stock\Incoming;
use App\Livewire\Admin\Stock\RequestTransfer;
use App\Livewire\Admin\Stock\RequestHistory;
use App\Livewire\Admin\Stock\TransferDetails;
use App\Livewire\Admin\User\EditUser;
use App\Livewire\Admin\User\Users;
use App\Livewire\Admin\Order\BrowseProducts;
use App\Livewire\Admin\Order\Cart;
use App\Livewire\Admin\Order\Orders;
use App\Livewire\Admin\Order\OrderDetails;
use App\Livewire\Admin\Installment\InstallmentDetails;
use App\Livewire\Admin\Expense\DailyExpense;
use App\Livewire\Admin\Reports\Reports;
use App\Livewire\Admin\Reports\DailyItemReport;

Route::get('/dashboard', Dashboard::class)->name('dashboard');

Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/product', Products::class)->name('product');
    Route::get('/product/new', CreateProduct::class)->name('create.product');
    Route::get('/product/edit/{product}', EditProduct::class)->name('edit.product');

    Route::get('/branch', Branches::class)->name('branch');
    Route::get('/branch/new', CreateBranch::class)->name('create.branch');
    Route::get('/branch/edit/{branch}', EditBranch::class)->name('edit.branch');

    Route::get('/supplier', Suppliers::class)->name('supplier');
    Route::get('/supplier/new', CreateSupplier::class)->name('create.supplier');
    Route::get('/supplier/edit/{supplier}', EditSupplier::class)->name('edit.supplier');

    Route::get('/users', Users::class)->name('users');
    Route::get('/users/new', CreateUser::class)->name('create.user');
    Route::get('/users/edit/{user}', EditUser::class)->name('edit.user');

});
Route::group(['middleware' => ['role:admin|inventory_head']], function () {
    Route::get('/manage-stock', StockManagement::class)->name('manage.stock');

    Route::get('/receive-product', ReceiveProducts::class)->name('receive.product');
    Route::get('/receive-history', BatchHistory::class)->name('receive.history');
    Route::get('/receive-history/{batch}', BatchDetails::class)->name('receive.history.details');
    Route::get('/stock-history', ReceiveProductsHistory::class)->name('receive.product.history');

    Route::get('/request-transfer', RequestTransfer::class)->name('request.transfer');
    Route::get('/view-request', ViewRequest::class)->name('view.request');
    Route::get('/view-request/{transferId}', TransferDetails::class)->name('view.request.details');
    Route::get('/for-transfer', ForTransfer::class)->name('for.transfer');
    Route::get('/incoming', Incoming::class)->name('incoming');
    Route::get('/transfer-history', TransferHistory::class)->name('transfer.history');
    Route::get('/transfer-history/{transfer}', TransferHistoryDetails::class)->name('transfer.history.details');
    Route::get('/request-history', RequestHistory::class)->name('request.history');
    Route::get('/delivery/to-ship', ToShip::class)->name('delivery.to-ship');
    Route::get('/delivery/out-for-delivery', OutForDelivery::class)->name('delivery.out-for-delivery');
    Route::get('/delivery/history', \App\Livewire\Admin\Delivery\History::class)->name('delivery.history');
    Route::get('/delivery/delivered', Delivered::class)->name('delivery.delivered');
    Route::get('/delivery/details/{delivery_id}', DeliveryDetails::class)->name('delivery.details');
});

Route::group(['middleware' => ['role:admin|sales_rep|cashier']], function () {
    Route::get('/browse', BrowseProducts::class)->name('browse.product');
    Route::get('/cart', Cart::class)->name('cart');
});

Route::group(['middleware' => ['role:admin|cashier']], function () {
    Route::get('/orders/{type}/{status}', Orders::class)->name('orders');
    Route::get('/orders-history', History::class)->name('orders.history');
    Route::get('/orders/{order_id}', OrderDetails::class)->name('order.details');

    Route::get('/customer/{customer}', InstallmentDetails::class)->name('customer.details');

    Route::get('/customers', CustomerList::class)->name('customer.list');
    Route::get('/daily-expense/new', DailyExpense::class)->name('expense');
});

Route::group(['middleware' => ['role:admin|sales_rep|cashier']], function () {
    Route::get('/reports/daily', Reports::class)->name('reports.daily');
    Route::get('/reports/monthly', Monthly::class)->name('reports.monthly');
    Route::get('/reports/daily-items/{product}', DailyItemReport::class)->name('reports.daily.items');
});
