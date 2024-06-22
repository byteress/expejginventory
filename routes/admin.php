<?php

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
use App\Livewire\Admin\Order\OrderProduct;
use App\Livewire\Admin\Order\Orders;
use App\Livewire\Admin\Order\OrderCashier;
use App\Livewire\Admin\Order\OrderCashierDetails;

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
});

Route::group(['middleware' => ['role:admin|sales_rep']], function () {
    Route::get('/browse', OrderProduct::class)->name('browse.product');
    Route::get('/cart', Orders::class)->name('cart');
});

Route::group(['middleware' => ['role:admin|cashier']], function () {
    Route::get('/orders', OrderCashier::class)->name('orders');
    Route::get('/orders-history', History::class)->name('orders.history');
    Route::get('/orders/{order_id}', OrderCashierDetails::class)->name('order.details');
});
