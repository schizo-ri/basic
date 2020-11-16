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
        $employee = $this->absence->employee;
        $zahtjev = array('start_date' => $this->absence['start_date'], 'end_date' => $this->absence['end_date']);
        $dani_zahtjev = BasicAbsenceController::daniGO($zahtjev);
        $zahtjevi = BasicAbsenceController::zahtjevi($employee);
        $slobodni_dani = BasicAbsenceController::days_off($employee);

        $neiskoristeno_GO = $zahtjevi['ukupnoPreostalo']; //vraća neiskorištene dane 
        if($this->absence->decree == 1) {
            $view = 'Centaur::email.absence_decree';
            $subject = 'Odluka uprave - ';
        } else {
            if($this->absence->absence['mark'] == "BOL") {
                $subject = __('emailing.sickne_info');
            } else {
                $subject =__('emailing.new_absence');
            }
            $view = 'Centaur::email.absence';
        }
        
        return $this->markdown('emails.absences.today_absence')
                    ->subject( $subject . ' ' . $this->absence->absence['name'] . ' - ' . $this->absence->employee->user['first_name']   . '_' . $this->absence->employee->user['last_name'])
                    ->with([
                        'absence' => $this->absence,
                        'dani_zahtjev' => $dani_zahtjev,
                        'slobodni_dani' => $slobodni_dani,
                        'neiskoristeno_GO' => $neiskoristeno_GO
                    ]);
    }
}
