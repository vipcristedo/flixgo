<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('id');
            $table->string('name')->comment('ten');
            $table->text('description')->comment('mo ta');
            $table->string('card_cover')->comment('link anh bia');
            $table->integer('age')->comment('tuoi');
            $table->boolean('nominations')->default(0)->comment('uu tien');
            $table->integer('genre')->comment('phan loai');
            $table->integer('runtime')->comment('thoi luong');
            $table->integer('release_year')->comment('nam san xuat');
            $table->integer('quality')->comment('chat luong');
            $table->integer('country')->comment('quoc gia');
            $table->integer('rate')->comment('diem danh gia');
            $table->string('slug')->comment('ten');
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
        Schema::dropIfExists('movies');
    }
}
