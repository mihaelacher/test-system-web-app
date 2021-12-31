<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /** @var string $creator */
    public $creator;
    /** @var Carbon $fromTime */
    public $fromTime;
    /** @var Carbon $toTime */
    public $toTime;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $creator, Carbon $fromTime, Carbon $toTime)
    {
        $this->creator = $creator;
        $this->fromTime = $fromTime;
        $this->toTime = $toTime;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): TestInvitation
    {
        return $this->from('testinvitation@no-reply.com')->view('emails.test-invitation');
    }
}
