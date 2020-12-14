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

        $odobrio_user = Sentinel::getUser();
		$odobrio = $odobrio_user->first_name . ' ' . $odobrio_user->last_name;

        $employee = Employee::find($this->absence->employee_id);
        
        $absence_name1 = explode(' ', $this->absence->absence->name);
        $absence_name = '';
        foreach ( $absence_name1 as $word ) {
            $absence_name .= $word . ' ';
           
        }

        if( $this->absence->approve == '1'){
            $odobrenje = __('absence.is_approved');
            $title = 'Zahtjev za '. ' ' .  $absence_name .' je odobren';
        } else {
            $odobrenje = __('absence.is_refused');
            $title = 'Zahtjev za '. ' ' . $absence_name .' je odbijen';
        }

        return $this->view('Centaur::email.absence_confirm')
                    ->subject( $title . ' - ' . $this->absence->employee->user['first_name']   . '_' . $this->absence->employee->user['last_name'])
                    ->with([
                        'absence' => $this->absence,
                        'odobrenje' => $odobrenje,
                        'odobrio' => $odobrio,
                        'template_mail' => $mail_template
                    ]);
    }
}
