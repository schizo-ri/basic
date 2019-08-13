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
        $odobrio_user = Sentinel::getUser()->employee;
		$odobrio = $odobrio_user->user['first_name'] . ' ' . $odobrio_user->user['last_name'] ;

		$employee = Employee::where('id',  $this->absence->employee_id)->first();
		$ime = $employee->first_name . ' ' . $employee->last_name;
        
        if( $this->absence->approve == '1'){
            $odobrenje = __('absence.is_approved');
        } else {
            $odobrenje = __('absence.not_approved');
        }

        return $this->from('info@duplico.hr', 'Duplico')
                    ->view('Centaur::email.absence_confirm')
                    ->subject( __('absence.approve_absence') . ' - ' . $this->absence->employee->user['first_name']   . '_' . $this->absence->employee->user['last_name'])
                    ->with([
                        'absence' => $this->absence,
                        'odobrenje' => $odobrenje,
                        'odobrio' => $odobrio,
                    ]);
    }
}
