<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/',[UserController::class,'show']);
Route::post('/',[UserController::class,'store']);
Route::get('/edit/{id}',[UserController::class,'edit']);
Route::put('/update/{id}',[UserController::class,'update']);
Route::delete('/remove/{id}',[UserController::class,'destroy']);

