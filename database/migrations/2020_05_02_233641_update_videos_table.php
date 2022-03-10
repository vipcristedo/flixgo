<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->integer('movie_id')->nullable()->change();
            $table->string('slug')->nullable()->change();
            $table->string('tags')->nullable()->change();
            $table->integer('status')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->integer('movie_id')->nullable()->change();
            $table->string('slug')->nullable()->change();
            $table->string('tags')->nullable()->change();
            $table->integer('status')->default(1)->change();
        });
    }
}
