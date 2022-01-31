<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'as' => 'dashboard.',
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth' , 'admin']
    ],
    function () {

        Route::prefix('dashboard')->group(function () {
            // Home
            Route::get('/','HomeController@index')->name('home');
            // Route::get('import','HomeController@importDrivers');
            // Role
            Route::resource('role','RoleController');

            // Client
            Route::resource('client','ClientController');

             // Sitter
             Route::resource('sitter','SitterController');

              // feature
            Route::resource('feature','FeatureController');
            // Center
            Route::resource('center','CenterController');

            // sitter_worker
            Route::resource('sitter_worker','SitterWorkerController');

            // transfer requests
            Route::resource('transfer_request','TransferRequestController')->only('index','show');
            Route::get('transfer_request/accept/{id}','TransferRequestController@accept')->name('transfer_request.accept');
            Route::get('transfer_request/reject/{id}','TransferRequestController@reject')->name('transfer_request.reject');

            // Offer Requests
            Route::resource('offer_request','OfferRequestController')->only('index','show');
            Route::get('offer_request/accept/{id}','OfferRequestController@accept')->name('offer_request.accept');
            Route::get('offer_request/reject/{id}','OfferRequestController@reject')->name('offer_request.reject');

             // orders
             Route::resource('orders','OrderController')->only('index','show','destroy');
            //  Route::get('orders','OrderController@index')->name('orders.index');
            //  Route::get('orders/{id}','OrderController@index')->name('orders.show');

             // Financial Statistics
            Route::resource('financial_statistic','FinancialController');
            // ====================HR===========================================
            // Manager
            Route::resource('manager','ManagerController');

            // =====================Location====================================






             // Slider
            Route::resource('slider','SliderController');

           // Country
           Route::resource('country','CountryController');
           // City
           Route::resource('city','CityController');

            // Order
            Route::resource('orders','OrderController');

            // ======================Setting====================================
            // Notification
            Route::resource('notification','NotificationController')->only('index','show','store');
            // Setting
            Route::resource('setting','SettingController')->only('index','store');

            // Contact
            Route::resource('contact','ContactController')->only('index','show','store','destroy');
            Route::delete('reply/{reply_id}/delete','ContactController@deleteReply');

            // =============================Utilities=============================

            Route::get('search','HomeController@getSearch');

            Route::get('get_profile','ProfileController@create')->name('profile.get_profile');
            Route::post('update_profile','ProfileController@store')->name('profile.update_profile');
            Route::post('update_password','ProfileController@updatePassword')->name('profile.update_password');

            // ===========================AJAX==================================
            Route::prefix('ajax')->group(function () {

                Route::get('get_available_days_by_district/{district_id}','AjaxController@getAvailableDaysByDistrict');

                Route::post('get_elm_reply/{driver_id}','AjaxController@getElmReply');
                Route::post('register_driver_to_elm/{driver_id}','AjaxController@registerDriverToElm');

                Route::get('get_users_by_type/{user_type}','AjaxController@getUsersByType');

                Route::get('main_search','AjaxController@getSearch');
                Route::get('get_new_orders','AjaxController@getNewOrders');
                Route::get('get_current_orders','AjaxController@getCurrentOrders');
                Route::get('get_finished_orders','AjaxController@getFinishedOrders');

                Route::post('update_order_status/{order_id}','AjaxController@updateOrderStatus');
                // Delete Images
                Route::delete('delete_app_image/{image_id}','AjaxController@deleteAppImage');

                // Generate Code
                Route::get('generate_code/{char_length?}/{char_type?}/{model?}/{col?}/{letter_type?}','AjaxController@generateCode');
            });
        });
    });
