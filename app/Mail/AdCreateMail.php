<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Ad;
use App\Models\MailTemplate;

class AdCreateMail extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * The ad instance.
     *
     * @var Ad
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
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','AdCreateMail')->first();
        
        if(isset( $_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] != 'localhost') {
            $host =  $_SERVER['HTTP_HOST'];
        } else {
            $host = 'localhost:8000';
        }

        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $host  . '/oglasnik';

        return $this->view('emails.ads.create')
                    ->subject('MyIntranet oglasnik - objavljen je novi oglas')
					->with([
						'ad' =>  $this->ad,
						'url' =>  $url,
                        'template_mail' => $mail_template
					]);
    }
}
