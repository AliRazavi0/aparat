<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConfirmationCodeMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var string
     */
    private $code;
    /**
     * @var string
     */
    private $email;

    /**
     * Create a new message instance.
     *
     * @param string $code
     * @param string $email
     */
    public function __construct(string $code, string $email)
    {
        $this->code = $code;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->email)
            ->view('emails.confirmation-code')
            ->with('code', $this->code);
    }
}
