<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Option_value;

class OptionsValuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Option_value::truncate();
        DB::table('option_values')->insert([
                ['option_id'=>1,'name'=>'Movies','description'=>'Movie','order'=>1,'user_created_id'=>1,'user_updated_id'=>1],
                ['option_id'=>1,'name'=>'TV Series','description'=>'TV Series','order'=>2,'user_created_id'=>1,'user_updated_id'=>1],
                ['option_id'=>2,'name'=>'HD','description'=>'hd','order'=>1,'user_created_id'=>1,'user_updated_id'=>1],
                ['option_id'=>2,'name'=>'FULL-HD','description'=>'full hd','order'=>2,'user_created_id'=>1,'user_updated_id'=>1],
                ['option_id'=>2,'name'=>'2K','description'=>'full hd','order'=>3,'user_created_id'=>1,'user_updated_id'=>1],
                ['option_id'=>2,'name'=>'4K','description'=>'full hd','order'=>4,'user_created_id'=>1,'user_updated_id'=>1],
                ['option_id'=>6,'name'=>'Vietnam','description'=>'VietNam','order'=>1,'user_created_id'=>1,'user_updated_id'=>1],
                ['option_id'=>6,'name'=>'Japan','description'=>'Japan','order'=>2,'user_created_id'=>1,'user_updated_id'=>1],
                ['option_id'=>6,'name'=>'USA','description'=>'USA','order'=>2,'user_created_id'=>1,'user_updated_id'=>1],
                ['option_id'=>7,'name'=>'Youtube','description'=>'youtube','order'=>1,'user_created_id'=>1,'user_updated_id'=>1],
                ['option_id'=>7,'name'=>'Sever','description'=>'Sever','order'=>2,'user_created_id'=>1,'user_updated_id'=>1],
        ]);
    }
}
