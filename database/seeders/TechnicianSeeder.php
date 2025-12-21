<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Technician;
use App\Models\Specialization;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TechnicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = Specialization::all();

        if ($specializations->isEmpty()) {
            $this->command->info('No specializations found. Please run SpecializationSeeder first.');
            return;
        }

        $technicians = [
            [
                'name' => 'محمد حسن الكهربائي',
                'email' => 'mohammed.tech@example.com',
                'phone' => '0500000001',
                'specialization' => 'كهرباء',
                'bio' => 'فني كهرباء خبرة 10 سنوات في التمديدات والصيانة.',
            ],
            [
                'name' => 'علي حسين السباك',
                'email' => 'ali.tech@example.com',
                'phone' => '0500000002',
                'specialization' => 'سباكة',
                'bio' => 'سباك محترف متخصص في كشف التسربات.',
            ],
            [
                'name' => 'سعيد النجارة',
                'email' => 'saeed.tech@example.com',
                'phone' => '0500000003',
                'specialization' => 'نجارة',
                'bio' => 'أعمال نجارة وتركيب أبواب وشبابيك.',
            ],
            [
                'name' => 'خالد التكييف',
                'email' => 'khalid.tech@example.com',
                'phone' => '0500000004',
                'specialization' => 'تكييف وتبريد',
                'bio' => 'صيانة وتركيب مكيفات سبليت ومركزي.',
            ],
            [
                'name' => 'عمر الدهان',
                'email' => 'omar.tech@example.com',
                'phone' => '0500000005',
                'specialization' => 'دهانات',
                'bio' => 'دهانات داخلية وخارجية وديكورات حديثة.',
            ],
        ];

        foreach ($technicians as $techData) {
            // Find specialization
            $specialization = $specializations->firstWhere('name', $techData['specialization']);
            
            // If specific specialization not found, pick random
            if (!$specialization) {
                $specialization = $specializations->random();
            }

            // Create User
            $user = User::firstOrCreate(
                ['email' => $techData['email']],
                [
                    'name' => $techData['name'],
                    'phone' => $techData['phone'],
                    'password' => Hash::make('password123'),
                    'user_type' => 'technician',
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'remember_token' => Str::random(10),
                ]
            );

            // Assign Role if using Spatie Permissions (optional check)
            if (method_exists($user, 'assignRole')) {
                try {
                    $user->assignRole('technician');
                } catch (\Exception $e) {
                    // Role might not exist or not set up, ignore for now
                }
            }

            // Create Technician Profile
            Technician::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'specialization_id' => $specialization->id,
                    'bio' => $techData['bio'],
                    'availability_status' => 'available',
                    'rating' => rand(3, 5),
                    'completed_tasks' => rand(0, 50),
                ]
            );
        }
    }
}
