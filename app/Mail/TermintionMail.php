<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\EmployeeTermination;
use Log;
use App\Models\MailTemplate;

class TermintionMail extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * The emplyee instance.
     *
     * @var employeeTermination;

     */
    public $employeeTermination;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmployeeTermination $employeeTermination)
    {
        $this->employeeTermination = $employeeTermination;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','TermintionMail')->first();
        
        return $this->view('emails.employee_terminations.termination')
                    ->subject( __('basic.employee_termination') )
                    ->with([
						'employeeTermination' => $this->employeeTermination,
                        'template_mail' => $mail_template
					]);
    }
}
