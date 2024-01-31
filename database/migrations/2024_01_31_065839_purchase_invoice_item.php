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
        if(!Schema::hasTable('purcahase_invoice_item')){
            Schema::create('purchase_invoice_item', function(Blueprint $table){
                $table->id('purchase_invoice_item_id')->nullable()->nullable();
                $table->unsignedBigInteger('company_id')->nullable();
                $table->unsignedBigInteger('purchase_invoice_id')->nullable();
                $table->unsignedBigInteger('item_category_id')->nullable();
                $table->unsignedBigInteger('item_unit_id')->nullable();
                $table->unsignedBigInteger('item_id')->nullable();
                $table->string('item_unit_cost')->nullable();
                $table->string('quantity')->nullable();
                $table->string('subtotal_amount')->nullable();
                $table->string('discount_percentage')->nullable();
                $table->string('discount_amount')->nullable();
                $table->string('subtotal_amount_after_discount')->nullable();
                $table->string('item_expired_date')->nullable();
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
