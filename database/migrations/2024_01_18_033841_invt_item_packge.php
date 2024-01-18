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
        if(!Schema::hasTable('invt_item_packge')){
            Schema::create('invt_item_packge', function(Blueprint $table){
                $table->id('item_packge_id');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->unsignedBigInteger('item_id')->nullable();
                $table->unsignedBigInteger('item_unit_id')->nullable();
                $table->unsignedBigInteger('item_category_id')->nullable();
                $table->string('item_default_quantity')->nullable();
                $table->string('margin_percentage')->nullable();
                $table->string('item_unit_price')->nullable();
                $table->string('item_unit_cost')->nullable();
                $table->unsignedBigInteger('order')->nullable();
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
