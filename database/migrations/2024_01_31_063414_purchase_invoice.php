<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(!Schema::hasTable('purchase_invoice')){
            Schema::create('purchase_invoice', function(Blueprint $table){
                $table->id('purchase_invoice_id');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->unsignedBigInteger('warehouse_id')->nullable();
                $table->unsignedBigInteger('supplier_id')->nullable();
                $table->unsignedBigInteger('purchase_payment_method')->nullable();
                $table->string('purchase_invoice_no')->nullable();
                $table->string('subtotal_item')->nullable();
                $table->string('purchase_invoice_remark')->nullable();
                $table->string('purchase_invoice_date')->nullable();
                $table->string('subtotal_amount_total')->nullable();
                $table->string('discount_percentage_total')->nullable();
                $table->string('discount_amount_total')->nullable();
                $table->string('tax_ppn_percentage')->nullable();
                $table->string('tax_ppn_amount')->nullable();
                $table->string('toral_amount')->nullable();
                $table->string('paid_amount')->nullable();
                $table->string('owning_amount')->nullable();
                $table->string('shortover_amount')->nullable();
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
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
