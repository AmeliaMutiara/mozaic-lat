<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(!Schema::hasTable('preference_transaction_module')){
            Schema::create('preference_transaction_module', function (Blueprint $table) {
                $table->id('transaction_module_id');
                $table->string('transaction_module_name')->nullable();
                $table->string('transaction_module_code')->nullable();
                $table->string('transaction_controller')->nullable();
                $table->string('transaction_table')->nullable();
                $table->string('transaction_primary_key')->nullable();
                $table->unsignedBigInteger('status')->nullable();
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->timestamp('last_update')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });

            DB::table('preference_transaction_module')->insert([
                ['transaction_module_name'=>"Jurnal Umum", 'transaction_module_code'=>"JU", 'transaction_controller'=>"JournalVoucherController", 'transaction_table'=>"acct_journal_voucher", 'transaction_primary_key'=>"journal_voucher_id"],
                ['transaction_module_name'=>"Pembelian", 'transaction_module_code'=>"PBL", 'transaction_controller'=>"PurchaseInvoiceController", 'transaction_table'=>"purchase_invoice", 'transaction_primary_key'=>"purchase_invoice_id"],
                ['transaction_module_name'=>"Retur Pembelian", 'transaction_module_code'=>"RPBL", 'transaction_controller'=>"PurchaseReturnReportController", 'transaction_table'=>"purchase_return", 'transaction_primary_key'=>"purchase_return_id"],
                ['transaction_module_name'=>"Penjualan", 'transaction_module_code'=>"PJL", 'transaction_controller'=>"SalesInvoiceReportController", 'transaction_table'=>"sales_invoice", 'transaction_primary_key'=>"sales_invoice_id"],
                ['transaction_module_name'=>"Pengeluaran", 'transaction_module_code'=>"PGL", 'transaction_controller'=>"ExpenditureController", 'transaction_table'=>"expenditure", 'transaction_primary_key'=>"expenditure_id"],
                ['transaction_module_name'=>"Hapus Penjualan", 'transaction_module_code'=>"HPSPJL", 'transaction_controller'=>"SalesInvoiceReportController", "sales_invoice", 'transaction_primary_key'=>"sales_invoice_id"],
                ['transaction_module_name'=>"Hapus Pengeluaran", 'transaction_module_code'=>"HPSPGL", 'transaction_controller'=>"ExpenditureController", 'transaction_table'=>"expenditure", 'transaction_primary_key'=>"expenditure_id"],
                ['transaction_module_name'=>"Pembayaran Hutang", 'transaction_module_code'=>"PH", 'transaction_controller'=>"PurchasePaymentController", 'transaction_table'=>"purchasepayment", 'transaction_primary_key'=>"payment_id"],
                ['transaction_module_name'=>"Batal Pembayaran Hutang", 'transaction_module_code'=>"BPH", 'transaction_controller'=>"PurchasePaymentController", 'transaction_table'=>"purchasepayment", 'transaction_primary_key'=>"payment_id"],
                ['transaction_module_name'=>"Hapus Pembelian", 'transaction_module_code'=>"HPBL", 'transaction_controller'=>"PurchaseInvoiceController", 'transaction_table'=>"purchase_invoice", 'transaction_primary_key'=>"purchase_invoice_id"]
            ]);

        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
