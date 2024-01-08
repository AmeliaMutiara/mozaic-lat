<?php

use App\Http\Controllers\AcctAccountController;
use App\Http\Controllers\CoreBankController;
use App\Http\Controllers\CoreSupplierController;
use App\Http\Controllers\ExampleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvtItemCategoryController;
use App\Http\Controllers\InvtWarehouseController;
use App\Models\CoreBank;
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
        Route::get('/add',[InvtItemCategoryController::class, 'addItemCategory'])->name('add');
        Route::post('/add-elements',[InvtItemCategoryController::class, 'elementsAddItemCategory'])->name('add-elements');
        Route::post('/add-process',[InvtItemCategoryController::class, 'processAddItemCategory'])->name('add-process');
        Route::get('/add-reset',[InvtItemCategoryController::class, 'addReset'])->name('add-reset');
        Route::get('/edit/{item_category_id}',[InvtItemCategoryController::class, 'editItemCategory'])->name('edit');
        Route::post('/process-edit-category',[InvtItemCategoryController::class, 'processEditItemCategory'])->name('edit-process');
        Route::get('/delete/{item_category_id}',[InvtItemCategoryController::class, 'deleteItemCategory'])->name('delete');

    });
    Route::prefix('warehouse')->name('warehouse.')->group(function () {
        Route::get('/',[InvtWarehouseController::class, 'index'])->name('index');
        Route::get('/add',[InvtWarehouseController::class, 'addWarehouse'])->name('add');
        Route::post('/add-elements',[InvtWarehouseController::class, 'elementsAddWarehouse'])->name('add-elements');
        Route::post('/add-process',[InvtWarehouseController::class, 'processAddWarehouse'])->name('add-process');
        Route::get('/add-reset', [InvtWarehouseController::class, 'addResetWarehouse'])->name('add-reset');
        Route::get('/edit/{warehouse_id}',[InvtWarehouseController::class, 'editWarehouse'])->name('edit');
        Route::post('/edit-process', [InvtWarehouseController::class, 'processEditWarehouse'])->name('edit-process');
        Route::get('/delete/{warehouse_id}', [InvtWarehouseController::class, 'deleteWarehouse'])->name('delete');
    });
    Route::prefix('core-supplier')->name('supplier.')->group(function() {
        Route::get('/', [CoreSupplierController::class, 'index'])->name('index');
        Route::get('/add', [CoreSupplierController::class, 'addCoreSupplier'])->name('add');
        Route::post('/add-process', [CoreSupplierController::class, 'processAddCoreSupplier'])->name('add-process');
        Route::post('/add-elements', [CoreSupplierController::class, 'addElementsCoreSupplier'])->name('add-elements');
        Route::get('/add-elements', [CoreSupplierController::class, 'resetElementsCoreSupplier'])->name('add-reset');
        Route::get('/edit/{supplier_id}', [CoreSupplierController::class, 'editCoreSupplier'])->name('edit');
        Route::post('/process-edit', [CoreSupplierController::class, 'processEditCoreSupplier'])->name('edit-process');
        Route::get('/delete/{supplier_id}', [CoreSupplierController::class, 'deleteCoreSupplier'])->name('delete');
    });
    Route::prefix('acct-account')->name('account.')->group(function() {
        Route::get('/', [AcctAccountController::class, 'index'])->name('index');
        Route::get('/add',[AcctAccountController::class, 'addAcctAccount'])->name('add');
        Route::post('/add-process',[AcctAccountController::class, 'processAddAcctAccount'])->name('add-process');
        Route::post('/add-elements',[AcctAccountController::class, 'addElementsAcctAccount'])->name('add-elements');
        Route::get('/add-reset',[AcctAccountController::class, 'addResetAcctAccount'])->name('add-reset');
        Route::get('/edit/{account_id}',[AcctAccountController::class, 'editAcctAccount'])->name('edit');
        Route::post('/process-edit',[AcctAccountController::class, 'processEditAcctAccount'])->name('edit-process');
        Route::get('/delete/{account_id}',[AcctAccountController::class, 'deleteAcctAccount'])->name('delete');
    });
    Route::prefix('core-bank')->name('bank.')->group(function() {
        Route::get('/', [CoreBankController::class, 'index'])->name('index');
        Route::get('/add', [CoreBankController::class, 'addCoreBank'])->name('add');
        Route::post('/add-process', [CoreBankController::class, 'processAddCoreBank'])->name('add-process');
        Route::post('/add-elements', [CoreBankController::class, 'addElementsCoreBank'])->name('add-elements');
        Route::get('/add-reset', [CoreBankController::class, 'resetElementsCoreBank'])->name('add-reset');
        Route::get('/edit/{bank_id}', [CoreBankController::class, 'editCoreBank'])->name('edit');
        Route::post('/edit-process', [CoreBankController::class, 'processEditCoreBank'])->name('edit-process');
        Route::get('/delete/{bank_id}', [CoreBankController::class, 'deleteCoreBank'])->name('delete');
    });
});