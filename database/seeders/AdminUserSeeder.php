<?php
// database/seeders/AdminUserSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@bayanopen.com'],
            [
                'name'     => 'Admin Bayan Open',
                'email'    => 'admin@bayanopen.com',
                'password' => Hash::make('admin123!'),
            ]
        );

        $this->command->info('Admin user created:');
        $this->command->line('  Email: admin@bayanopen.com');
        $this->command->line('  Password: admin123!');
    }
}