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
        if(!Schema::hasTable('acct_account_setting')){
            Schema::create('acct_account_setting', function (Blueprint $table){
                $table->id('account_setting_id');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->unsignedBigInteger('account_id')->nullable();
                $table->string('account_setting_name')->nullable();
                $table->tinyInteger('account_setting_status')->nullable();
                $table->tinyInteger('account_default_status')->nullable();
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
