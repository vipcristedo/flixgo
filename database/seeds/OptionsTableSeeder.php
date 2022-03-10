<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Option;

class OptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Option::truncate();
        DB::table('options')->insert(
            [
                ['name'=>'genre','user_created_id'=>1,'user_updated_id'=>1],
                ['name'=>'quality','user_created_id'=>1,'user_updated_id'=>1],
                ['name'=>'language','user_created_id'=>1,'user_updated_id'=>1],
                ['name'=>'is_active','user_created_id'=>1,'user_updated_id'=>1],
                ['name'=>'status','user_created_id'=>1,'user_updated_id'=>1,],
                ['name'=>'country','user_created_id'=>1,'user_updated_id'=>1],
                ['name'=>'channel_type','user_created_id'=>1,'user_updated_id'=>1]
            ]
        );
    }
}
