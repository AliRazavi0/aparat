<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var string
     */
    private $code;

    /**
     * Create a new message instance.
     *
     * @param string $code
     */
    public function __construct(string $code)
    {
        //
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view('emails.verification-code')
            ->with('code', $this->code);
    }
}
