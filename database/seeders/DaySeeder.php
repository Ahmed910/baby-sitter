<?php

namespace Database\Seeders;

use App\Models\Day;
use App\Models\DayTranslation;
use Illuminate\Database\Seeder;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $day1 = Day::create([]);
        DayTranslation::create(['day_id'=>$day1->id,'name'=>'saturday','locale'=>'en']);
        DayTranslation::create(['day_id'=>$day1->id,'name'=>'السبت','locale'=>'ar']);

        $day2 = Day::create([]);
        DayTranslation::create(['day_id'=>$day2->id,'name'=>'sunday','locale'=>'en']);
        DayTranslation::create(['day_id'=>$day2->id,'name'=>'الاحد','locale'=>'ar']);

        $day3 = Day::create([]);
        DayTranslation::create(['day_id'=>$day3->id,'name'=>'monday','locale'=>'en']);
        DayTranslation::create(['day_id'=>$day3->id,'name'=>'الاثنين','locale'=>'ar']);

        $day4 = Day::create([]);
        DayTranslation::create(['day_id'=>$day4->id,'name'=>'tuesday','locale'=>'en']);
        DayTranslation::create(['day_id'=>$day4->id,'name'=>'الثلاثاء','locale'=>'ar']);

        $day5 = Day::create([]);
        DayTranslation::create(['day_id'=>$day5->id,'name'=>'wednesday','locale'=>'en']);
        DayTranslation::create(['day_id'=>$day5->id,'name'=>'الأربعاء','locale'=>'ar']);

        $day6 = Day::create([]);
        DayTranslation::create(['day_id'=>$day6->id,'name'=>'thursday','locale'=>'en']);
        DayTranslation::create(['day_id'=>$day6->id,'name'=>'الخميس','locale'=>'ar']);

        $day7 = Day::create([]);
        DayTranslation::create(['day_id'=>$day7->id,'name'=>'friday','locale'=>'en']);
        DayTranslation::create(['day_id'=>$day7->id,'name'=>'الجمعة','locale'=>'ar']);

    }
}
