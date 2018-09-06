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

Route::post('postcodes', 'Camps\PostController@getPostCodes');
Route::post('camps', 'Camps\CampController@getLocationCamps');
Route::post('children', 'Camps\CampController@getChildren');
Route::post('details', 'Camps\CampController@getCampDetails');
Route::post('register-parent', 'Camps\CampController@saveParentDetails');
Route::post('register-child', 'Camps\CampController@saveChildDetails');
Route::post('deactive-child', 'Camps\CampController@deactiveChild');
Route::post('check-gift-card', 'Camps\CampController@checkGiftCard');
Route::post('stripe-publish-key', 'Camps\PaymentController@stripePublishKey');
Route::post('stripe-create-charge', 'Camps\PaymentController@stripeCreateCharge');

Route::post('subscribe',['as'=>'subscribe','uses'=>'Camps\MailChimpController@subscribe']);
Route::post('add-to-drip',['as'=>'add-to-drip','uses'=>'Camps\DripController@addUserToDrip']);
Route::post('update-from-drip',['as'=>'update-from-drip','uses'=>'Camps\DripController@updateUserFromDrip']);
Route::post('remove-from-drip',['as'=>'remove-from-drip','uses'=>'Camps\DripController@removeUserFromDrip']);