<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('channels', function (Blueprint $table) {
       $table->bigIncrements('id')->comment('id');
       $table->string('title')->comment('tieu de');
       $table->string('link')->nullable()->comment('duong dan');
       $table->integer('channels_type')->comment('the loai cua kenh')->nullable();
       $table->integer('order')->comment('so thu tu uu tien');
       $table->text('description')->nullable()->comment('mo ta');
       $table->integer('status')->default(1)->comment('trang thai');
       $table->unsignedBigInteger('user_created_id')->comment('nguoi tao');
       $table->unsignedBigInteger('user_updated_id')->comment('nguoi cap nhat');
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
      Schema::dropIfExists('channels');
    }
  }
