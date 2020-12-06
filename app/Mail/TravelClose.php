<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\TravelOrder; 
use App\Models\MailTemplate;

class TravelClose extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * The travel instance.
     *
     * @var travel
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
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','TravelClose')->first();
        
        return $this->view('emails.travel.close')
                    ->subject(__('basic.close_travel') . ' - ' . $this->travel->employee->user['first_name'] . ' ' . $this->travel->employee->user['last_name'] )
                    ->attach('travelOrder/Putni nalog_' . $this->travel->id .'.pdf')
                    ->with([
                        'travel' =>  $this->travel,
                        'template_mail' => $mail_template
                    ]);
    }
}
