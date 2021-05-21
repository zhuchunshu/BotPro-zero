<?php

use Illuminate\Support\Facades\Route;
use App\Plugins\zero\src\Http\Controllers\Setting;
use App\Plugins\zero\src\Http\Controllers\IndexController;
use App\Plugins\zero\src\Http\Controllers\SwitchController;

Route::get('/', [IndexController::class,'show']);

// 设置
Route::get('/setting', [Setting::class,'index']);
Route::get('/setting/{id}', [Setting::class,'edit']);
Route::put('/{id}', [Setting::class,'update']);

Route::get('/switch', [SwitchController::class,'index']); // 插件功能开关 
Route::put('/switch/{name}', [SwitchController::class,'update']); // 插件功能开关 