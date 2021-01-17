<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Car;
use App\Models\MailTemplate;

class CarsRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * The car instance.
     *
     * @var Cars
     */
    public $cars;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','CarsRegistrationMail')->first();
        $mail_style = array();
        $template_text_header = array();
        $template_text_body= array();
        $template_text_footer = array();

        if( $mail_template ) {
            $mail_style = $mail_template->mailStyle;
            $template_text_header = MailTemplate::textHeader( $mail_template );
            $template_text_body = MailTemplate::textBody( $mail_template );
            $template_text_footer = MailTemplate::textFooter( $mail_template );
        }

        if(isset( $_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] != 'localhost') {
            $host =  'https://duplico.myintranet.io';
        } else {
            $host = 'http://localhost:8000';
        }

        $url = $host  . '/store_event/'.$this->car->id;

        return $this->view('centaur.email.car_registrations')
                    ->subject( __('basic.vehicle_registration') . ' - ' . $this->car->registration)
                    ->with([
                        'car' => $this->car,
                        'url'=>  $url,
                        'template_mail' => $mail_template,
                        'mail_style' => $mail_style,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}
