<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(OptionsTableSeeder::class);
        $this->call(OptionsValuesTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(Admin_RoleTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(Permission_RoleTableSeeder::class);
        $this->call(MoviesTableSeeder::class);
        $this->call(TypesTableSeeder::class);
    }
}
