<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_values', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('id');
            $table->unsignedBigInteger('option_id')->comment('id cua option');
            $table->string('name')->comment('ten');
            $table->integer('order')->comment('so thu tu');
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
        Schema::dropIfExists('options_values');
    }
}
