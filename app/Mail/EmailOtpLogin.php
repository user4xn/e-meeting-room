<?php

namespace App\Mail;

use App\Models\UserEmailOtp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class EmailOtpLogin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected $user_id)
    {
        
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Otp Login',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $otp = strval(rand(100000, 999999));
        $check_otp = UserEmailOtp::where('user_id', $this->user_id)
            ->first();
        if($check_otp){
            $check_otp->user_id = $this->user_id;
            $check_otp->otp = $otp;
            $check_otp->save();
        }else{
            $store_otp = new UserEmailOtp();
            $store_otp->user_id = $this->user_id;
            $store_otp->otp = $otp;
            $store_otp->save();
        }
        return new Content(
            view: 'emails.otp_login',
            with: [
                'otp' => $otp,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
