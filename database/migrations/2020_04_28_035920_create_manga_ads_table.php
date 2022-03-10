<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMangaAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manga_ads', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('id');
            $table->integer('object_id')->comment('id cua object');
            $table->string('table_name')->comment('ten bang so huu');
            $table->string('link')->comment('link quang cao');
            $table->string('artical')->comment('link anh quang cao');  
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
        Schema::dropIfExists('manga_ads');
    }
}
