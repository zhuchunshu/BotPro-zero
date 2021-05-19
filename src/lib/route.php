<?php

use App\Plugins\zero\src\Http\Controllers\IndexController;
use App\Plugins\zero\src\Http\Controllers\Setting;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class,'show']);

// 设置
Route::get('/setting', [Setting::class,'index']);
Route::get('/setting/{id}', [Setting::class,'edit']);
Route::put('/{id}', [Setting::class,'update']);