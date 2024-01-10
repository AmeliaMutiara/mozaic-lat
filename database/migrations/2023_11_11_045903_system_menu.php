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
               [ 'id_menu' => 3,  'id' => '#',             'type' => 'folder','text' => 'Preferensi','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 31, 'id' => '#',             'type' => 'folder','text' => 'Preferensi Barang','parent' => "3",'menu_level' => "2",],
               [ 'id_menu' => 311,'id' => 'item-category',             'type' => 'file','text' => 'Kategori Barang','parent' => "31",'menu_level' => "3",],
               [ 'id_menu' => 312,'id' => 'warehouse',             'type' => 'file','text' => 'Gudang','parent' => "31",'menu_level' => "3",],
               [ 'id_menu' => 313,'id' => 'item-unit',             'type' => 'file','text' => 'Preferensi Satuan Barang','parent' => "31",'menu_level' => "3",],
               [ 'id_menu' => 32, 'id' => '#',             'type' => 'folder','text' => 'Set Up Data','parent' => "3",'menu_level' => "2",],
               [ 'id_menu' => 321,'id' => 'system-user-group',             'type' => 'file','text' => 'User Group','parent' => "32",'menu_level' => "3",],
               [ 'id_menu' => 322,'id' => 'core-bank',             'type' => 'file','text' => 'Bank','parent' => "32",'menu_level' => "3",],
               [ 'id_menu' => 323,'id' => 'core-supplier',             'type' => 'file','text' => 'Supplier','parent' => "32",'menu_level' => "3",],
               [ 'id_menu' => 4,  'id' => '#',             'type' => 'folder','text' => 'Akutansi','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 41,  'id' => '#',             'type' => 'folder','text' => 'Akutansi','parent' => "4",'menu_level' => "2",],
               [ 'id_menu' => 411,  'id' => 'acct-account',             'type' => 'file','text' => 'No Perkiraan','parent' => "41",'menu_level' => "3",],
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
