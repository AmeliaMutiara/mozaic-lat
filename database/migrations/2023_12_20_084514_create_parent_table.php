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
        if(!Schema::hasTable('parent_table')) {
            Schema::create('parent_table', function (Blueprint $table) {
                $table->id('parent_id');
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
        Schema::dropIfExists('parent_table');
    }
};
