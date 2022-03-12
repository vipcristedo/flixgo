<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_tag', function (Blueprint$table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('movie_id');
            $table->unsignedBigInteger('tag_id');
            $table->unsignedBigInteger('user_created_id');
            $table->unsignedBigInteger('user_updated_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movie_tag');
    }
}
