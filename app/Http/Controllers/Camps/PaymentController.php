<?php

namespace App\Http\Controllers\Camps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;

class PaymentController extends Controller
{
  public function stripePublishKey(Request $request)
  {
    $key = env('STRIPE_PUBLISH_KEY', '');
    return response()->json(['success' => true, 'key' => $key]);
  }

  public function stripeSecretKey()
  {
    $key = env('STRIPE_SECRET_KEY', '');
    return response()->json(['success' => true, 'key' => $key]);
  }

  public function stripeCreateCharge(Request $request)
  {
    $token_id = $request->get('stripeToken');
    $token_email = $request->get('stripeEmail');
    $amount = $request->get('amount');
    $sec_key = env('STRIPE_SECRET_KEY', '');

    try {
      Stripe::setApiKey($sec_key);
      $customer = Customer::create(array(
        'email' => $token_email,
        'source' => $token_id
      ));
      $charge = Charge::create ( array (
              "customer" => $customer->id,
              "amount" => $amount,
              "currency" => "usd",
              "description" => "Camps payment."
      ));
      return response()->json(['success' => true]);
    } catch ( \Exception $e ) {
      return response()->json(['success' => false]);
    }
  }
}
