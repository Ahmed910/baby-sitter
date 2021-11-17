<?php
$default_msg = 'تم السماح لكم بعمل الرحلات على باقة '. (setting('price_of_default_package_order') ?? 1). ' ريال لكل رحلة. وللاستفاده بعدد لامحدود من الرحلات قم بالاشتراك بإحدى الباقات المتوفرة لدينا';
$home_msg = setting('home_msg_'.app()->getLocale()) ? setting('home_msg_'.app()->getLocale()) : $default_msg;
return[
    'auth' => [
        'reset_code_is' => 'كود استعادة كلمة المرور :code',
        'verified_code_is' => 'كود تفعيل الحساب :code',
        'failed'   => 'بيانات الاعتماد هذه غير متطابقة مع البيانات المسجلة لدينا.',
        'sign_with_not_active' => "تم التسجيل بنجاح  رجاء تفعيل حسابك ",
        'sign_with_active' => "تم التسجيل بنجاح",
        'you_already_have_password' => 'انت بالفعل لديك كلمة مرور',
        'you_should_add_password' => 'قم بإضافة كلمة مرور قبل تسجيل الخروج',
        'user_not_found' => 'رقم الهاتف غير صحيح أو الحساب غير مفعل',
        'code_true' => 'الكود المدخل صحيح',
        'code_not_true' => 'الكود غير صحيح',
        'success_change_password' => "تم تغيير كلمة المرور بنجاح",
    ],
    'messages' => [
        'success_sign_up' => "تم التسجيل بنجاح  رجاء تفعيل حسابك ",
        'cant_transfer_temp_balance' => "رصيدك الحالي لايكفي عملية التحويل (لايمكنك استخدام رصيدك المؤقت في عملية التحويل)",
        'order_hint_if_offer' => "عزيزي الكابتن رجاء العلم إذا تخطت قيمة الرحلة مبلغ :max_price ريال سيتم اضافة المبلغ (:max_price) الى محفظتك والباقي سيتم استلامه نقدا من العميل",
        'order_hint_if_offer_after_accept' => "سوف يتم اضافة مبلغ :driver_wallet_amount ريال الى محفظتك بعد انهاء الرحلة وسيقوم العميل بدفع مبلغ :client_pay_amount ريال",
        'cant_finish_order_before_received_from_client' => "لايمكنك انهاء الطلب قبل تأكيد استلام الطلب من العميل",
          'offer_created_successfully'=>'تم إنشاء العرض بنجاح',
          'updated_successfully'=>'تم التعديل بنجاح',
          'editing_is_not_done_try_again'=>'التعديل لم يتم حاول مرة اخري',
          'offer_updated_successfully'=>'تم تعديل العرض بنجاح',
        'driver_finish_trip' => "تم انهاء الرحلة من قبل الكابتن",
        'driver_finish_order' => "تم انهاء الطلب من قبل الكابتن",
        'successfully_added_to_cart'=>'تم الاضافة للسلة بنجاح',
        'client_finish_trip' => "تم انهاء الرحلة من قبل العميل",
        'client_finish_order' => "تم انهاء الطلب من قبل العميل",
        'admin_finish_order' => "تم انهاء الرحلة من قبل الادارة",
        'driver_cancel_order' => "تم الغاء الرحلة من قبل الكابتن",
        'client_cancel_order' => "تم لغاء  الرحلة من قبل العميل",
        'admin_cancel_order' => "تم الغاء الرحلة من قبل الادارة",
        'order_canceled' => "تم الغاء الرحلة",
        'ur_wallet_less_than_budget_plus_addition' => "برجاء شحن المحفظة بمبلغ الرحلة إضافة إلى سعر التفاوض (:min_wallet_order_amount)",
        'wallet_is_soon' => "جاري العمل على الدفع من المحفظة رجاء اختيار طريقة أخرى للدفع حاليا",
        'cant_open_this_chat' => "لايمكن التفاعل على طلب منتهي او تم الغاؤة",
        'cant_found_order' => 'لم يتم العثور على الطلب المراد التفاعل علية ',
        'success_send_reply' => 'تم ارسال الرد للعميل',
        'cant_cancel_order_after_start_trip' => 'لايمكن الغاء طلب بعد بدء الرحلة',
        'cant_finish_order_before_start_trip' => 'لايمكن انهاء طلب قبل بدء الرحلة',
        'success_send_to_drivers' => 'تم عمل الطلب في انتظار تلقي العروض',
        'cant_make_offer_on_cancel_or_finished_orders' => 'لايمكن تقديم عرض على طلبات منتهية أو ملغية',
        'success_subscribe_renewal' => 'تم تجديد الاشتراك بنجاح',
        'success_subscribed' => 'تم الاشتراك بنجاح',
        'success_subscribed_and_start_after_current_finish' => 'تم الاشتراك بنجاح في الباقة الجديدة سوف يتم العمل بالباقة الجديدة بعد انتهاء الباقة الحالية',
        'expired_your_subscribtion' => 'رجاء تجديد اشتراكك لتتمكن من استقبال طلبات جديدة',
        'admin_not_accept_ur_data' => 'جاري مراجعة بياناتك من قبل الادارة وسوف يتم إبلاغكم حين الانتهاء شكرا لانتظاركم',
        'u_havnt_car' => 'رجاء استكمال بيانات السيارة لتتمكن من استقبال الطلبات',
        'not_subscribed_to_package' => 'رجاء الاشتراك في باقة لتلقي الطلبات',
        'refuse_ur_car_data' => 'لم تتم الموافقة على بياناتكم رجاء التواصل مع الادارة للاطلاع على سبب الرفض',
        'success_withdrawal' => 'تم توصيل طلبك للادارة وسوف يتم التواصل معك في حالة تحويل المبلغ',
        'success_charge' => 'تم شحن محفظتك بنجاح',
        'ur_wallet_lt_amount' => 'رصيدك الحالي غير كافي لعملية السحب',
        'client_select_another_driver' => 'قام العميل باختيار كابتن اخر',
        'u_r_on_default_package_with_subscribed_on_package_and_wallet_lt_default' => 'انت الان على نظام الرحلة الواحدة سعر الرحلة '. (setting('price_of_default_package_order') ?? 1 ) . 'ريال قم بتجديد الباقة لتستمتع بعدد غير محدود من الرحلات اليومية',
        'u_r_on_default_package_with_subscribed_on_package' => $home_msg,
        'u_r_on_default_package' => $home_msg,
        'reach_max_withdrawal_limit' => 'وصلت للحد الاقصي للسحب من التطبيق رجاء شحن المحفظة',
        'success_add_amount_to_ur_wallet' => 'تمت اضافة مبلغ :amount الى محفظتك',
        'please_charge_wallet_to_pay_off' => 'قم بسداد مديونية سلفني لكي تستفيد من الخدمة مرة أخرى!',
        'ur_wallet_balance_not_prmit_use_salfni' => 'رصيد محفظتك لايسمح لك باستخدام خدمة سلفني',
        'plz_charge_wallet_or_update_package' => 'رصيد محفظتك لايكفي لعمل رحلات الباقة الواحدة قم بشحن المحفظة او الاشتراك في احدى باقاتنا المتميزه',
        'no_live_order' => 'لاتوجد طلبات جارية الان',
        'ur_wallet_lt_package_price' => 'رصيد محفظتك غير كاف لاتمام عملية التجديد',
        'request_send_to_admin_will_reply_soon' => 'تم رفع الطلب للادارة وسيتم الرد قريبا شكرا لاستخدامكم تطبيق كيبرز',
        'new_transfer_transaction_title' => 'عمليةتحويل جديدة',
        'new_transfer_transaction_body' => 'قام :from بتحويل مبلغ :amount الى محفظتك',
        'u_have_oldest_request_wait_for_replying' => 'لديك طلب تجديد بالفعل قيد الانتظار سوف يتم الرد على الطلب قريبا',
        'success_transfer_from_ur_wallet_to_another' => 'تم تحويل مبلغ :amount من محفظتك الى :another_user',
        'cant_transfer_to_me' => 'لايمكن تحويل الرصيد الى حسابي الشخصي',
        'client_start_trip_status_title' => 'بدء الرحلة',
        'client_start_trip_status_body' => 'قام :client بالموافقة على بدء الرحلة',
        'success_change_order_status' => 'تم تغيير حالة الطلب',
        'already_have_order' => 'لديك طلب بالفعل لايمكنك عمل طلب جديد ',
        'u_r_use_this_offer' => 'قمت باستخدام هذا العرض مسبقا',
        'ride' => [
            'cant_finish_order_before_start_trip' => 'لايمكن انهاء الطلب قبل استلامه',
            'cant_cancel_order_after_start_trip' => 'لايمكن الغاء طلب بعد استلام الطلب',
            'wait_for_accept_client_start' => 'بانتظار قبول بدء الرحله من العميل',
        ],
        'delivery' => [
            'cant_finish_order_before_start_trip' => 'لايمكن انهاء طلب قبل بدء الرحلة',
            'cant_cancel_order_after_start_trip' => 'لايمكن الغاء طلب بعد بدء الرحلة',
        ],
    ],
    'fcm' => [

    ],
    'package' => [
        'months' => [
            'one' => 'شهر',
            'two' => 'شهرين',
            'more' => 'أشهر',
        ],
        'package_name' => ":price :currency :duration  :free :commission",
        'free' => ' مجانا',
        'commission' => '(:commission ريال / رحلة)'
    ],
    'car' => [
        'plate_types' => [
            'taxi' => 'تاكسي',
            'private' => 'خاص',
        ]
    ]
];
