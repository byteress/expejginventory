<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Frontend\Login;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', Login::class)->name('login');

Route::view('/test-home', 'frontend.homepage')->name('test.home');
Route::get('/logout', function () {
    Auth::logout();
    return redirect(route('login'));
})->name('logout');
