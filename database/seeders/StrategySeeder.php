<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Strategy;

class StrategySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. تشريح "ثغرة" العميل
        Strategy::create([
            'title' => 'تشريح "ثغرة" العميل',
            'description' => 'الوضع الحالي: أغلب طالبي الخدمات لديهم "الإحباط" من عدم إيجاد فني محترف.',
            'step_number' => 1,
            'color' => '#6366f1', // Indigo
            'points' => [
                [
                    'title' => 'الثغرة الأولى: "سرعة قبل الجودة"',
                    'icon' => 'fa-lightbulb',
                    'color' => '#6366f1',
                    'items' => [
                        'المتجر الموحد (One-Stop Shop)',
                        'تزويد فني بقطع قبل أن يصل لك'
                    ]
                ],
                [
                    'title' => 'الثغرة الثانية: "السعر المطموح"',
                    'icon' => 'fa-chart-line',
                    'color' => '#10b981', // Emerald
                    'items' => [
                        'الحزم الواضحة (Bundling)',
                        'السعر يشمل (القطعة + التركيب + الانتقال)'
                    ]
                ]
            ]
        ]);

        // 2. الإيمان والتطابق
        Strategy::create([
            'title' => 'الإيمان والتطابق',
            'description' => null,
            'step_number' => 2,
            'color' => '#8b5cf6', // Violet
            'points' => [
                [
                    'title' => 'تقرير فني بورقة مهندس',
                    'icon' => 'fa-shield-alt',
                    'color' => '#ef4444', // Red
                    'items' => [
                        'الورقة المرفقة (تشمل الصيانة والتعقيم)',
                        'مستندك بيدك عشان يسلموك'
                    ]
                ]
            ]
        ]);

        // 3. التتبع بعد صمت
        Strategy::create([
            'title' => 'التتبع بعد صمت',
            'description' => null,
            'step_number' => 3,
            'color' => '#f59e0b', // Amber
            'points' => [
                [
                    'title' => 'ضمان الفني لا يرد على التليفون',
                    'icon' => 'fa-headset',
                    'color' => '#f59e0b',
                    'items' => [
                        'متابعة تلقائية',
                        'السجل الفعلي للمتابعة'
                    ]
                ]
            ]
        ]);

        // 4. ضمان الفعل
        Strategy::create([
            'title' => 'ضمان الفعل',
            'description' => null,
            'step_number' => 4,
            'color' => '#3b82f6', // Blue
            'points' => [
                [
                    'title' => 'التعامل مع شركة لها سجلات',
                    'icon' => 'fa-clipboard-check',
                    'color' => '#3b82f6',
                    'items' => [
                        'سجلات وتنظيف وأوراق من الكود',
                        'السجل الفعلي للمتابعة'
                    ]
                ]
            ]
        ]);
    }
}
