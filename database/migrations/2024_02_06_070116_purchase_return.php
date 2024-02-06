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
        if(!Schema::hasTable('purchase_return')){
            Schema::create('purchase_return', function(Blueprint $table){
                $table->id('purchase_return_id')->nullable();
                $table->unsignedBigInteger('company_id')->nullable();
                $table->unsignedBigInteger('supplier_id')->nullable();
                $table->unsignedBigInteger('warehouse_id')->nullable();
                $table->unsignedBigInteger('purchase_invoice_id')->nullable();
                $table->string('purchase_return_no')->nullable();
                $table->string('purchase_return_date')->nullable();
                $table->string('purchase_return_quantity')->nullable();
                $table->string('subtotal_amount-total')->nullable();
                $table->string('discount_percentage_total')->nullable();
                $table->string('tax_ppn_percentage')->nullable();
                $table->string('tax_ppn_amount')->nullable();
                $table->string('shortover_amount')->nullable();
                $table->string('purchase_return_subtotal')->nullable();
                $table->string('purchase_return_remark')->nullable();
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
