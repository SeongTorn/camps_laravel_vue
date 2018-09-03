<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'auth:api'], function () {
  Route::post('logout', 'Auth\LoginController@logout');

  Route::get('/user', function (Request $request) {
      return $request->user();
  });

  Route::patch('settings/profile', 'Settings\ProfileController@update');
  Route::patch('settings/password', 'Settings\PasswordController@update');
});

Route::group(['middleware' => 'guest:api'], function () {
  Route::post('login', 'Auth\LoginController@login');
  Route::post('register', 'Auth\RegisterController@register');
  Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('password/reset', 'Auth\ResetPasswordController@reset');

  Route::post('oauth/{driver}', 'Auth\OAuthController@redirectToProvider');
  Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');
});

Route::post('postcodes', 'Camp\PostController@getPostCodes');
Route::post('camps', 'Camp\CampController@getLocationCamps');
Route::post('children', 'Camp\CampController@getChildren');
Route::post('details', 'Camp\CampController@getCampDetails');
Route::post('register-parent', 'Camp\CampController@saveParentDetails');
Route::post('register-child', 'Camp\CampController@saveChildDetails');
Route::post('deactive-child', 'Camp\CampController@deactiveChild');
Route::post('check-gift-card', 'Camp\CampController@checkGiftCard');
Route::post('stripe-publish-key', 'Camp\PaymentController@stripePublishKey');
Route::post('stripe-create-charge', 'Camp\PaymentController@stripeCreateCharge');