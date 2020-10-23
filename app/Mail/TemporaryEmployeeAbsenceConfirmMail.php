<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\TemporaryEmployeeRequest;
use Sentinel;

class TemporaryEmployeeAbsenceConfirmMail extends Mailable
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
        $odobrio_user = Sentinel::getUser();
		$odobrio = $odobrio_user->first_name . ' ' . $odobrio_user->last_name;
        
        if( $this->temporaryEmployeeRequest->approve == '1'){
            $odobrenje = __('absence.is_approved');
        } else {
            $odobrenje = __('absence.not_approved');
        }

        return $this->markdown('emails.temporary_employee_requests.confirm')
                    ->subject( __('absence.approve_absence') . ' - ' . $this->temporaryEmployeeRequest->employee->user['first_name']   . '_' . $this->temporaryEmployeeRequest->employee->user['last_name'])
                    ->with([
                        'absence' => $this->temporaryEmployeeRequest,
                        'odobrenje' => $odobrenje,
                        'odobrio' => $odobrio,
                    ]);

    }
}
