<?php

use App\Http\Controllers\ExampleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvtItemCategoryController;
use App\Models\InvtItemCategory;

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
    return redirect('home');
});

// * contoh route singgle (hanya 1 menu)
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/quote', [HomeController::class, 'quote'])->name('quote');

// Route::get('/item-category', [InvtItemCategoryController::class, 'index'])->name('item-category');
// Route::get('/item-category/add', [InvtItemCategoryController::class, 'addItemCetegory'])->name('add-item-category');
Route::post('/elements-add',[InvtItemCategoryController::class, 'elementsAddItemCategory'])->name('elements-add-category');
Route::post('/process-add-category',[InvtItemCategoryController::class, 'processAddItemCategory'])->name('process-add-item-category');
Route::get('/reset-add',[InvtItemCategoryController::class, 'addReset'])->name('add-reset-category');

Route::get('/quote', [ExampleController::class, 'index'])->name('index');

// * contoh route group untuk 1 menu tapi ada beberapa route yang masih berhubungan

Route::middleware('auth')->group(function () {
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
    Route::prefix('item-category')->name('ic.')->group(function () {
        Route::get('/',[InvtItemCategoryController::class, 'index'])->name('index');
        Route::get('add',[InvtItemCategoryController::class, 'addItemCategory'])->name('add');
        Route::get('edit',[InvtItemCategoryController::class, 'editItemCategory'])->name('edit');
    });

});