<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMangasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mangas', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('id');
            $table->string('name')->comment('ten');
            $table->string('slug')->comment('duong dan');
            $table->string('author')->comment('tac gia');
            $table->text('description')->comment('mo ta');
            $table->string('card_cover')->comment('anh bia');
            $table->integer('country')->comment('id');
            $table->integer('status')->comment('trang thai');
            $table->integer('age')->comment('tuoi');
            $table->integer('release_year')->comment('nam xuat ban');
            $table->integer('rate')->comment('diem danh gia');
            $table->integer('nominations')->comment('uu tien');
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
        Schema::dropIfExists('mangas');
    }
}
