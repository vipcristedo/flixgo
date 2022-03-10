<?php

use Illuminate\Database\Seeder;

class MangasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mangas')->truncate();
        // for ($i=1; $i <= 100; $i++) { 
        // 	DB::table('mangas')->insert([
        // 		'name'=>'manga'.$i,
        // 		'slug'=>'manga'.$i.'-'.$i,
        // 		'author'=>'Anonymous',
        //         'card_cover'=>'',
        // 		'description'=>'123456789',
        // 		'country'=>1,
        // 		'status'=>1,
        // 		'age'=>16,
        // 		'release_year'=>2015,
        // 		'rate'=>5,
        // 		'nominations'=>1,
        // 		'user_created_id'=>1,
        // 		'user_updated_id'=>1,
        // 		'created_at' => '2020-04-24 22:03:58',
        // 	]);
        // }
    }
}
