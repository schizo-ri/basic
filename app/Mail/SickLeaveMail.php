<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Absence; 
use App\Models\MailTemplate; 
use App\Http\Controllers\ApiController;
use Log;

class SickLeaveMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The absence instance.
     *
     * @var absence
     */
    public $absence;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Absence $absence)
    {
        $this->absence = $absence;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','SickLeaveMail')->first();
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

       /*  $api = new ApiController();
        $send_leave_request = $api->send_leave_request($this->absence, 'abs');
        if($send_leave_request == true) {
            $message_erp = ' Zahtjev ' . $this->absence->id . ' je uspješno zapisan u Odoo.';
        } else {
            $message_erp = ' Zahtjev  ' . $this->absence->id . ' NIJE zapisan u Odoo. Došlo je do neke greške';
        } */
        Log::info($message_erp);

        return $this->view('emails.absences.sick_today')
                    ->subject('Upis bolovanja u erp')
                    ->with([
                        'variable' => $variable,
                        'absence' => $this->absence,
                        'mail_style' => $mail_style,
                        'template_mail' => $mail_template,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}
