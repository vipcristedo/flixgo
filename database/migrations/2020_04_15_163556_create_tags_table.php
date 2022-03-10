<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('tags', function (Blueprint $table) {
       $table->bigIncrements('id')->comment('id');
       $table->string('name')->comment('ten');
       $table->string('slug')->nullable()->comment('duong dan');
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
      Schema::dropIfExists('tags');
    }
  }
