<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Ad;

class AdCreateMail extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * The campaign instance.
     *
     * @var Campaign
     */
    public $ad;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ad $ad)
    {
        $this->ad = $ad;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(isset( $_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] != 'localhost') {
            $host =  $_SERVER['HTTP_HOST'];
        } else {
            $host = 'localhost:8000';
        }

        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $host  . '/oglasnik';

        return $this->markdown('emails.ads.create')
                    ->subject('MyIntranet oglasnik - objavljen je novi oglas')
					->with([
						'ad' =>  $this->ad,
						'url' =>  $url
					]);
    }
}
