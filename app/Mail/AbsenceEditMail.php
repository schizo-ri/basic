<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Absence; 
use App\Models\AbsenceType; 
use App\Http\Controllers\BasicAbsenceController;

class AbsenceEditMail extends Mailable
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
    public function __construct(Absence $absence, $request)
    {
        $this->absence = $absence;
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $employee = $this->absence->employee;
        $zahtjev = array('start_date' => $this->request['start_date'], 'end_date' => $this->request['end_date']);
       
        $dani_zahtjev = BasicAbsenceController::daniGO($zahtjev);
        
        $zahtjevi = BasicAbsenceController::zahtjevi($employee);
        $slobodni_dani = new BasicAbsenceController();
        $neiskoristeno_GO = $zahtjevi['ukupnoPreostalo']; //vraća neiskorištene dane 
        $type = AbsenceType::where('mark',$this->request['type'] )->first()->name;
        $subject =__('emailing.edit_absence') . ' ' . $this->absence->absence->name . ' - ' . $this->absence->employee->user['first_name']   . '_' . $this->absence->employee->user['last_name'];
        $view = 'emails.absences.edit';

        return $this->view($view)
                    ->subject( $subject )
                    ->with([
                        'absence' => $this->absence,
                        'request' => $this->request,
                        'type' => $type,
                        'dani_zahtjev' => $dani_zahtjev,
                        'neiskoristeno_GO' => $neiskoristeno_GO,
                    ]);
    }
}
