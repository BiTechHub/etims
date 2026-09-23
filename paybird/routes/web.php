<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Artisan;

Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return 'All caches cleared (route, config, view, app).';
});


Route::get('/admin-login', [AdminAuthController::class, 'showLogin'])
    ->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.submit');
Route::get('/', [GuestController::class, 'register_user']);
Route::get('pay-bird', [GuestController::class, 'register_user']);
Route::post('pay-bird-post', [GuestController::class, 'register_user_post']);
Route::get('confirm-payment/{id}', [GuestController::class, 'confirm_payment']);
Route::post('/ccavenue/initiate', [PaymentController::class, 'initiate'])
    ->name('ccavenue.initiate');
Route::post('/ccavenue/callback', [PaymentController::class, 'callback'])
    ->name('ccavenue.callback');
Route::post('/ccavenue/cancel', [PaymentController::class, 'cancel'])
    ->name('ccavenue.cancel');







Route::prefix('admin')->middleware('admin.auth')->group(function () {


Route::get('/state', [AdminController::class, 'state'])
    ->name('state');
Route::get('/countries', [AdminController::class, 'countries'])
    ->name('countries');
Route::post('/countries/store', [AdminController::class, 'storeCountry'])
    ->name('storeCountry');
Route::get('/registration-list', [AdminAuthController::class, 'registrationList'])
    ->name('registration.list');
Route::get('/payment-list', [AdminAuthController::class, 'paymentList'])
    ->name('payment.list');
Route::post('/district/store', [AdminController::class, 'storeDistrict'])
    ->name('district.store');
Route::post('/district/update/{id}', [AdminController::class, 'updateDistrict']);
Route::get('/payment_type', [AdminController::class, 'payment_type'])
    ->name('payment_type');
Route::post('/payment_store', [AdminController::class, 'payment_store'])
    ->name('payment_store');
Route::get('/branch', [AdminController::class, 'branch'])->name('branch');
Route::post('/branch_store', [AdminController::class, 'branch_store'])
    ->name('branch_store');
Route::get('/organization', [AdminController::class, 'organization'])
    ->name('organization');
Route::post('/organization_store', [AdminController::class, 'organization_store'])
    ->name('organization_store');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
Route::get('/registration-list', [AdminAuthController::class, 'registrationList'])
    ->name('registration.list');
Route::get('/payment-list', [AdminAuthController::class, 'paymentList'])
    ->name('payment.list');
Route::get('/dashboard', [AdminController::class, 'index'])
    ->name('admin.dashboard');



});