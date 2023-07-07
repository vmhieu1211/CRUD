<?php

namespace App\Observers;

use App\Events\SendMail;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Http\Request;

class MailObserver
{

    public function handleUserCreated(SendMail $event)
    {
        $user = $event->user;
        Mail::to($user->email)->send(new WelcomeMail($user));
    }
    public function createUser(Request $request)
{
    $user = User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
    ]);

    event(new SendMail($user));


}
}
