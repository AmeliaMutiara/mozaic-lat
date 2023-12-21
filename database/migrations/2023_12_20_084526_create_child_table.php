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
        if(!Schema::hasTable('child_table')) {
            Schema::create('child_table', function (Blueprint $table) {
                $table->id('child_id');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->foreign('parent_id')->references('parent_id')->on('parent_table')->onUpdate('cascade')->onDelete('set null');
                $table->string('name')->nullable();
                $table->text('description')->nullable();


                // * kode dibawah wajib ada di semua migrasi yang digunakan (yang bukan untuk sistem)
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
        Schema::dropIfExists('child_table');
    }
};
