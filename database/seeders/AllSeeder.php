<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;

class AllSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            GroupSeeder::class,
            PermissionTableSeeder::class,
            RoleTableSeeder::class,
            UserTableSeeder::class,
            BulkUserTableSeeder::class,
            CategoryTableSeeder::class
        ]);
    }
}