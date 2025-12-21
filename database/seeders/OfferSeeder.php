<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Offer;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offers = [
            [
                'title' => 'عرض جيران',
                'subtitle_1' => 'الصور مجاني بشراء',
                'subtitle_2' => 'الكبير بضعف الكبير',
                'badge_text' => 'عرض اليوم',
                'discount_value' => '10%',
                'discount_label' => 'خصم',
                'gradient_class' => 'promo-1',
                'icon' => 'fa-tools',
                'discount_color_class' => null // default red
            ],
            [
                'title' => 'صيانة تكييف',
                'subtitle_1' => 'خصم خاص على',
                'subtitle_2' => 'تنظيف المكيفات',
                'badge_text' => 'لفترة محدودة',
                'discount_value' => '20%',
                'discount_label' => 'خصم',
                'gradient_class' => 'promo-2',
                'icon' => 'fa-snowflake',
                'discount_color_class' => 'yellow'
            ],
            [
                'title' => 'خدمة طوارئ',
                'subtitle_1' => 'فني كهرباء',
                'subtitle_2' => 'متاح 24 ساعة',
                'badge_text' => 'جديد',
                'discount_value' => '<i class="fas fa-bolt"></i>',
                'discount_label' => 'سريع',
                'gradient_class' => 'promo-3',
                'icon' => 'fa-bolt',
                'discount_color_class' => 'white'
            ],
        ];

        foreach ($offers as $offer) {
            Offer::create($offer);
        }
    }
}
