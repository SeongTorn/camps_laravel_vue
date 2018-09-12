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
    $from_addr = env('MAIL_FROM_ADDRESS', '');
    $from_name = env('MAIL_FROM_NAME', '');

    if ($request->get('to_email') != null) {
      $data = array (
        'content' => $request->get('message'),
        'from' => $from_addr,
        'from_name' => $from_name,
        'to' => $request->get('to_email'),
        'subject' => 'CodeSpaceCamp'
      );
    } else {
      $data [] = '';
    }
    // Mail::to('houn.sockram@hotmail.com')->send(new SendMail($data));
    Mail::send ('emails.reset', $data, function($message) use ($data) {
      $message->from($data['from'], $data['from_name']);
      $message->to($data['to'])->subject($data['subject']);
    });
    /*Mail::send('emails.reset', $data, function($message) use ($data) {
      $message->to("houn.sockram@hotmail.com");
      $message->subject('Sendgrid Testing');
    });*/
    return response()->json(['success' => true]);
  }
}
