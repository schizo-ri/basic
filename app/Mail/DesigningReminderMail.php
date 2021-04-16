<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Designing;

class DesigningReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $designing;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Designing $designing, $missing_doc)
    {
        $this->designing = $designing;
        $this->missing_doc = $missing_doc;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']  . '/designings';
        /* $link = 'http://localhost:8000/designings'; */
       
        return $this->markdown('email.designing.reminder') 
        ->subject( 'Projektiranje - podsjetnik' )
        ->with([
            'designing' => $this->designing,
            'link' => $link,
            'missing_doc' =>  $this->missing_doc
        ]);
        
    }
}
