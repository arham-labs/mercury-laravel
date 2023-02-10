<?php

namespace Arhamlabs\NotificationHandler\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationHandlerMail extends Mailable
{
    use Queueable, SerializesModels;

    protected array $mailObject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $mailObject)
    {
        $this->mailObject = $mailObject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = config('mail.from.address'), $name = config('mail.from.name'))
            ->subject($this->mailObject['subject'])
            ->view($this->mailObject['view'])
            ->with([
                'data' => $this->mailObject,
            ]);
    }
}
