<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceTranslation;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service = Service::create(['service_type'=>'hour']);
        ServiceTranslation::create(['service_id'=>$service->id,'name'=>'hour','locale'=>'en']);
        ServiceTranslation::create(['service_id'=>$service->id,'name'=>'ساعة','locale'=>'ar']);

        $service1 = Service::create(['service_type'=>'month']);
        ServiceTranslation::create(['service_id'=>$service1->id,'name'=>'month','locale'=>'en']);
        ServiceTranslation::create(['service_id'=>$service1->id,'name'=>'شهر','locale'=>'ar']);
    }
}
