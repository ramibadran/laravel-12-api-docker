<?php

use App\Http\Controllers\API\V1\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class,'login'])->middleware(['verify.hmac'])->name('login');
Route::post('/refresh', [LoginController::class,'refresh'])->name('refresh');






