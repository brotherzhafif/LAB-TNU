<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::firstOrCreate(
        //     ['email' => 'superadmin@labtnu.com'],
        //     ['name' => 'Super Admin', 'password' => bcrypt('superadmin')]
        // );
        $this->call([
            RolePermissionSeeder::class,
        ]);
    }
}
