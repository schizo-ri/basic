<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Absence; 
use App\Models\Employee; 
use Sentinel;

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
        $odobrio_user = Sentinel::getUser();
		$odobrio = $odobrio_user->first_name . ' ' . $odobrio_user->last_name;

        $employee = Employee::find($this->absence->employee_id);
        
        if( $this->absence->approve == '1'){
            $odobrenje = __('absence.is_approved');
        } else {
            $odobrenje = __('absence.not_approved');
        }

        return $this->view('Centaur::email.absence_confirm')
                    ->subject( __('absence.approve_absence') . ' - ' . $this->absence->employee->user['first_name']   . '_' . $this->absence->employee->user['last_name'])
                    ->with([
                        'absence' => $this->absence,
                        'odobrenje' => $odobrenje,
                        'odobrio' => $odobrio,
                    ]);
    }
}
