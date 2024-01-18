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
        if(!Schema::hasTable('invt_item')){
            Schema::create('invt_item', function (Blueprint $table) {
                $table->id('item_id');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->unsignedBigInteger('item_category_id')->nullable();
                $table->unsignedBigInteger('item_unit_id')->nullable();
                $table->string('item_name')->nullable();
                $table->string('item_code')->nullable();
                $table->string('item_barcode')->nullable();
                $table->unsignedBigInteger('item_status')->nullable();
                $table->string('item_default_quantity')->nullable();
                $table->string('item_unit_price')->nullable();
                $table->string('item_unit_cost')->nullable();
                $table->string('item_remark')->nullable();
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
