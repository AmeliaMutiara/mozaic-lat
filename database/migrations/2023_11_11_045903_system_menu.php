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
               [ 'id_menu' => 3,  'id' => '#',             'type' => 'folder','text' => 'Pembelian','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 31, 'id' => 'purchase-invoice',             'type' => 'file','text' => 'Pembelian','parent' => "3",'menu_level' => "2",],
               [ 'id_menu' => 4,  'id' => '#',             'type' => 'folder','text' => 'Preferensi','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 41, 'id' => '#',             'type' => 'folder','text' => 'Preferensi Barang','parent' => "4",'menu_level' => "2",],
               [ 'id_menu' => 411,'id' => 'item-category',             'type' => 'file','text' => 'Kategori Barang','parent' => "41",'menu_level' => "3",],
               [ 'id_menu' => 412,'id' => 'warehouse',             'type' => 'file','text' => 'Gudang','parent' => "41",'menu_level' => "3",],
               [ 'id_menu' => 413,'id' => 'item-unit',             'type' => 'file','text' => 'Preferensi Satuan Barang','parent' => "41",'menu_level' => "3",],
               [ 'id_menu' => 414,'id' => 'invt-item',             'type' => 'file','text' => 'Barang','parent' => "41",'menu_level' => "3",],
               [ 'id_menu' => 42, 'id' => '#',             'type' => 'folder','text' => 'Set Up Data','parent' => "4",'menu_level' => "2",],
               [ 'id_menu' => 421,'id' => 'system-user-group',             'type' => 'file','text' => 'User Group','parent' => "42",'menu_level' => "3",],
               [ 'id_menu' => 422,'id' => 'core-bank',             'type' => 'file','text' => 'Bank','parent' => "42",'menu_level' => "3",],
               [ 'id_menu' => 423,'id' => 'core-supplier',             'type' => 'file','text' => 'Supplier','parent' => "42",'menu_level' => "3",],
               [ 'id_menu' => 424,'id' => 'system-user',             'type' => 'file','text' => 'User','parent' => "42",'menu_level' => "3",],
               [ 'id_menu' => 43, 'id' => 'preference-voucher',             'type' => 'file','text' => 'Voucher','parent' => "4",'menu_level' => "2",],
               [ 'id_menu' => 5,  'id' => '#',             'type' => 'folder','text' => 'Akutansi','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 51, 'id' => '#',             'type' => 'folder','text' => 'Preferensi','parent' => "5",'menu_level' => "2",],
               [ 'id_menu' => 511,'id' => 'acct-account',             'type' => 'file','text' => 'No Perkiraan','parent' => "51",'menu_level' => "3",],
               [ 'id_menu' => 512,'id' => 'acct-account-setting',             'type' => 'file','text' => 'Setting Jurnal','parent' => "51",'menu_level' => "3",],
               [ 'id_menu' => 52, 'id' => 'journal-voucher',             'type' => 'file','text' => 'Jurnal Umum','parent' => "5",'menu_level' => "2",],
               [ 'id_menu' => 53, 'id' => 'journal-memorial',             'type' => 'file','text' => 'Jurnal Memorial','parent' => "5",'menu_level' => "2",],
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
