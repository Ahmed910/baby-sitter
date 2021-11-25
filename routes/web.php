<?php

use Illuminate\Support\Facades\Route;

Route::group(
[
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
], function(){


		// Dashboard (Has Role)
		Route::get('dashboard/login', "Auth\LoginController@showLoginForm")->name("dashboard.login");
		Route::post('dashboard/login', "Auth\LoginController@login")->name("dashboard.post_login");


		// For All
		Route::get('activate/{confirmationCode}', 'Auth\LoginController@confirm')->name('confirmation_path');
		Route::post('setPassword', "Auth\LoginController@storePassword")->name('setPassword');
		Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('forget');
		Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('email');
		Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
		Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('resetToNew');

		Route::middleware('auth')->group(function () {
			Route::post('logout',"Auth\LoginController@logout")->name('logout');
		});
		Route::view('/',"dashboard.error.404_notauth")->name('site.home');
		Route::view('terms',"site.terms")->name('site.terms');

});
