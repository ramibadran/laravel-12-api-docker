<?php

use App\Http\Controllers\API\V1\UserController;
use Illuminate\Support\Facades\Route;

//Route::get('/', [UserController::class,'users'])->name('list');
Route::post('/', [UserController::class,'create'])->middleware(['verify.hmac'])->name('create');//'idempotency'
//Route::get('/', [UserController::class,'profile'])->name('profile');
//Route::put('/', [UserController::class,'update'])->middleware([/*'verify.hmac',*/'jwtAuth'])->name('update');





