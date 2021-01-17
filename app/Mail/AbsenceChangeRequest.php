<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Absence; 
use App\Http\Controllers\BasicAbsenceController;
use App\Models\MailTemplate;

class AbsenceChangeRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The absence instance.
     *
     * @var absence
     */
    public $absence;
    public $new_request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Absence $absence, $new_request)
    {
        $this->absence = $absence;
        $this->new_request = $new_request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','AbsenceMail')->first();
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

        $employee = $this->absence->employee;
        $zahtjev = array('start_date' => $this->absence['start_date'], 'end_date' => $this->absence['end_date']);
        $dani_zahtjev = BasicAbsenceController::daniGO_count($zahtjev);
        $zahtjevi = BasicAbsenceController::zahtjevi($employee);
        $slobodni_dani = BasicAbsenceController::days_off($employee);

        $neiskoristeno_GO = $zahtjevi['ukupnoPreostalo']; //vraća neiskorištene dane 
    
        $subject = 'Zahtjev za promjenom odobrenog izostanka - ';
        $view = 'Centaur::email.absence_change';
        
        return $this->view($view) 
                    ->subject( $subject . ' ' . addslashes( $this->absence->absence->name ) . ' - ' . $this->absence->employee->user['first_name']   . '_' . $this->absence->employee->user['last_name'])
                    ->with([
                        'absence'      => $this->absence,
                        'new_request'  => $this->new_request,
                        'dani_zahtjev' => $dani_zahtjev,
                        'slobodni_dani' => $slobodni_dani,
                        'neiskoristeno_GO' => $neiskoristeno_GO,
                        'template_mail' => $mail_template,
                        'mail_style' => $mail_style,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}
