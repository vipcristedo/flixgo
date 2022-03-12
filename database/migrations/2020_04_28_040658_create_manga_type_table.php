<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMangaTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manga_type', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('id');
            $table->unsignedBigInteger('manga_id')->comment('id cua manga');
            $table->unsignedBigInteger('type_id')->comment('id cua type');
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
        Schema::dropIfExists('manga_type');
    }
}
