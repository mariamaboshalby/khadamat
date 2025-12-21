<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'أحمد محمد علي',
                'email' => 'ahmed.mohammed@example.com',
                'phone' => '0501234567',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'فاطمة عبدالله السعيد',
                'email' => 'fatima.abdullah@example.com',
                'phone' => '0559876543',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'محمد خالد العتيبي',
                'email' => 'mohammed.khaled@example.com',
                'phone' => '0562345678',
                'status' => 'inactive',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'نورة سالم الدوسري',
                'email' => 'noura.salem@example.com',
                'phone' => '0534567890',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'عبدالرحمن إبراهيم الحربي',
                'email' => 'abdulrahman.ibrahim@example.com',
                'phone' => '0587654321',
                'status' => 'suspended',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'مريم أحمد الشمري',
                'email' => 'mariam.ahmed@example.com',
                'phone' => '0512345678',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'خالد عمر القحطاني',
                'email' => 'khalid.omer@example.com',
                'phone' => '0545678901',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'سارة محمد الزهراني',
                'email' => 'sara.mohammed@example.com',
                'phone' => '0578901234',
                'status' => 'inactive',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'ياسر عبدالعزيز الغامدي',
                'email' => 'yasser.abdulaziz@example.com',
                'phone' => '0590123456',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'هناء حسن العسكر',
                'email' => 'hana.hassan@example.com',
                'phone' => '0523456789',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'فيصل تركي الرشيد',
                'email' => 'faisal.turki@example.com',
                'phone' => '0556789012',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'ليلى سعد الشهري',
                'email' => 'layla.saad@example.com',
                'phone' => '0531234567',
                'status' => 'inactive',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'عبدالله ناصر الحمود',
                'email' => 'abdullah.nasser@example.com',
                'phone' => '0567890123',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'أمل فهد البقمي',
                'email' => 'amal.fahad@example.com',
                'phone' => '0589012345',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'سعد مبارك الدعيج',
                'email' => 'saad.mubarak@example.com',
                'phone' => '0515678901',
                'status' => 'suspended',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($customers as $customer) {
            User::firstOrCreate(
                ['email' => $customer['email']],
                [
                    'name' => $customer['name'],
                    'phone' => $customer['phone'],
                    'password' => Hash::make('password123'),
                    'status' => $customer['status'],
                    'email_verified_at' => $customer['email_verified_at'],
                    'remember_token' => Str::random(10),
                    'created_at' => now()->subDays(rand(1, 365)),
                    'updated_at' => now()->subHours(rand(1, 24)),
                ]
            );
        }
    }
}
