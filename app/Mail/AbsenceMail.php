<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Absence; 
use App\Models\Employee; 
use App\Http\Controllers\BasicAbsenceController;

class AbsenceMail extends Mailable
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
        $employee = Employee::where('id',$this->absence->employee_id)->first();
        $zahtjev = array('start_date' => $this->absence['start_date'], 'end_date' => $this->absence['end_date']);
        $dani_zahtjev = BasicAbsenceController::daniGO($zahtjev);
        $zahtjevi = BasicAbsenceController::zahtjevi($employee);
        $neiskoristeno_GO = $zahtjevi['preostalo_PG'] + $zahtjevi['preostalo_OG']; //vraća neiskorištene dane 
        
        return $this->from('info@duplico.hr', 'Duplico')
                    ->view('Centaur::email.absence')
                    ->subject( __('emailing.new_absence') . ' - ' . $this->absence->employee->user['first_name']   . '_' . $this->absence->employee->user['last_name'])
                    ->with([
                        'absence' => $this->absence,
                        'dani_zahtjev' => $dani_zahtjev,
                        'neiskoristeno_GO' => $neiskoristeno_GO
                    ]);
    }
}
