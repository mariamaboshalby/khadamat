<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Specialization;

class UpdateServiceSpecializationSeeder extends Seeder
{
    public function run(): void
    {
        $serviceSpecializationMap = [
            'سباكة' => 'سباكة',
            'كهرباء' => 'كهرباء', 
            'تكييف' => 'تكييف وتبريد',
            'نقاشة' => 'دهانات',
        ];

        foreach ($serviceSpecializationMap as $serviceName => $specializationName) {
            $service = Service::where('name', $serviceName)->first();
            $specialization = Specialization::where('name', $specializationName)->first();
            
            if ($service && $specialization) {
                $service->update(['specialization_id' => $specialization->id]);
            }
        }
    }
}