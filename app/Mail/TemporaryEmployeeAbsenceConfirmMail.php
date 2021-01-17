<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\TemporaryEmployeeRequest;
use Sentinel;
use App\Models\MailTemplate;

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
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','TemporaryEmployeeAbsenceConfirmMail')->first();
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
        
        $odobrio_user = Sentinel::getUser();
		$odobrio = $odobrio_user->first_name . ' ' . $odobrio_user->last_name;
        
        if( $this->temporaryEmployeeRequest->approve == '1' ){
            $odobrenje = __('absence.is_approved');
        } else {
            $odobrenje = __('absence.not_approved');
        }

        return $this->view('emails.temporary_employee_requests.confirm')
                    ->subject( __('absence.approve_absence') . ' - ' . $this->temporaryEmployeeRequest->employee->user['first_name']   . '_' . $this->temporaryEmployeeRequest->employee->user['last_name'])
                    ->with([
                        'absence' => $this->temporaryEmployeeRequest,
                        'odobrenje' => $odobrenje,
                        'odobrio' => $odobrio,
                        'template_mail' => $mail_template,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);

    }
}