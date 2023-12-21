<?php

use App\Http\Controllers\ExampleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Auth::routes();
Route::get('/', function () {
    return view('welcome');
});

// * contoh route singgle (hanya 1 menu)
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/quote', [HomeController::class, 'quote'])->name('quote');

// * contoh route group untuk 1 menu tapi ada beberapa route yang masih berhubungan

// * contoh >> Cara Pemanggilan di blade/controller : route('contoh.index')
Route::prefix('example')->name('contoh.')->group(function () {
    Route::get('/',[ExampleController::class, 'index'])->name('index');
    // * variabel bisa ditaruh mana saja (lihat dokumentasi laravel)
    Route::get('{parent_id}/child',[ExampleController::class, 'child'])->name('child');
    Route::get('child/{parent_id}',[ExampleController::class, 'child'])->name('child-2');
});
// * contoh2 >> Cara Pemanggilan di blade/controller : route('contoh2.index')
Route::prefix('example-ii')->name('contoh2.')->group(function () {
    Route::get('/',[ExampleController::class, 'index'])->name('index');
    // * variabel bisa ditaruh mana saja (lihat dokumentasi laravel)
    Route::get('{parent_id}/child',[ExampleController::class, 'child'])->name('child');
    Route::get('child/{parent_id}',[ExampleController::class, 'child'])->name('child-2');
});
