<?php

use Illuminate\Support\Facades\Route;
// use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::namespace('Api')->middleware('setLocale')->group(function(){
    Route::namespace('User')->group(function(){



        Route::post('register', 'AuthController@signup');

        Route::post('login', 'AuthController@login');

        Route::post('verify', 'AuthController@confirm');

        Route::post('send_code','AuthController@sendCode');

        Route::post('check_code', "AuthController@checkCode");

        Route::post('reset_password', "AuthController@resetPassword");
        Route::post('update_location', "UserController@updateUserLocation");

        Route::post('check_for_phone_and_email','AuthController@checkForPhoneAndEmail');

        Route::group(['middleware' => 'auth:api'], function () {
            // Logout
            Route::post('logout', 'AuthController@logout');
            // Profile
            Route::get('profile', 'UserController@index');
            Route::post('profile', 'UserController@store');
            Route::post('edit_password', 'UserController@editPassword');

                       // Chat
            Route::get('chat/{order_id}/{receiver_id}', 'ChatController@show');
            Route::apiResource('chat', 'ChatController')->only('index', 'store', 'destroy');
            Route::put('chat/{chat_id}/message_is_seen', 'ChatController@messageIsSeen');
            // Notification
            Route::apiResource('notifications','NotificationController')->only('index','show','destroy');

            // Wallet
            Route::apiResource('wallet_transfers','WalletTransfersController')->only('index','show','store');
            Route::get('wallet','WalletController@index');
            Route::get('my_ibans','WalletController@getIbans');
            Route::post('charge_wallet','WalletController@chargeWallet');
            Route::post('withdrawal_wallet','WalletController@withdrawalWallet');
        });
    });
    // Client
    Route::namespace('Client')->prefix('client')->group(function(){
        Route::middleware(['auth:api','client_middleware'])->group(function(){
            // Orders
            // Route::apiResource('orders','OrderController')->only('index','show','store');
            // Route::get('get_orders','OrderController@getOrders');
            // Route::post('change_order_status','OrderController@changeOrderStatus');
            // Route::post('received_orders','OrderController@ClientRecieveOrder');
            // Offers
            Route::get('offers/{order_id}','OfferController@offers');
            Route::get('offers/{order_id}/{offer_id}','OfferController@showOffer');
            Route::post('offers','OfferController@acceptOffer');

            // favorites
            Route::post('toggle_favorites/{user_id}','FavoriteController@toggleFavorites');

            Route::get('favorites','FavoriteController@getFavorites');

            Route::get('delete_user_from_favorites/{fav_id}','FavoriteController@deleteUserFromFavorites');

            //Order
            Route::post('create_order_for_sitter','OrderController@createOrderForSitter');
            Route::post('create_order_for_center','OrderController@createOrderForCenter');
            Route::get('get_orders','OrderController@getOrders');
            Route::get('get_sitter_order_details/{order_id}','OrderController@getSitterOrderDetails');
            Route::get('get_center_order_details/{order_id}','OrderController@getCenterOrderDetails');
            Route::get('cancel_order/{order_id}','OrderController@cancelOrder');
            // Kids
            Route::apiResource('kid','KidController');
            //Store Categories

            // Neareast Drivers
            Route::get('nearest_drivers/{number_of_drivers?}','LocationController@nearestDrivers');
            // Rate && Review
            Route::post('rates','OrderController@SetRate');
            // Route::get('rates/{consultant_id}','ConsultantController@getReviews');
        });
        Route::apiResource('store_categories','StoreCategoryController')->only('index','show');
        Route::apiResource('product_categories','ProductCategoryController')->only('index','show');
    });

    Route::namespace('BabySitter')->prefix('baby_sitter')->group(function(){
        Route::middleware(['auth:api','baby_sitter_middleware'])->group(function(){
            // Offers
            Route::apiResource('offer','OfferController');
            // Schedules
            Route::apiResource('schedule','ScheduleController');
            // Rate && Review
            // Route::post('rates','OrderController@SetRate');
            // Orders
            Route::get('get_orders','OrderController@getOrders');
            Route::get('get_order_details/{order_id}','OrderController@getOrderDetails');
            Route::get('accept_order/{order_id}','OrderController@acceptOrder');
            Route::get('reject_order/{order_id}','OrderController@rejectOrder');
            Route::get('cancel_order/{order_id}','OrderController@cancelOrder');
            Route::post('check_otp_validity','OrderController@checkOtpValidity');
            Route::get('send_otp/{order_id}','OrderController@sendOTP');
            Route::get('deliver_childern/{order_id}','OrderController@deliverChildern');
            Route::get('get_customer_profile/{customer_id}','OrderController@getOrderDetails')->name('sitter_order.customer_profile');

            // Route::get('rates/{consultant_id}','ConsultantController@getReviews');
            // get main profile
            Route::apiResource('gallery','GalleryController')->except('show','update','index');
            Route::post('edit_features','FeatureController@editFeaturesForSitter');
        });

    });
    // Child Center
    Route::namespace('ChildCenter')->prefix('child_center')->group(function(){
        Route::middleware(['auth:api','child_center_middleware'])->group(function(){
            // Offers
            Route::apiResource('offer','OfferController');

            //
            Route::post('reject_orders','OfferController@rejectOrder');
            Route::post('change_order_status','OrderController@changeOrderStatus');
            Route::post('change_account_status','DriverController@changeAccountStatus');

            Route::apiResource('gallery','GalleryController')->except('show','update');
            // Update Driver Location
            Route::post('edit_features','FeatureController@editFeaturesForCenter');
            Route::post('update_location','LocationController@updateLocation');
            // Schedules
            Route::apiResource('schedule','ScheduleController');
            //BabySitter
            Route::apiResource('baby_sitter','BabySitterController');
            // Package
            Route::apiResource('packages','PackageController')->only('store','index');

            Route::post('renew_subscription_from_wallet','PackageController@renewSubscribtionFromWallet');
            Route::get('driver_car','CarController@getCarData');
            Route::post('update_driver_car','CarController@updateDriver');
            Route::post('toggle_is_available','DriverController@toggleAvailable');
            Route::post('extend_packages','PackageController@extendPackage');
            Route::get('check_subscribtions','PackageController@checkSubscribtion');
            // Rate && Review
            Route::post('rates','ConsultantController@SetRate');

        });
        Route::get('min_manufacture_years','CarController@getMinManufactureYears');
        Route::get('plate_types','CarController@getPlateTypes');
    });
    Route::namespace('Help')->group(function(){
        // Country
        Route::get('countries', "CountryController@index");
        // Cancel Reasons
        Route::get('cancel_reasons', "HelpController@getCancelReasons")->middleware('auth:api');

        // City
        Route::get('cities', "CountryController@show");
        // About
        Route::get('about', 'HomeController@getAbout');
        // Policy
        Route::get('policy', 'HomeController@getPolicy');
        // Terms
        Route::get('terms', 'HomeController@getTerms');
        // Services
        Route::get('get_services','HomeController@getServices');
        // Features
        Route::get('get_features','HomeController@getFeatures');
        // Days
        Route::get('get_days','HomeController@getDays');

        // get faqs
        Route::get('get_faqs','FaqController@getFaqs');
        // Tax
        Route::get('tax','HomeController@getTax')->middleware('auth:api');
        // Contact
        Route::get('contact', 'HomeController@getContact');
        // Contact Us & Complaints   // ->middleware('auth:api')
        Route::post('contact', 'HomeController@contact');

        Route::get('get_all_offers','NewHomeController@getAllOffers');
        Route::get('get_sitters','NewHomeController@getSitters');

        Route::get('get_sitter_details/{sitter_id}','NewHomeController@getSitterDetails');

        Route::get('get_center_details/{center_id}','NewHomeController@getCenterDetails');

        Route::get('get_centers','NewHomeController@getCenters');

        Route::get('get_nearest_centers','NewHomeController@getNearestCenters');

        // Slider
        Route::get('sliders','SliderController@index');




        // Delete Images
        Route::delete('delete_app_image/{image_id}','HomeController@deleteAppImage')->middleware("auth:api");

        // Search
        Route::get('search', 'HomeController@search');


        // Slider
        Route::get('sliders','SliderController@index');


    });
});
