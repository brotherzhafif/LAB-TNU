<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Buat Role
        $roles = ['superadmin', 'admin', 'monitor', 'pengguna'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Superadmin
        $super = User::firstOrCreate([
            'email' => 'superadmin@labtnu.test',
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password'),
        ]);
        $super->assignRole('superadmin');

        // Admin
        $admin = User::firstOrCreate([
            'email' => 'admin@labtnu.test',
        ], [
            'name' => 'Admin Lab',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // Monitor
        $monitor = User::firstOrCreate([
            'email' => 'monitor@labtnu.test',
        ], [
            'name' => 'Monitor Kaprodi',
            'password' => bcrypt('password'),
        ]);
        $monitor->assignRole('monitor');

        // Pengguna
        $user = User::firstOrCreate([
            'email' => 'pengguna@labtnu.test',
        ], [
            'name' => 'Mahasiswa',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('pengguna');
    }
}