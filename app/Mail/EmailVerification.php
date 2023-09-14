<?php

namespace App\Mail;

use App\Models\UserDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Crypt;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;
    protected $user_id;
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    public function build()
    {
        $user = UserDetail::where('id', $this->user_id)
            ->select('name')
            ->first();
        $url = route('users.emailVerification').'?data='.Crypt::encrypt($this->user_id);
        return $this->subject('Verifikasi Email')
                    ->from(env('MAIL_USERNAME'), 'E-Meeting')
                    ->markdown('emails.verification', ['url' => $url, 'name' => $user->name]);
    }
}
