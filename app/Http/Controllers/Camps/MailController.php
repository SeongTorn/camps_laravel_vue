<?php

namespace App\Http\Controllers\Camps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
class MailController extends Controller
{
  public function sendMail(Request $request)
  {
    if ($request->get('message') != null) {
      $data = array (
        'message' => 'test mail'
      );
    } else {
      $data [] = '';
    }
    // Mail::to('houn.sockram@hotmail.com')->send(new SendMail($data));
    /*Mail::send ('emails.reset', $data, function($message) use($request) {
      $message->from('seongtorn@gmail.com', 'Just Laravel');
      $message->to('houn.sockram@hotmail.com')->subject ('Just Laravel demo email using SendGrid');
    });*/
    Mail::send('emails.reset', $data, function($message) use ($data) {
      $message->to("houn.sockram@hotmail.com");
      $message->subject('Sendgrid Testing');
    });
    return response()->json(['success' => true]);
  }
}
