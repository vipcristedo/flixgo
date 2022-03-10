<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('role_permission', function (Blueprint $table) {
       $table->bigIncrements('id')->comment('id');
       $table->integer('role_id')->comment('id cua role');
       $table->integer('permission_id')->comment('id cua permission');
       $table->integer('user_created_id')->comment('nguoi tao');
       $table->integer('user_updated_id')->comment('nguoi cap nhat');
       $table->timestamps();
       $table->softDeletes()->comment('thoi gian xoa');
     });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('role_permission');
    }
  }
