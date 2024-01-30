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
        if(!Schema::hasTable('invt_item_package')){
            Schema::create('invt_item_package', function(Blueprint $table){
                $table->id('invt_item_package_id');
                $table->unsignedBigInteger('item_id')->nullable();
                $table->unsignedBigInteger('package_item_id')->nullable();
                $table->unsignedBigInteger('item_unit_id')->nullable();
                $table->string('item_quantity')->nullable();
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
