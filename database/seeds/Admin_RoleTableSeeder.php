<?php

use Illuminate\Database\Seeder;

class Admin_RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_role')->truncate();
    }
}
