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
        if(!Schema::hasTable('purchase_invoice_change_date')){
            Schema::create('purchase_invoice_change_date', function(Blueprint $table){
                $table->id('purchase_invoice_change_date_id')->nullable();
                $table->unsignedBigInteger('purchase_invoice_id')->nullable();
                $table->date('purchase_invoice_date_old')->nullable();
                $table->date('purchase_invoice_date_new')->nullable();
                $table->date('purchase_invoice_due_date_old')->nullable();
                $table->date('purchase_invoice_due_date_new')->nullable(); 
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
