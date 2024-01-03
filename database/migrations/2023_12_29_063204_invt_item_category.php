<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Nette\Schema\Schema as SchemaSchema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(!Schema::hasTable('invt_item_category')){
            Schema::create('invt_item_category', function (Blueprint $table) {
                $table->id('item_category_id');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->string('item_category_code')->nullable();
                $table->string('item_category_name')->nullable();
                $table->string('item_category_remark')->nullable();
                $table->integer('margin_precentage')->nullable();
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
        Schema::dropIfExists('invt_item_category');
    }
};
