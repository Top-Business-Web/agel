<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Country;

class CitySeeder extends Seeder
{
    public function run()
    {
        $country = Country::where('name', 'السعودية')->first();

        $cities = [
            'الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'الظهران', 'القطيف',
            'بريدة', 'عنيزة', 'الرس', 'حفر الباطن', 'الجبيل', 'الخرج', 'الزلفي', 'المجمعة',
            'ينبع', 'تبوك', 'الطائف', 'أبها', 'خميس مشيط', 'جازان', 'نجران', 'الباحة',
            'سكاكا', 'عرعر', 'القريات', 'بيشة', 'الوجه', 'الليث', 'القنفذة', 'رابغ',
            'محايل عسير', 'صبيا', 'شرورة', 'حائل', 'رفحاء', 'طريف', 'الهفوف', 'المبرز',
            'وادي الدواسر', 'بلجرشي', 'الدلم', 'الدوادمي', 'المذنب', 'رأس تنورة', 'الأفلاج',
            'تربة', 'بدر', 'ضباء', 'المخواة', 'السليل', 'تثليث', 'خليص', 'أملج', 'الخرمة'
        ];


        foreach ($cities as $city) {
            City::create([
                'name' => $city,
                'country_id' => $country->id,
                'status' => 1
            ]);
        }
    }
}
