<?php

use App\Http\Controllers\AcctAccountController;
use App\Http\Controllers\CoreBankController;
use App\Http\Controllers\CoreSupplierController;
use App\Http\Controllers\ExampleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvtItemCategoryController;
use App\Http\Controllers\InvtItemUnitController;
use App\Http\Controllers\InvtWarehouseController;
use App\Http\Controllers\JournalVoucherController;
use App\Http\Controllers\PreferenceVoucherController;
use App\Http\Controllers\SystemUserController;
use App\Http\Controllers\SystemUserGroupController;
use App\Models\CoreBank;
use App\Models\InvtItemCategory;
use App\Models\PreferenceVoucher;

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
    Route::prefix('system-user-group')->name('usergroup.')->group(function() {
        Route::get('/', [SystemUserGroupController::class, 'index'])->name('index');
        Route::get('/add', [SystemUserGroupController::class, 'addSystemUserGroup'])->name('add');
        Route::post('/add-process', [SystemUserGroupController::class, 'processAddSystemUserGroup'])->name('add-process');
        Route::get('/edit/{user_group_id}', [SystemUserGroupController::class, 'editSystemUserGroup'])->name('edit');
        Route::post('/edit-process', [SystemUserGroupController::class, 'processEditSystemUserGroup'])->name('edit-process');
        Route::get('/delete/{user_group_id}', [SystemUserGroupController::class, 'deleteSystemUserGroup'])->name('delete');
    });
    Route::prefix('item-unit')->name('itemunit.')->group(function() {
        Route::get('/', [InvtItemUnitController::class, 'index'])->name('index');
        Route::get('/add', [InvtItemUnitController::class, 'addInvtItemUnit'])->name('add');
        Route::post('/add-process', [InvtItemUnitController::class, 'processAddInvtItemUnit'])->name('add-process');
        Route::post('/add-elements', [InvtItemUnitController::class, 'addElementsInvtItemUnit'])->name('add-elements');
        Route::get('/add-reset', [InvtItemUnitController::class, 'addReset'])->name('add-reset');
        Route::get('/edit/{item_unit_id}', [InvtItemUnitController::class, 'editInvtItemUnit'])->name('edit');
        Route::post('/edit-process', [InvtItemUnitController::class, 'processEditInvtItemUnit'])->name('edit-process');
        Route::get('/delete/{item_unit_id}', [InvtItemUnitController::class, 'deleteInvtItemUnit'])->name('delete');
    });
    Route::prefix('system-user')->name('user.')->group(function() {
        Route::get('/', [SystemUserController::class, 'index'])->name('index');
        Route::get('/add', [SystemUserController::class, 'addSystemUser'])->name('add');
        Route::post('/add-process', [SystemUserController::class, 'processAddSystemUser'])->name('add-process');
        Route::get('/edit/{user_id}', [SystemUserController::class, 'editSystemUser'])->name('edit');
        Route::post('/edit-process', [SystemUserController::class, 'processEditSystemUser'])->name('edit-process');
        Route::get('/delete/{user_id}', [SystemUserController::class, 'deleteSystemUser'])->name('delete');
        Route::get('/changepw/{user_id}', [SystemUserController::class, 'changePassword'])->name('changepw');
        Route::post('/changepw-process', [SystemUserController::class, 'processChangePassword'])->name('changepw-process');
    });
    Route::prefix('preference-voucher')->name('pv.')->group(function() {
        Route::get('/', [PreferenceVoucherController::class, 'index'])->name('index');
        Route::get('/add', [PreferenceVoucherController::class, 'addPreferenceVoucher'])->name('add');
        Route::post('/add-process', [PreferenceVoucherController::class, 'addProcessPreferenceVoucher'])->name('add-process');
        Route::post('/add-elements', [PreferenceVoucherController::class, 'addElementsPreferenceVoucher'])->name('add-elements');
        Route::get('/add-reset', [PreferenceVoucherController::class, 'resetElementsPreferenceVoucher'])->name('add-reset');
        Route::get('/edit/{voucher_id}', [PreferenceVoucherController::class, 'editPreferenceVoucher'])->name('edit');
        Route::post('/edit-process', [PreferenceVoucherController::class, 'editProcessPreferenceVoucher'])->name('edit-process');
        Route::get('/delete/{voucher_id}', [PreferenceVoucherController::class, 'deletePreferenceVoucher'])->name('delete');
    });
    Route::prefix('journal-voucher')->name('jv.')->group(function() {
        Route::get('/', [JournalVoucherController::class, 'index'])->name('index');
        Route::get('/add', [JournalVoucherController::class, 'addJournalVoucher'])->name('add');
        Route::post('/add-process', [JournalVoucherController::class, 'processAddJournalVoucher'])->name('add-process');
        Route::post('/add-elements', [JournalVoucherController::class, 'addElementsJournalVoucher'])->name('add-elements');
        Route::get('/add-reset', [JournalVoucherController::class, 'resetAddJournalVoucher'])->name('add-reset');
        Route::post('/add-array', [JournalVoucherController::class, 'addArrayJournalVoucher'])->name('add-array');
        Route::post('/filter', [JournalVoucherController::class, 'filterJournalVoucher'])->name('filter');
        Route::post('/filter-reset', [JournalVoucherController::class, 'resetFilterJournalVoucher'])->name('filter-reset');
        Route::get('/print/{journal_voucher_id}', [JournalVoucherController::class, 'printJournalVoucher'])->name('print');
        Route::get('/delete/{journal_voucher_id}', [JournalVoucherController::class, 'reverseJournalVoucher'])->name('delete');
    });
});