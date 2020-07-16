<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\TravelOrder; 

class TravelCreate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TravelOrder $travel)
    {
        $this->travel = $travel;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.travel.create')
                    ->subject(__('basic.create_travel1') . ' ' . $this->travel->employee->user['last_name'] )
                    ->with([
                        'travel' =>  $this->travel
                    ]);;
    }
}
