<?php

use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('types')->truncate();
        \Db::table('types')->insert([
            'title' => 'Actions',
            'description' => 'Description',
            'slug' => 'actions',
            'table_name' => 'movies',
            'user_created_id' => '1',
            'user_updated_id' => '1',
            'created_at' => '2020-04-24 22:03:58',
        ]);
        \Db::table('types')->insert([
            'title' => 'Cartoons',
            'description' => 'Cartoons',
            'slug' => 'cartoons',
            'table_name' => 'movies',
            'user_created_id' => '1',
            'user_updated_id' => '1',
            'created_at' => '2020-04-24 22:03:58',
        ]);

    }
}
