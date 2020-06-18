<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Absence;
use App\Models\Emailing;
use App\Models\Department;
use App\Models\Employee;
use DateTime;
use DateInterval;
use DatePeriod;
use App\Http\Controllers\BasicAbsenceController;

class AbsenceCronMail extends Mailable
{
    use Queueable, SerializesModels;

  
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
         
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $datum = new DateTime('now');
		date_modify($datum, '+1day');
		$dan = date_format($datum,'d');
		$mjesec = date_format($datum,'m');
        $ova_godina = date_format($datum,'Y');
        
        $day_absences = array();
        $absences = Absence::where('approve',1)->get();
        foreach($absences as $absence){			
            $begin = new DateTime($absence->start_date);
            $end = new DateTime($absence->end_date);
            $end->setTime(0,0,1);
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            
            $zahtjevi = BasicAbsenceController::zahtjevi($absence->employee); 

            $dani_GO = $zahtjevi['ukupnoPreostalo'];

            foreach ($period as $dan) {  //ako je dan  GO !!!
                $period_day = date_format($dan,'d');
                $period_month = date_format($dan,'m');
                $period_year = date_format($dan,'Y');
                if($period_day == $dan & $period_month == $mjesec & $period_year == $ova_godina ){
                    array_push($day_absences, array(
                        'ime' => $absence->user['first_name'] . ' ' . $absence->user['last_name'], 
                        'zahtjev' =>  $absence->absence['name'], 
                        'period' => date('d.m.Y', strtotime($absence->start_date)) . ' - ' .  date('d.m.Y', strtotime($absence->end_date)), 
                        'vrijeme' => $absence->start_time . ' - ' .  $absence->end_time, 
                        'napomena' =>  $absence->comment, 'dani_GO' => $dani_GO ));
                }
            }
        }
        if(count($day_absences)>0) {
            return $this->view('Centaur::email.absence_day')
                    ->subject( __('emailing.day_absence') . ' ' . date_format($datum,'d.m.Y'))
                    ->with([
                        'day_absences' => $day_absences
                    ]);
        }
    }
}