<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChapterPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapter_pictures', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('id');
            $table->integer('chapter_id')->comment('id cua chapter');
            $table->integer('manga_id')->comment('id cua manga');
            $table->string('link')->comment('link cua anh');
            $table->string('order')->comment('so thu tu trang');
            $table->string('title')->comment('tieu de');
            $table->integer('status')->comment('trang thai');
            $table->string('sources')->nullable()->comment('vi tri luu');
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
        Schema::dropIfExists('chapter_pictures');
    }
}
