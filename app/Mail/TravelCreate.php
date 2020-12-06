<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\TravelOrder; 
use App\Models\MailTemplate;

class TravelCreate extends Mailable
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
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','TravelCreate')->first();
        
        return $this->view('emails.travel.create')
                    ->subject(__('basic.create_travel1') . ' - ' . $this->travel->employee->user['last_name'] )
                    ->with([
                        'travel' =>  $this->travel,
                        'template_mail' => $mail_template
                    ]);;
    }
}
