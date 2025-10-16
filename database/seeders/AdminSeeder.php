<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('email', 'admin@lasala.cat')->exists()) {
            User::create([
                'name' => 'Admin LaSala',
                'email' => 'admin@lasala.cat',
                'password' => Hash::make('LaSala2025!'),
                'rol' => UserRole::ADMIN,
            ]);

            $this->command->info('✅ Admin creat');
        } else {
            $this->command->info('ℹ️  Admin ja existeix');
        }
    }
}
