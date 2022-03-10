<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('admins')->truncate();

    	DB::table('admins')->insert([
        	'name'=>'admin',
        	'email'=>'admin@zent.vn',
        	'role'=>'1',
        	'phone'=>'123456789',
        	'address'=>'HN',
        	'password'=>bcrypt('123456'),
        	'user_created_id'=>'1',
        	'user_updated_id'=>'1',
        	'created_at'=>'2020-04-24 22:03:58',
        ]);
    }
}
