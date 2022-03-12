<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('id');
            $table->string('title')->comment('tieu de');
            $table->unsignedBigInteger('movie_id')->comment('id cua movie');
            $table->text('description')->nullable()->comment('mo ta');
            $table->integer('order')->comment('so thu tu');
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
        Schema::dropIfExists('playlists');
    }
}
