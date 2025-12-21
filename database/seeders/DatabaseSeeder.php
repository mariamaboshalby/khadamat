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
    

    // Run seeders in correct order
    $this->call([
        RolePermissionSeeder::class,
        AdminUserSeeder::class,
        SpecializationSeeder::class,
        ServiceSeeder::class,
        UpdateServiceSpecializationSeeder::class,
        OfferSeeder::class,
        CustomerSeeder::class,
        TechnicianSeeder::class,
        RequestSeeder::class,
        ReviewSeeder::class,
        StrategySeeder::class,
    ]);

    }
}
