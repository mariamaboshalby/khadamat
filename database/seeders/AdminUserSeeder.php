<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
                   'name' => 'admin',
        'email' => 'admin@admin.com',
        'phone' => '01143466685',
        'password' => Hash::make('12345678'),
            'user_type' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Assign admin role
        $admin->assignRole('admin');
    }
}
