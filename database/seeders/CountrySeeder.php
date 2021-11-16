<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\CountryTranslation;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country= Country::create([
            'phonecode'=>'9665',
            'continent'=>'asia'
         ]);

         CountryTranslation::create(['name'=>'السعودية','nationality'=>'سعودى','country_id'=>$country->id,'locale'=>'ar']);
         CountryTranslation::create(['name'=>'saudia','nationality'=>'Egyptian','country_id'=>$country->id,'locale'=>'en']);
    }
}
