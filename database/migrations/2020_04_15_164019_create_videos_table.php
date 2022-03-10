<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('id');
            $table->string('title')->comment('mo ta');
            $table->string('tags')->comment('the tag');
            $table->text('description')->comment('mo ta');
            $table->integer('movie_id')->comment('id cua movie');
            $table->integer('playlist_id')->nullable()->comment('id cua playlist');
            $table->integer('status')->default(1)->comment('trang thai');
            $table->integer('chap')->nullable()->comment('tap so may');
            $table->string('slug')->comment('duong dan');
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
        Schema::dropIfExists('videos');
    }
}
