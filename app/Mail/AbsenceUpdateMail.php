<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Absence; 
use App\Models\Employee; 
use App\Models\Template; 
use App\Http\Controllers\BasicAbsenceController;
use App\Models\MailTemplate;

class AbsenceUpdateMail extends Mailable
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
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','AbsenceUpdateMail')->first();
        
        $employee = Employee::find($this->absence->employee_id);
        $zahtjev = array('start_date' => $this->absence['start_date'], 'end_date' => $this->absence['end_date']);
        $dani_zahtjev = BasicAbsenceController::daniGO($zahtjev);
        $zahtjevi = BasicAbsenceController::zahtjevi($employee);
        $neiskoristeno_GO = $zahtjevi['ukupnoPreostalo']; //vraća neiskorištene dane 
        $template = Template::where('module','absence')->first();
        if($this->absence->decree == 1) {
            $view = 'Centaur::email.absence_decree_update';
            $subject = 'Odluka uprave o izmjeni zahtjeva - ';
        } else {
            $view = 'Centaur::email.absence';
            $subject =__('emailing.edit_absence');
        }
  
        return $this->view($view) 
                    ->subject( $subject . ' ' . $this->absence->absence['name'] . ' - ' . $this->absence->employee->user['first_name']   . '_' . $this->absence->employee->user['last_name'])
                    ->with([
                        'template' => $template,
                        'absence' => $this->absence,
                        'dani_zahtjev' => $dani_zahtjev,
                        'neiskoristeno_GO' => $neiskoristeno_GO,
                        'template_mail' => $mail_template
                    ]);
    }
}
