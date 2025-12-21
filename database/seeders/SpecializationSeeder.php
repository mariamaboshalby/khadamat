<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialization;

class SpecializationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specializations = [
            [
                'name' => 'كهرباء',
                'description' => 'أعمال الكهرباء والصيانة الكهربائية',
                'is_active' => true,
            ],
            [
                'name' => 'سباكة',
                'description' => 'أعمال السباكة وصيانة المواسير',
                'is_active' => true,
            ],
            [
                'name' => 'نجارة',
                'description' => 'أعمال النجارة والأثاث',
                'is_active' => true,
            ],
            [
                'name' => 'دهانات',
                'description' => 'أعمال الدهانات والديكور',
                'is_active' => true,
            ],
            [
                'name' => 'تكييف وتبريد',
                'description' => 'صيانة وتركيب أجهزة التكييف والتبريد',
                'is_active' => true,
            ],
            [
                'name' => 'أجهزة منزلية',
                'description' => 'صيانة الأجهزة المنزلية',
                'is_active' => true,
            ],
        ];

        foreach ($specializations as $specialization) {
            Specialization::firstOrCreate(
                ['name' => $specialization['name']],
                $specialization
            );
        }
    }
}
