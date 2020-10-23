<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Afterhour;

class AfterHourSendMail extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * The campaign instance.
     *
     * @var Campaign
     */
    public $afterhour;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( Afterhour $afterhour )
    {
        $this->afterhour = $afterhour;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.afterhours.send')
                    ->subject( __('basic.request'))
                    ->with([
                        'afterhour' =>  $this->afterhour,
                    ]);
    }
}
