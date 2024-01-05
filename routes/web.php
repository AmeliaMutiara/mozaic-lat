<?php

use App\Http\Controllers\CoreSupplierController;
use App\Http\Controllers\ExampleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvtItemCategoryController;
use App\Http\Controllers\InvtWarehouseController;
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
        Route::post('/elements-add',[InvtItemCategoryController::class, 'elementsAddItemCategory'])->name('elements-add');
        Route::post('/process-add-category',[InvtItemCategoryController::class, 'processAddItemCategory'])->name('process-add');
        Route::get('/reset-add',[InvtItemCategoryController::class, 'addReset'])->name('add-reset');
        Route::get('edit/{item_category_id}',[InvtItemCategoryController::class, 'editItemCategory'])->name('edit');
        Route::post('/process-edit-category',[InvtItemCategoryController::class, 'processEditItemCategory'])->name('process-edit');
        Route::get('delete/{item_category_id}',[InvtItemCategoryController::class, 'deleteItemCategory'])->name('delete');

    });
    Route::prefix('warehouse')->name('warehouse.')->group(function () {
        Route::get('/',[InvtWarehouseController::class, 'index'])->name('index');
        Route::get('/add',[InvtWarehouseController::class, 'addWarehouse'])->name('add');
        Route::post('/elements-add',[InvtWarehouseController::class, 'elementsAddWarehouse'])->name('elements-add');
        Route::post('/process-add-warehouse',[InvtWarehouseController::class, 'processAddWarehouse'])->name('process-add');
        Route::get('/warehouse/add-reset', [InvtWarehouseController::class, 'addResetWarehouse'])->name('add-reset-warehouse');
        Route::get('/warehouse/edit-warehouse/{warehouse_id}',[InvtWarehouseController::class, 'editWarehouse'])->name('edit-warehouse');
        Route::post('/warehouse/process-edit-warehouse', [InvtWarehouseController::class, 'processEditWarehouse'])->name('process-edit-warehouse');
        Route::get('/warehouse/delete-warehouse/{warehouse_id}', [InvtWarehouseController::class, 'deleteWarehouse'])->name('delete-warehouse');
    });
    Route::prefix('core-supplier')->name('supplier.')->group(function() {
        Route::get('/', [CoreSupplierController::class, 'index'])->name('index');
        Route::get('/add', [CoreSupplierController::class, 'addCoreSupplier'])->name('add');
        Route::post('/process-add', [CoreSupplierController::class, 'processAddCoreSupplier'])->name('add-process');
        Route::post('/add-elements', [CoreSupplierController::class, 'addElementsCoreSupplier'])->name('add-elements');
        Route::get('/reset-elements', [CoreSupplierController::class, 'resetElementsCoreSupplier'])->name('reset-elements');
        Route::get('/edit/{supplier_id}', [CoreSupplierController::class, 'editCoreSupplier'])->name('edit');
        Route::post('/process-edit', [CoreSupplierController::class, 'processEditCoreSupplier'])->name('edit-process');
        Route::get('/delete/{supplier_id}', [CoreSupplierController::class, 'deleteCoreSupplier'])->name('delete');
    });
});