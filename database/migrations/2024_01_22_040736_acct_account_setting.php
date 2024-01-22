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
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('account_id');
                $table->string('account_setting_name');
                $table->unsignedBigInteger('account_setting_status');
                $table->unsignedBigInteger('created_id');
                $table->unsignedBigInteger('updated_id');
                $table->unsignedBigInteger('deleted_id');
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
