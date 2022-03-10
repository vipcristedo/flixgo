<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('types', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('id');
            $table->string('title')->comment('tieu de');
            $table->text('description')->comment('mo ta');
            $table->string('slug')->comment('duong dan');
            $table->string('table_name')->comment('ten cua bang co the loai');
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
        Schema::dropIfExists('types');
    }
}
