<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdminRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_role', function (Blueprint $table) {
            $table->integer('user_created_id')->nullable()->change();
            $table->integer('user_updated_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->integer('user_created_id')->nullable()->change();
            $table->integer('user_updated_id')->nullable()->change();
        });
    }
}
