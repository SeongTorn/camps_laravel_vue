<?php

namespace App\Http\Controllers\Camps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Newsletter;

class MailChimpController extends Controller
{
  public function subscribe(Request $request)
  {
    $this->validate($request, [
      'email' => 'required|email',
    ]);

    try {
      if (!Newsletter::isSubscribed('lord.vetinari@discworld.com')) {
        Newsletter::unsubscribe($request->get('email'));
      }
      return response()->json(['success' => true]);
    } catch (\Mailchimp_Error $e) {
      $error = Newsletter::getLastError();
      return response()->json(['success' => false, 'error' => $error]);
    }
  }

  public function sendCompaign(Request $request)
  {

  }
}
