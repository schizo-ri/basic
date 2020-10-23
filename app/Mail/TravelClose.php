<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\TravelOrder; 

class TravelClose extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * The travel instance.
     *
     * @var vacationRequest
     */
    public $travel;
    
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
        return $this->markdown('emails.travel.close')
                    ->subject(__('basic.close_travel') . ' - ' . $this->travel->employee->user['first_name'] . ' ' . $this->travel->employee->user['last_name'] )
                    ->attach('travelOrder/Putni nalog_' . $this->travel->id .'.pdf')
                    ->with([
                        'travel' =>  $this->travel
                    ]);
    }
}
