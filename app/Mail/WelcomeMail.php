<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;
    private $user;
    /**
     * Create a new message instance.
     
     *
     * @return void
     * */
    public function __construct(User $user)
    {
        $this->user= $user;
    }

    public function build()
    {
        return $this
            ->subject('Welcome to our website')
            ->view('mail.welcome')
            ->with([
                'user' => $this->user
            ]);
    }
}
