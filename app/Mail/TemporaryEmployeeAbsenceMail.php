<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\TemporaryEmployeeRequest;
use App\Http\Controllers\BasicAbsenceController;
use App\Models\MailTemplate;

class TemporaryEmployeeAbsenceMail extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * The temporaryEmployeeRequest instance.
     *
     * @var vacationRequest
     */
    public $temporaryEmployeeRequest;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TemporaryEmployeeRequest $temporaryEmployeeRequest)
    {
        $this->temporaryEmployeeRequest = $temporaryEmployeeRequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','TemporaryEmployeeAbsenceMail')->first();
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
        
        $zahtjev = array('start_date' => $this->temporaryEmployeeRequest['start_date'], 'end_date' => $this->temporaryEmployeeRequest['end_date']);
        $dani_zahtjev = BasicAbsenceController::daniGO_count($zahtjev);

        return $this->view('Centaur::email.temporaryEmployeeRequest')
                    ->subject( __('emailing.new_absence'))
                    ->with([
                        'temporaryEmployeeRequest' => $this->temporaryEmployeeRequest,
                        'dani_zahtjev' => $dani_zahtjev,
                        'template_mail' => $mail_template,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}
