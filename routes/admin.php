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
use App\Livewire\Admin\User\EditUser;
use App\Livewire\Admin\User\Users;

Route::get('/dashboard', Dashboard::class)->name('dashboard');


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
