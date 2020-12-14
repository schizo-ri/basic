<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\TemporaryEmployeeRequest;
use App\Http\Controllers\BasicAbsenceController;
use App\Models\MailTemplate;

class TemporaryEmployeeAbsenceMail extends Mailable
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
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','TemporaryEmployeeAbsenceMail')->first();
        
        $zahtjev = array('start_date' => $this->temporaryEmployeeRequest['start_date'], 'end_date' => $this->temporaryEmployeeRequest['end_date']);
        $dani_zahtjev = BasicAbsenceController::daniGO_count($zahtjev);

        return $this->view('Centaur::email.temporaryEmployeeRequest')
                    ->subject( __('emailing.new_absence'))
                    ->with([
                        'temporaryEmployeeRequest' => $this->temporaryEmployeeRequest,
                        'dani_zahtjev' => $dani_zahtjev,
                        'template_mail' => $mail_template
                    ]);
    }
}
