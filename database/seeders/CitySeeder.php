<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\CityTranslation;
use App\Models\Country;

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country= Country::first();

       $city =  City::create(['country_id' => $country->id]);
         CityTranslation::create(['name'=>'الرياض','city_id'=>$city->id,'locale'=>'ar']);
         CityTranslation::create(['name'=>'alrayad','city_id'=>$city->id,'locale'=>'en']);

         $city1 =  City::create(['country_id' => $country->id]);
         CityTranslation::create(['name'=>'جدة','city_id'=>$city1->id,'locale'=>'ar']);
         CityTranslation::create(['name'=>'gada','city_id'=>$city1->id,'locale'=>'en']);

         $city2 =  City::create(['country_id' => $country->id]);
         CityTranslation::create(['name'=>'الدمام','city_id'=>$city2->id,'locale'=>'ar']);
         CityTranslation::create(['name'=>'dammam','city_id'=>$city2->id,'locale'=>'en']);
    }
}
