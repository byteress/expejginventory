<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Branch\Branches;
use App\Livewire\Admin\Branch\CreateBranch;
use App\Livewire\Admin\Branch\EditBranch;
use App\Livewire\Admin\User\CreateUser;
use App\Livewire\Admin\Dashboard;
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
    Route::get('/receive-product', ReceiveProducts::class)->name('receive.product');
    Route::get('/receive-product-history', ReceiveProductsHistory::class)->name('receive.product.history');

    Route::get('/request-transfer', RequestTransfer::class)->name('request.transfer');
    Route::get('/view-request', ViewRequest::class)->name('view.request');
    Route::get('/view-request/{transferId}', TransferDetails::class)->name('view.request.details');
    Route::get('/for-transfer', ForTransfer::class)->name('for.transfer');
    Route::get('/incoming', Incoming::class)->name('incoming');
    Route::get('/request-history', RequestHistory::class)->name('request.history');
});
