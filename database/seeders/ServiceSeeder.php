<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'سباكة',
                'icon' => 'fa-faucet',
                'color_class' => 'blue',
                'route_name' => 'plumbing'
            ],
            [
                'name' => 'كهرباء',
                'icon' => 'fa-lightbulb',
                'color_class' => 'yellow',
                'route_name' => 'electricity'
            ],
            [
                'name' => 'تكييف',
                'icon' => 'fa-snowflake',
                'color_class' => 'cyan',
                'route_name' => 'ac'
            ],
            [
                'name' => 'نقاشة',
                'icon' => 'fa-paint-roller',
                'color_class' => 'orange',
                'route_name' => 'painting'
            ],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['name' => $service['name']], // Check by name
                $service
            );
        }
    }
}
