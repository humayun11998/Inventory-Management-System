<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Pos\SupplierController;
use App\Http\Controllers\Pos\CustomerController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});


// Admin All Routes
Route::controller(AdminController::class)->group(function () {
    Route::get('/admin/logout', 'destroy')->name('admin.logout');
    Route::get('/admin/profile', 'Profile')->name('admin.profile');
    Route::get('/edit/profile', 'EditProfile')->name('edit.profile');

    Route::post('/store/profile', 'StoreProfile')->name('store.profile');

    Route::get('/change/password', 'ChangePassword')->name('change.password');
    Route::post('/update/password', 'UpdatePassword')->name('update.password');

});

// Suppliers All Routes
Route::controller(SupplierController::class)->group(function () {
    Route::get('/suppliers/all', 'SuppliersAll')->name('suppliers.all');
    Route::get('/suppliers/add', 'SuppliersAdd')->name('suppliers.add');
    Route::get('/suppliers/edit/{id}', 'SuppliersEdit')->name('suppliers.edit');
    Route::get('/suppliers/delete/{id}', 'SuppliersDelete')->name('suppliers.delete');

    Route::post('/suppliers/store', 'SupplierStore')->name('supplier.store');
    Route::post('/suppliers/update', 'SupplierUpdate')->name('supplier.update');


});

// Customer All Routes
Route::controller(CustomerController::class)->group(function () {
    Route::get('/customer/all', 'CustomerAll')->name('customer.all');
    Route::get('/customer/add', 'CustomerAdd')->name('customer.add');
    Route::get('/customer/edit/{id}', 'CustomerEdit')->name('customer.edit');
    Route::get('/customer/delete/{id}', 'CustomerDelete')->name('customer.delete');


    Route::post('/customer/store', 'CustomerStore')->name('customer.store');
    Route::post('/customer/update', 'CustomerUpdate')->name('customer.update');
});




Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
