<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Absence; 
use App\Http\Controllers\BasicAbsenceController;
use App\Models\MailTemplate;

class AbsenceInfoMail extends Mailable
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
        $slobodni_dani = new BasicAbsenceController();
        $slobodni_dani =  $slobodni_dani->days_offUnused($employee->id);

        $neiskoristeno_GO = $zahtjevi['ukupnoPreostalo']; //vraća neiskorištene dane 
        if($this->absence->decree == 1) {
            $subject = 'Odluka uprave - ';
        } else {
            if($this->absence->absence['mark'] == "BOL") {
                $subject = __('emailing.sicknes_info');
            } else {
                $subject =__('emailing.new_absence') . ' ' . $this->absence->absence->name;
            }
        }
        $view = 'emails.absences.info';
        return $this->view($view) 
                    ->subject( $subject . ' - ' . $this->absence->employee->user['first_name']   . '_' . $this->absence->employee->user['last_name'])
                    ->with([
                        'absence' => $this->absence,
                        'dani_zahtjev' => $dani_zahtjev,
                        'slobodni_dani' => $slobodni_dani,
                        'neiskoristeno_GO' => $neiskoristeno_GO,
                        'mail_style' => $mail_style,
                        'template_mail' => $mail_template,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}