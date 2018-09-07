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
Route::post('camps', 'Camps\CampsController@getLocationCamps');
Route::post('children', 'Camps\CampsController@getChildren');
Route::post('parent', 'Camps\CampsController@getParent');
Route::post('details', 'Camps\CampsController@getCampDetails');
Route::post('register-parent', 'Camps\CampsController@saveParentDetails');
Route::post('register-child', 'Camps\CampsController@saveChildDetails');
Route::post('check-registered', 'Camps\CampsController@checkRegistered');
Route::post('deactive-child', 'Camps\CampsController@deactiveChild');
Route::post('check-gift-card', 'Camps\CampsController@checkGiftCard');
Route::post('check-payment', 'Camps\CampsController@checkPayment');
Route::post('save-payment', 'Camps\CampsController@savePayment');
Route::post('stripe-publish-key', 'Camps\PaymentController@stripePublishKey');
Route::post('stripe-create-charge', 'Camps\PaymentController@stripeCreateCharge');

Route::post('subscribe',['as'=>'subscribe','uses'=>'Camps\MailChimpController@subscribe']);
Route::post('unsubscribe',['as'=>'unsubscribe','uses'=>'Camps\MailChimpController@unsubscribe']);
Route::post('add-to-drip',['as'=>'add-to-drip','uses'=>'Camps\DripController@addUserToDrip']);
Route::post('update-from-drip',['as'=>'update-from-drip','uses'=>'Camps\DripController@updateUserFromDrip']);
Route::post('remove-from-drip',['as'=>'remove-from-drip','uses'=>'Camps\DripController@removeUserFromDrip']);
Route::post('send-mail',['as'=>'send-mail','uses'=>'Camps\MailController@sendMail']);