<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Designing;

class NewDesigningMail extends Mailable
{
    use Queueable, SerializesModels;

    public $designing;

    /**
     * Create a new message instance.
     *
     * @return void
     */
      public function __construct(Designing $designing)
    {
        $this->designing = $designing;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']  . '/designings';

        return $this->markdown('email.designing.open_designing')
                    ->subject( 'Dodjeljen projektant na projekt ' . $this->designing->project_no )
                    ->with([
                        'designing' => $this->designing,
                        'link' => $link
                    ]);
    }
}
