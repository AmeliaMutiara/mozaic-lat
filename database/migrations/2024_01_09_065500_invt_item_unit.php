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
        if(!Schema::hasTable('invt_item_unit')){
            Schema::create('invt_item_unit', function (Blueprint $table) {
                $table->id('item_unit_id');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->string('item_unit_code')->nullable();
                $table->string('item_unit_name')->nullable();
                $table->string('item_unit_remark')->nullable();
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
