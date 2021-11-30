<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FaqTranslation;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faq = Faq::create([]);
        FaqTranslation::create(['faq_id'=>$faq->id,'question'=>'how to add new service?','answer'=>'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters','locale'=>'en']);
        FaqTranslation::create(['faq_id'=>$faq->id,'question'=>'كيف تضيف خدمة جديدة؟','answer'=>'لوريم إيبسوم(Lorem Ipsum) هو ببساطة نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر عندما قامت مطبعة مجهولة برص مجموعة من الأحرف بشكل عشوائي أخذتها من نص، لتكوّن كتيّب بمثابة دليل أو مرجع شكلي لهذه الأحرف','locale'=>'ar']);

        $faq1 = Faq::create([]);
        FaqTranslation::create(['faq_id'=>$faq1->id,'question'=>'how to contact with ISPs?','answer'=>'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters','locale'=>'en']);
        FaqTranslation::create(['faq_id'=>$faq1->id,'question'=>'كيف تتصل ب ISPs?','answer'=>'لوريم إيبسوم(Lorem Ipsum) هو ببساطة نص شكلي (بمعنى أن الغاية هي الشكل وليس المحتوى) ويُستخدم في صناعات المطابع ودور النشر. كان لوريم إيبسوم ولايزال المعيار للنص الشكلي منذ القرن الخامس عشر عندما قامت مطبعة مجهولة برص مجموعة من الأحرف بشكل عشوائي أخذتها من نص، لتكوّن كتيّب بمثابة دليل أو مرجع شكلي لهذه الأحرف','locale'=>'ar']);

    }
}
