<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(WeblistsTableSeeder::class);
        $this->call(ListitemsTableSeeder::class);
        $this->call(ListitemWeblistsTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(ListitemTagsTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionUserWeblistsTableSeeder::class);
    }
}
