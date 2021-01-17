<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Absence; 
use App\Models\Employee; 
use Sentinel;
use App\Models\MailTemplate;

class AbsenceConfirmMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The emplyee instance.
     *
     * @var vacationRequest
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
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','AbsenceConfirmMail')->first();
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
        
        $odobrio_user = Sentinel::getUser();
		$odobrio = $odobrio_user->first_name . ' ' . $odobrio_user->last_name;

        $employee = Employee::find($this->absence->employee_id);
        
        if( $this->absence->approve == '1'){
            $odobrenje = __('absence.is_approved');
            $title = 'Zahtjev za '. ' ' .  addslashes($this->absence->absence->name) .' je odobren';
        } else {
            $odobrenje = __('absence.is_refused');
            $title = 'Zahtjev za '. ' ' . addslashes($this->absence->absence->name)  .' je odbijen';
        }

        return $this->view('Centaur::email.absence_confirm')
                    ->subject( $title . ' - ' . $this->absence->employee->user['first_name']   . '_' . $this->absence->employee->user['last_name'])
                    ->with([
                        'absence' => $this->absence,
                        'odobrenje' => $odobrenje,
                        'odobrio' => $odobrio,
                        'template_mail' => $mail_template,
                        'mail_style' => $mail_style,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}
