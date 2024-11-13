<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $saudi_cities = collect([
            "الرياض",
            "جدة",
            "مكة المكرمة",
            "المدينة المنورة",
            "الدمام",
            "الخبر",
            "القصيم",
            "تبوك",
            "الطائف",
            "الأحساء",
            "نجران",
            "عسير",
            "جازان",
            "بريدة",
            "المدينة الصناعية بجدة",
            "خميس مشيط",
            "الحدود الشمالية",
            "بيشة",
            "الزلفي",
            "الجبيل",
            "رابغ",
            "حائل",
            "ابها",
            "القنفذة",
            "الدوادمي",
            "المجمعة",
            "الدرعية",
            "الجبيل الصناعية"
        ])->map(fn($v) => ['name' => $v])
            ->toArray();

        City::insert($saudi_cities);
    }
}
