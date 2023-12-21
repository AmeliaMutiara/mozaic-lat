<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(!Schema::hasTable('system_menu')) {
            Schema::create('system_menu', function (Blueprint $table) {
                $table->integer('id_menu');
                $table->primary('id_menu');
                $table->string('id',100)->nullable();
                $table->enum('type',['folder','file','function'])->nullable();
                $table->string('text',50)->nullable();
                $table->string('parent',50)->nullable();
                $table->string('image',50)->nullable();
                $table->string('menu_level',50)->nullable();
                $table->softDeletesTz();
            });
            DB::table('system_menu')->insert([
               [ 'id_menu' => 1,  'id' => 'index',              'type' => 'file','text' => 'Beranda','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 2,  'id' => 'example',             'type' => 'file','text' => 'Contoh Tabel','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 3, 'id' => '#',       'type' => 'file','text' => 'Level 1','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 31, 'id' => '#',       'type' => 'file','text' => 'Level 2','parent' => "3",'menu_level' => "2",],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('system_menu');
    }
};
