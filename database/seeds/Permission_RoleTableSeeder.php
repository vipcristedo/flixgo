<?php

use Illuminate\Database\Seeder;

class Permission_RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permission_role')->truncate();

    }
}
