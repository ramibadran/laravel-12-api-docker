<?php


use App\Http\Controllers\API\V1\SettingController;
use Illuminate\Support\Facades\Route;

Route::post('/signature', [SettingController::class,'generateSignature'])->name('generateSignature');


