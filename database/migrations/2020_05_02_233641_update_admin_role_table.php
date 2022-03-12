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
            $table->unsignedBigInteger('user_created_id')->nullable()->change();
            $table->unsignedBigInteger('user_updated_id')->nullable()->change();
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
            $table->unsignedBigInteger('user_created_id')->nullable()->change();
            $table->unsignedBigInteger('user_updated_id')->nullable()->change();
    }
}
