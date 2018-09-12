<?php

namespace App\Http\Controllers\Camps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Newsletter;

class MailChimpController extends Controller
{
  public $list_names;

  public function __construct()
  {
    $this->list_names = array("subscribers", "remarketing", "onboarding");
  }

  public function subscribe(Request $request)
  {
    $this->validate($request, [
      'email' => 'required|email',
    ]);

    if ($request->get('list')) {
      $list = $this->list_names[$request->get('list')];
    } else {
      $list = $this->list_names[0];
    }

    try {
      if (!Newsletter::isSubscribed($request->get('email'), $list)) {
        Newsletter::subscribe($request->get('email'), [], $list);
      }
      return response()->json(['success' => true]);
    } catch (\Mailchimp_Error $e) {
      $error = Newsletter::getLastError();
      return response()->json(['success' => false, 'error' => $error]);
    }
  }

  public function updateSubscribe(Request $request)
  {
    $this->validate($request, [
      'email' => 'required|email',
    ]);

    if ($request->get('list')) {
      $list = $this->list_names[$request->get('list')];
    } else {
      $list = $this->list_names[0];
    }

    try {
      if (Newsletter::isSubscribed($request->get('email'), $list)) {
        Newsletter::updateEmailAddress($request->get('email'), $request->get('new_email'));
      }
      return response()->json(['success' => true]);
    } catch (\Mailchimp_Error $e) {
      $error = Newsletter::getLastError();
      return response()->json(['success' => false, 'error' => $error]);
    }
  }

  public function unsubscribe(Request $request)
  {
    $this->validate($request, [
      'email' => 'required|email',
    ]);

    if ($request->get('list')) {
      $list = $this->list_names[$request->get('list')];
    } else {
      $list = $this->list_names[0];
    }

    try {
      if (Newsletter::isSubscribed($request->get('email'), $list)) {
        Newsletter::unsubscribe($request->get('email'), $list);
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
