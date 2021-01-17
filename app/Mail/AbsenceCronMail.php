<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Absence;
use App\Models\TemporaryEmployeeRequest;
use App\Http\Controllers\BasicAbsenceController;
use DateTime;
use DateInterval;
use DatePeriod;
use Log;
use App\Models\MailTemplate;

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
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','AbsenceCronMail')->first();
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

        $datum = new DateTime('now');
		date_modify($datum, '+1day');
		$dan = date_format($datum,'d');
		$mjesec = date_format($datum,'m');
        $ova_godina = date_format($datum,'Y');
        
        $day_absences = array();
        $absences = Absence::AbsencesForMonth($mjesec, $ova_godina);
        $absences = $absences->where('approve',1);

        foreach($absences as $absence){			
            $begin = new DateTime($absence->start_date);
            $end = new DateTime($absence->end_date);
            $end->setTime(0,0,1);
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            
            $zahtjevi = BasicAbsenceController::zahtjevi($absence->employee); 
            $dani_GO = $zahtjevi['ukupnoPreostalo'];

            foreach ($period as $dan_perioda) {  //ako je dan  GO !!!
                $period_day = date_format($dan_perioda,'d');
                $period_month = date_format($dan_perioda,'m');
                $period_year = date_format($dan_perioda,'Y');
                if($period_day == $dan & $period_month == $mjesec & $period_year == $ova_godina ){
                    array_push($day_absences, array(
                        'ime' => $absence->employee->user['first_name'] . ' ' . $absence->employee->user['last_name'], 
                        'zahtjev' =>  $absence->absence['name'], 
                        'period' => date('d.m.Y', strtotime($absence->start_date)) . '-'. date('d.m.Y', strtotime($absence->end_date)) . ' - ' . $absence->absence['name'] != 'Izlazak' ? date('d.m.Y', strtotime($absence->end_date)) : '', 
                        'vrijeme' => $absence->absence['name'] == 'Izlazak' ? $absence->start_time . ' - ' .  $absence->end_time : '', 
                        'napomena' => $absence->comment,
                        'dani_GO' => $dani_GO ));
                }
            }
        }

        $izostanci_priv = TemporaryEmployeeRequest::TemporaryEmployeeSortApproved();

        if(count($izostanci_priv) >0) {
			foreach($izostanci_priv as $izostanak_priv){
                $begin2 = new DateTime($izostanak_priv->start_date);
                $end2 = new DateTime($izostanak_priv->end_date);
                $end2->setTime(0,0,1);
                $interval2 = DateInterval::createFromDateString('1 day');
                $period2 = new DatePeriod($begin2, $interval2, $end2);

                $begin_dan = date_format($begin2,'d');
                $begin_mjesec = date_format($begin2,'m');			
                $begin_godina = date_format($begin2,'Y');
                
                foreach ($period2 as $dan2) {  //ako je dan  GO !!!
                    $period_day = date_format($dan2,'d');
                    $period_month = date_format($dan2,'m');
                    $period_year = date_format($dan2,'Y');
                    if($begin2 == $end2 && $begin_dan == $dan && $begin_mjesec == $mjesec){
                        array_push($day_absences,array('ime' => $izostanak_priv->employee->user->first_name . ' ' . $izostanak_priv->employee->user->last_name, 
                                                        'zahtjev' =>  $izostanak_priv->absence_type['name'], 
                                                        'period' => date('d.m.Y', strtotime( $izostanak_priv->start_date)), 
                                                        'vrijeme' => $izostanak_priv->start_time . ' - ' .  $izostanak_priv->end_time, 
                                                        'dani_GO' => '', 
                                                        'napomena' =>  $izostanak_priv->comment ));
                    } else if($period_day == $dan && $period_month == $mjesec && $period_year == $ova_godina || $begin2 == $end2 ){
                        array_push($day_absences,array('ime' => $izostanak_priv->employee->user->first_name . ' ' . $izostanak_priv->employee->user->last_name, 
                                                        'zahtjev' =>  $izostanak_priv->absence_type['name'], 
                                                        'period' => date('d.m.Y', strtotime( $izostanak_priv->start_date)) . ' - ' .  date('d.m.Y', strtotime($izostanak_priv->end_date)), 
                                                        'vrijeme' => $izostanak_priv->start_time . ' - ' .  $izostanak_priv->end_time, 
                                                        'napomena' =>  $izostanak_priv->comment, 
                                                        'dani_GO' => '-'));
                    }
                }
			}
        }
        if(count($day_absences)>0) {
            $title = __('absence.absence_for_day') . ' ' . date_format($datum,'d.m.Y');
            return $this->view('emails.absences.today_absence')
                    ->subject(  $title )
                    ->with(['day_absences'    => $day_absences,
                            'title'    => $title,
                            'template_mail' => $mail_template,
                            'mail_style' => $mail_style,
                            'text_header' => $template_text_header,
                            'text_body' => $template_text_body,
                            'text_footer' => $template_text_footer
            ]);
        }
    }
}