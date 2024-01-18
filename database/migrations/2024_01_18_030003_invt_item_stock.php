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
        if(!Schema::hasTable('invt_item_stock')){
            Schema::create('invt_item_stock', function(Blueprint $table){
                $table->id('item_stock_id');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->unsignedBigInteger('warehouse_id')->nullable();
                $table->unsignedBigInteger('item_id')->nullable();
                $table->unsignedBigInteger('item_unit_id')->nullable();
                $table->unsignedBigInteger('item_category_id')->nullable();
                $table->unsignedBigInteger('rack_line')->nullable();
                $table->unsignedBigInteger('rack_column')->nullable();
                $table->string('last_balance')->nullable();
                $table->datetime('last_update')->nullable();
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
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
