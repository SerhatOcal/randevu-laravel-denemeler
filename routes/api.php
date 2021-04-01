<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'api'], function (){
    Route::get('/calisma-saatleri/{date?}',[App\Http\Controllers\api\indexController::class,'getWorkingHours']);
    Route::post('/randevu-olustur',[App\Http\Controllers\api\indexController::class,'randevuOlustur']);

    Route::group(['namespace' => 'admin', 'prefix' => 'admin'],function (){
        Route::get('/randevu-islemleri',[App\Http\Controllers\api\admin\indexController::class,'all']);

        Route::post('/islemler',[App\Http\Controllers\api\admin\indexController::class,'getIslemler']);
        Route::get('/randevu-listesi', [App\Http\Controllers\api\admin\indexController::class,'getList']);
        Route::get('/randevu-gun-listesi', [App\Http\Controllers\api\admin\indexController::class,'getTodayList']);
        Route::get('/gecmis-randevu-listesi', [App\Http\Controllers\api\admin\indexController::class,'getLastList']);
        Route::get('/onay-bekleyenler-listesi', [App\Http\Controllers\api\admin\indexController::class,'getConfirmationList']);
        Route::get('/iptal-edilen-randevular', [App\Http\Controllers\api\admin\indexController::class,'getIptalList']);
    });
});
