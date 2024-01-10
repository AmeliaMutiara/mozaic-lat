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
                $table->string('id_menu', 10);
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
               [ 'id_menu' => 1,  'id' => 'home',          'type' => 'file','text' => 'Beranda','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 2,  'id' => 'example',             'type' => 'folder','text' => 'Contoh Table','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 3,  'id' => 'item-category',             'type' => 'file','text' => 'Kategori Barang','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 4,  'id' => 'warehouse',             'type' => 'file','text' => 'Gudang','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 5,  'id' => 'core-supplier',             'type' => 'file','text' => 'Supplier','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 6,  'id' => 'acct-account',             'type' => 'file','text' => 'No Perkiraan','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 7,  'id' => 'core-bank',             'type' => 'file','text' => 'Bank','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 8,  'id' => 'system-user-group',             'type' => 'file','text' => 'User Group','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 9,  'id' => 'item-unit',             'type' => 'file','text' => 'Preferensi Satuan Barang','parent' => "#",'menu_level' => "1",],
            //    [ 'id_menu' => 9, 'id' => '#',              'type' => 'folder','text' => 'Level 1','parent' => "#",'menu_level' => "1",],
            //    [ 'id_menu' => 91, 'id' => 'level',         'type' => 'file','text' => 'Level 2','parent' => "4",'menu_level' => "2",],
            //    [ 'id_menu' => 911, 'id' => 'level',         'type' => 'file','text' => 'Level 3','parent' => "41",'menu_level' => "3",],
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
