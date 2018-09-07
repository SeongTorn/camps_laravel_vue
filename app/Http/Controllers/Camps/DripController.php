<?php

namespace App\Http\Controllers\Camps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Drip;

class DripController extends Controller
{
  public function addUserToDrip(Request $request)
  {
    $this->validate($request, [
      'email' => 'required|email',
    ]);
    try {
      $subscriber = Drip::fetchSubscriber(['email' => $request->get('email')]);
      if ($subscriber) {
        return response()->json(['success' => true, 'msg'=>'already subscribed']);
      }
      Drip::createOrUpdateSubscriber(
        ['email' => $request->get('email')]
      );
      return response()->json(['success' => true]);
    } catch (Exception $e) {
      return response()->json(['success' => false, 'error' => $e]);
    }
  }

  public function updateUserFromDrip(Request $request)
  {
    $this->validate($request, [
      'email' => 'required|email',
      'new_email' => 'required|email',
    ]);
    try {
      $response = Drip::createOrUpdateSubscriber(
        ['email' => $request->get('email'), 'new_email' => $request->get('new_email')]
      );
      return response()->json(['success' => true, 'response' => $response]);
    } catch (\Mailchimp_Error $e) {
      return response()->json(['success' => false, 'error_msg' => 'Error from Drip']);
    }
  }

  public function removeUserFromDrip(Request $request)
  {
    $this->validate($request, [
      'email' => 'required|email',
    ]);
    try {
      $response = Drip::unsubscribeSubscriber(
        ['email' => $request->get('email')]
      );
      return response()->json(['success' => true, 'response' => $response]);
    } catch (\Mailchimp_Error $e) {
      return response()->json(['success' => false, 'error_msg' => 'Error from Drip']);
    }
  }
}
