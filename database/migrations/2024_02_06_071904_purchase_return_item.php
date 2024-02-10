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
        if(!Schema::hasTable('purchase_return_item')){
            Schema::create('purchase_return_item', function(Blueprint $table){
                $table->id('purchase_item_id')->nullable();
                $table->unsignedBigInteger('company_id')->nullable();
                $table->unsignedBigInteger('purchase_return_id')->nullable();
                $table->unsignedBigInteger('item_category_id')->nullable();
                $table->unsignedBigInteger('item_id')->nullable();
                $table->unsignedBigInteger('item_unit_id')->nullable();
                $table->string('purchase_item_cost')->nullable();
                $table->string('purchase_item_quantity')->nullable();
                $table->string('purchase_item_subtotal')->nullable();
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
