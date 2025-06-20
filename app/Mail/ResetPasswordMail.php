<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $token;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, String $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Reset Password")
            ->from(config('mail.from.address'), config('app.name'))
            ->view('email.resetPassword', [
                "user" => $this->user,
                "token" => $this->token
            ]);
    }
}
