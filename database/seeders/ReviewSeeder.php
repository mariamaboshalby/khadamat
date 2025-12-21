<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Technician;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get real customers and technicians
        $customers = User::where('user_type', 'customer')->take(5)->get();
        $technicians = Technician::take(3)->get();

        if ($customers->isEmpty() || $technicians->isEmpty()) {
            $this->command->warn('No customers or technicians found. Skipping ReviewSeeder.');
            return;
        }

        $reviews = [
            [
                'customer_id' => $customers->first()->id,
                'technician_id' => $technicians->first()->id,
                'rating' => 5,
                'title' => 'خدمة ممتازة',
                'comment' => 'خدمة ممتازة وسريعة، الفني وصل في الموعد المحدد وقام بإصلاح العطل باحترافية. أوصي به بشدة.',
                'service_date' => now()->subDays(5),
                'service_cost' => 250.00,
                'service_type' => 'repair',
                'status' => 'approved',
                'is_verified' => true,
                'verified_at' => now(),
            ],
            [
                'customer_id' => $customers->get(1)->id ?? $customers->first()->id,
                'technician_id' => $technicians->first()->id,
                'rating' => 5,
                'title' => 'تطبيق رائع',
                'comment' => 'تطبيق رائع وسهل الاستخدام، والأسعار مناسبة جداً مقارنة بالسوق. الفني كان متعاوناً.',
                'service_date' => now()->subDays(10),
                'service_cost' => 180.00,
                'service_type' => 'maintenance',
                'status' => 'approved',
                'is_verified' => true,
                'verified_at' => now(),
            ],
            [
                'customer_id' => $customers->get(2)->id ?? $customers->first()->id,
                'technician_id' => $technicians->get(1)->id ?? $technicians->first()->id,
                'rating' => 4,
                'title' => 'تجربة جيدة',
                'comment' => 'تجربة جيدة، الفني كان محترماً وملتزماً بالإجراءات الاحترازية. الخدمة كانت سريعة.',
                'service_date' => now()->subDays(15),
                'service_cost' => 320.00,
                'service_type' => 'installation',
                'status' => 'approved',
                'is_verified' => true,
                'verified_at' => now(),
            ],
            [
                'customer_id' => $customers->get(3)->id ?? $customers->first()->id,
                'technician_id' => $technicians->get(2)->id ?? $technicians->first()->id,
                'rating' => 3,
                'title' => 'مقبول',
                'comment' => 'الخدمة كانت مقبولة، لكن هناك بعض التأخير في الوصول. الجودة كانت جيدة بشكل عام.',
                'service_date' => now()->subDays(20),
                'service_cost' => 150.00,
                'service_type' => 'repair',
                'status' => 'pending',
                'is_verified' => false,
            ],
            [
                'customer_id' => $customers->get(4)->id ?? $customers->first()->id,
                'technician_id' => $technicians->get(1)->id ?? $technicians->first()->id,
                'rating' => 5,
                'title' => 'احترافية عالية',
                'comment' => 'فني محترف جداً، فهم المشكلة بسرعة وقام بحلها بكفاءة عالية. سأتصل بهم مرة أخرى بالتأكيد.',
                'service_date' => now()->subDays(25),
                'service_cost' => 450.00,
                'service_type' => 'repair',
                'status' => 'approved',
                'is_verified' => true,
                'verified_at' => now(),
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }

        $this->command->info('Created ' . count($reviews) . ' reviews successfully.');
    }
}
