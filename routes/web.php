<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tinker', \App\Http\Controllers\TinkerController::class);
Route::prefix('/{vendor}/{package}/{version}/')->group(function (){
    Route::get('{namespace?}/', [\App\Http\Controllers\DocController::class, 'namespace'])->name('namespace');
    Route::prefix('{namespace}/')->group(function () {
        Route::get('/class/{class}', [\App\Http\Controllers\DocController::class, 'class'])->name('class');
        Route::get('/{interface}')->name('interface');
        Route::get('/{trait}')->name('trait');
        Route::get('/{enum}')->name('enum');
        Route::get('/{exception}')->name('exception');
        Route::get('/{function}')->name('function');
    });

});
