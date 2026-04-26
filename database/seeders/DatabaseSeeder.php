<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'), // Menghasilkan hash bcrypt yang valid
            'role' => 'admin',
        ]);

        
        User::create([
            'name' => 'Anggota Biasa',
            'email' => 'anggota@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'anggota',
        ]);
        
        echo "Seed data berhasil dibuat! \n";
        echo "Admin: admin@gmail.com | password123 \n";
        echo "Anggota: anggota@gmail.com | password123 \n";
    }
}