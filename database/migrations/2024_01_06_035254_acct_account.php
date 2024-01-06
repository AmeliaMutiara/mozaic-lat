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
        if(!Schema::hasTable('acct_account')){
            Schema::create('acct_account', function (Blueprint $table) {
                $table->id('account_id');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->string('account_code')->nullable();
                $table->string('account_name')->nullable();
                $table->string('account_group')->nullable(); 
                $table->integer('account_suspended')->nullable();
                $table->integer('account_default_status')->nullable();
                $table->string('account_remark')->nullable();
                $table->integer('account_status')->nullable();
                $table->string('account_token')->nullable();
                $table->integer('account_type_id')->nullable();
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
