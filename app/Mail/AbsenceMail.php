<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Absence; 
use App\Http\Controllers\BasicAbsenceController;
use App\Models\MailTemplate;

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
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','AbsenceMail')->first();
        $mail_style = $mail_template->mailStyle;
        $mail_text = $mail_template->mailText;

        $convert_to_array = explode(';', $mail_text->text_header);
        $template_text_header = array();
        for($i=0; $i < count($convert_to_array ); $i++){
            $key_value = explode(':', $convert_to_array [$i]);
            if(  $key_value [0] && $key_value [1] ) {
                $template_text_header[$key_value[0]] = $key_value[1];
            }
        }

        $convert_to_array = explode(';', $mail_text->text_body);
        $template_text_body= array();
        for($i=0; $i < count($convert_to_array ); $i++){
            $key_value = explode(':', $convert_to_array [$i]);
            if(  $key_value [0] && $key_value [1] ) {
                $template_text_body[$key_value[0]] = $key_value[1];
            }
        }

        $convert_to_array = explode(';', $mail_text->text_footer);
        $template_text_footer = array();

        for($i=0; $i < count($convert_to_array ); $i++){
            $key_value = explode(':', $convert_to_array [$i]);
            if(  $key_value [0] && $key_value [1] ) {
                $template_text_footer[$key_value[0]] = $key_value[1];
            }
        }

        $employee = $this->absence->employee;
        $zahtjev = array('start_date' => $this->absence['start_date'], 'end_date' => $this->absence['end_date']);
        $dani_zahtjev = BasicAbsenceController::daniGO_count($zahtjev);
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
                $subject =__('emailing.new_absence') . ' ' . addslashes( $this->absence->absence->name ) ;
            }
            $view = 'Centaur::email.absence';
        }
        
        return $this->view($view) 
                    ->subject( $subject . ' - ' . $this->absence->employee->user['first_name']   . '_' . $this->absence->employee->user['last_name'])
                    ->with([
                        'absence' => $this->absence,
                        'dani_zahtjev' => $dani_zahtjev,
                        'slobodni_dani' => $slobodni_dani,
                        'neiskoristeno_GO' => $neiskoristeno_GO,
                        'mail_style' => $mail_style,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}