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
            'email' => 'superadmin@labtnu.id',
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password'),
        ]);
        $super->assignRole('superadmin');

        // Admin
        $admin = User::firstOrCreate([
            'email' => 'admin@labtnu.id',
        ], [
            'name' => 'Admin Lab',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        // Monitor
        $monitor = User::firstOrCreate([
            'email' => 'monitor@labtnu.id',
        ], [
            'name' => 'Monitor Kaprodi',
            'password' => bcrypt('password'),
        ]);
        $monitor->assignRole('monitor');

        // Pengguna
        $user = User::firstOrCreate([
            'email' => 'pengguna@labtnu.id',
        ], [
            'name' => 'Mahasiswa',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('pengguna');

        $users = [
            ['ADITYA ALAM FIRMANSYAH', '30222001', 'pengguna'],
            ['AGOSTINHO DA COSTA', '30222002', 'pengguna'],
            ['ALAN MAULANA ADAMS', '30222003', 'pengguna'],
            ['ANTONIO MOUZINHO DE DEUS PINTO', '30222005', 'pengguna'],
            ['AMELIA PUTRI KARTIKASARI', '30222006', 'pengguna'],
            ['ASWANDI', '30222007', 'pengguna'],
            ['DANANDARU SAKTYASIDI', '30222008', 'pengguna'],
            ['DENY KURNIAWAN PRASETYO', '30222009', 'pengguna'],
            ['DIMAS ANUNG NUGROHO', '30222010', 'pengguna'],
            ['DWI ANGGER LAILATUL RIFA', '30222011', 'pengguna'],
            ['FIEL SALVADOR RANGEL DA CUNHA BRAZ', '30222012', 'pengguna'],
            ['GESTI PUTRI AULIA', '30222013', 'pengguna'],
            ['LYDIA CASCADIA', '30222014', 'pengguna'],
            ['M ROIM', '30222015', 'pengguna'],
            ['M. ZAINUL MUTTAQIN', '30222016', 'pengguna'],
            ['NIKEN AYU DWI ANDINI', '30222017', 'pengguna'],
            ['RIFAL FAISAL', '30222018', 'pengguna'],
            ['RIFQI ZAZWAN', '30222019', 'pengguna'],
            ['SAFIRA CALVINDA PUTRI', '30222020', 'pengguna'],
            ['SAFIRA WHINAR PRAMESTI', '30222021', 'pengguna'],
            ['SARI NASTITI NALURITA', '30222022', 'pengguna'],
            ['SONY SETYAWAN', '30222023', 'pengguna'],
            ['BIMA ANDHIKA PUTRA W', '30223001', 'pengguna'],
            ['DESWINDA ROSALIA PUTRI', '30223002', 'pengguna'],
            ['FIDELIA FERNANDES', '30223003', 'pengguna'],
            ['GRACA DELIDIA MOUNDIAS M.S.', '30223004', 'pengguna'],
            ['SURYA ENDAR PRATAMA', '30223005', 'pengguna'],
            ['RUNI MUTHIA MUGNIY', '30223006', 'pengguna'],
            ['ABHARINA HASNA SHALIHA', '30224001', 'pengguna'],
            ['AGATHA ZAYYAN SURYAWAN PUTRA', '30224002', 'pengguna'],
            ['ALVIN NOVANDA GALUH PUTRA', '30224003', 'pengguna'],
            ['DWI CHOLIFATI\'AH', '30224004', 'pengguna'],
            ['FAIZ ATTARSYAH SILALAHI', '30224006', 'pengguna'],
            ['M.NAZIEL EKA REZKIANSYAH', '30224007', 'pengguna'],
            ['NAUFAL ABIYYAN TSQUIF', '30224008', 'pengguna'],
            ['BAMBANG BAGUS HARIANTO', '19810915 200502 1 001', 'pengguna'],
            ['NYARIS PAMBUDIYATNO', '19820525 200502 1 001', 'pengguna'],
            ['YUYUN SUPRAPTO', '19820107 200502 2 001', 'pengguna'],
            ['ADE IRFANSYAH', '19801125 200212 1 002', 'pengguna'],
            ['TEGUH IMAM SUHARTO', '19910913 201503 1 003', 'pengguna'],
            ['ARGO PRAGOLO', 'argo', 'pengguna'],
            ['IRENE PUTRI', 'irene', 'admin'],
            ['PUJI SISWANDI', 'puji', 'admin'],
            ['AHMAD BAHRAWI', '19800517 200012 1 003', 'monitor'],
            ['BAMBANG DRIYONO', '19710305 199301 1 001', 'monitor'],
            ['SETYO HARIYADI SURANTO PUTRO', '19790824 200912 1 001', 'monitor'],
            ['PARJAN', '19770127 200212 1 001', 'monitor'],
            ['ADE IRFANSYAH', '19801125 200212 1 002', 'monitor'],
            ['MAX GENTA SULKANI', '19860125 200912 1 005', 'superadmin'],
            ['AMELIA PUTRI KARTIKASARI', '30222006', 'superadmin'],
        ];

        foreach ($users as [$name, $nit_nip, $role]) {
            $emailPrefix = preg_replace('/\D/', '', $nit_nip); // ambil digit saja
            $emailPrefix = substr($emailPrefix, 0, 8);
            if (strlen($emailPrefix) < 8) {
                // fallback: pakai nit_nip asli jika <8 digit
                $emailPrefix = substr($nit_nip, 0, 8);
            }
            $email = strtolower($emailPrefix . $role . '@labtnu.id');

            $user = User::firstOrCreate([
                'email' => $email,
            ], [
                'name' => $name,
                'nit_nip' => $nit_nip,
                'password' => bcrypt('password'),
            ]);
            $user->assignRole($role);
        }
    }
}