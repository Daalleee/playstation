<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = ['admin', 'kasir', 'pemilik', 'pelanggan'];

        foreach ($roles as $role) {
            User::firstOrCreate(
                ['email' => $role.'@example.com'],
                [
                    'name' => ucfirst($role),
                    'password' => Hash::make('password'),
                    'role' => $role,
                    'phone' => '08123456789',
                    'address' => 'Alamat '.$role,
                ]
            );
        }
    }
}
