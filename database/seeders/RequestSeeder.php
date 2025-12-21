<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Request as RequestModel;
use App\Models\User;
use App\Models\Service;

class RequestSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('user_type', 'customer')->get();
        
        // If no customers by user_type, try by role
        if ($customers->isEmpty()) {
            $customers = User::whereHas('roles', function($q) {
                $q->where('name', 'customer');
            })->get();
        }

        $services = Service::whereNotNull('specialization_id')->get();

        if ($customers->isEmpty() || $services->isEmpty()) {
            $this->command->info('No customers or services found.');
            return;
        }

        $requests = [
            [
                'description' => 'تسريب في الحمام يحتاج إصلاح عاجل',
                'address' => 'شارع الجامعة، المعادي، القاهرة',
                'status' => 'approved',
            ],
            [
                'description' => 'انقطاع الكهرباء في غرفة النوم',
                'address' => 'شارع النيل، الزمالك، القاهرة',
                'status' => 'approved',
            ],
            [
                'description' => 'المكيف لا يعمل ويحتاج صيانة',
                'address' => 'شارع التحرير، وسط البلد، القاهرة',
                'status' => 'approved',
            ],
            [
                'description' => 'دهان الشقة بالكامل',
                'address' => 'شارع الهرم، الجيزة',
                'status' => 'approved',
            ],
        ];

        foreach ($requests as $requestData) {
            RequestModel::create([
                'user_id' => $customers->random()->id,
                'service_id' => $services->random()->id,
                'description' => $requestData['description'],
                'address' => $requestData['address'],
                'status' => $requestData['status'],
                'scheduled_at' => now()->addDays(rand(1, 7)),
                'latitude' => 30.0444 + (rand(-100, 100) / 1000),
                'longitude' => 31.2357 + (rand(-100, 100) / 1000),
            ]);
        }
    }
}