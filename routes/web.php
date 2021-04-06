<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();
Route::group(["prefix" => "cron"], function (){
    Route::get("/hatirlatma", function (){
        Artisan::call("Hatirlatma:Start");
    });
});

Route::get('/', [App\Http\Controllers\front\indexController::class, 'index'])->name('index');
Route::get('/detay', [App\Http\Controllers\front\indexController::class, 'detay'])->name('detay');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['namesapces' => 'admin', 'prefix' => 'admin', 'as' => 'admin.','middleware' => ["auth"]], function(){
    Route::get('/', [App\Http\Controllers\admin\indexController::class, 'index'])->name('index');
    Route::get("/calisma-saatleri", [\App\Http\Controllers\admin\indexController::class, "CalismaSaatleri"])->name("CalismaSaatleri");
});


