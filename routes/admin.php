<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\ProductController;
use App\Livewire\Admin\CreateUser;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Users;

Route::get('/dashboard', Dashboard::class)->name('dashboard');


Route::get('/product', [ProductController::class, 'index'])->name('product');
Route::get('/product/new', [ProductController::class, 'createProduct'])->name('create.product');

Route::get('/branch', [BranchController::class, 'index'])->name('branch');
Route::get('/branch/new`', [BranchController::class, 'createBranch'])->name('create.branch');

Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier');
Route::get('/supplier/new`', [SupplierController::class, 'createSupplier'])->name('create.supplier');

Route::get('/users', Users::class)->name('users');
Route::get('/users/new', CreateUser::class)->name('create.user');
