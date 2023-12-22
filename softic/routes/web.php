<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\PaymentController;
use App\Http\Controllers\Gateway\paypal\ProcessController;
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

Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/search',[HomeController::class,'search'])->name('search');
Route::post('/payment',[PaymentController::class,'paymentStore'])->name('payment.store');
Route::get('/payment/confirm',[PaymentController::class,'paymentConfirm'])->name('payment.confirm');
Route::get('/transaction/list',[\App\Http\Controllers\TransactionController::class,'list'])->name('transaction.list');
Route::get('user/transaction/details/{id}',[\App\Http\Controllers\TransactionController::class,'details'])->name('transaction.details');
Route::controller(ProcessController::class)->group(function(){
    Route::post('paypal','ipn')->name('paypal');
});
