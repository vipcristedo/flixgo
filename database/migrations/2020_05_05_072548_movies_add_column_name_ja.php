<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoviesAddColumnNameJa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movies', function (Blueprint $table) {
            if (!Schema::hasColumn('movies', 'name_ja')) {
                $table->string('name_ja')->after('name')->comment('Tên tiếng nhật');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movies', function (Blueprint $table) {
            if (Schema::hasColumn('movies', 'name_ja')) {
                $table->dropColumn('name_ja');
            }
        });
    }
}
