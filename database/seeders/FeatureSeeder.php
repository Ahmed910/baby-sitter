<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\FeatureTranslation;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $feature = Feature::create([]);
        FeatureTranslation::create(['feature_id'=>$feature->id,'name'=>'outdoor playground','locale'=>'en']);
        FeatureTranslation::create(['feature_id'=>$feature->id,'name'=>'ملعب خارحي','locale'=>'ar']);

        $feature1 = Feature::create([]);
        FeatureTranslation::create(['feature_id'=>$feature1->id,'name'=>'independent bedroom','locale'=>'en']);
        FeatureTranslation::create(['feature_id'=>$feature1->id,'name'=>'غرفة نوم مستقلة','locale'=>'ar']);

        $feature2 = Feature::create([]);
        FeatureTranslation::create(['feature_id'=>$feature2->id,'name'=>'security cameras','locale'=>'en']);
        FeatureTranslation::create(['feature_id'=>$feature2->id,'name'=>'كاميرات مراقية','locale'=>'ar']);
    }
}
