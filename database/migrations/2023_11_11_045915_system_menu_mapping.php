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
        if(!Schema::hasTable('system_menu_mapping')) {
            Schema::create('system_menu_mapping', function (Blueprint $table) {
                $table->id('menu_mapping_id');
                $table->unsignedBigInteger('company_id')->nullable();
                $table->integer('user_group_level')->nullable();
                $table->string('id_menu')->nullable();
                $table->foreign('id_menu')->references('id_menu')->on('system_menu')->onUpdate('cascade')->onDelete('cascade');
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });
             // Insert admin user
            DB::table('system_menu_mapping')->insert([
                ['user_group_level' => 1,'id_menu' => 1  ],
                ['user_group_level' => 1,'id_menu' => 2  ],
                ['user_group_level' => 1,'id_menu' => 3  ],
                ['user_group_level' => 1,'id_menu' => 4  ],
                ['user_group_level' => 1,'id_menu' => 5  ],
                ['user_group_level' => 1,'id_menu' => 6  ],
                ['user_group_level' => 1,'id_menu' => 7  ],
                ['user_group_level' => 1,'id_menu' => 8  ],
                ['user_group_level' => 1,'id_menu' => 9  ],
                // ['user_group_level' => 1,'id_menu' => 9  ],
                // ['user_group_level' => 1,'id_menu' => 91 ],
                // ['user_group_level' => 1,'id_menu' => 911 ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('system_menu_mapping');
    }
};
