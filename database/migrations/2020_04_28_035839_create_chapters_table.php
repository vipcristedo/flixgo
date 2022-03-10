<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('id');
            $table->integer('manga_id')->comment('id cua manga');
            $table->string('name')->comment('ten');
            $table->string('slug')->comment('duong dan');
            $table->integer('chap')->comment('tap so may');
            $table->integer('status')->comment('trang thai');
            $table->text('description')->comment('mo ta');
            $table->integer('release_year')->comment('nam xuat ban');
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
        Schema::dropIfExists('chapters');
    }
}
