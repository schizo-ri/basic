<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\EmployeeTraining;
use App\Models\MailTemplate;

class EmployeeTrainingMail extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * The employeeTraining instance.
     *
     * @var employeeTraining;

     */
    public $employeeTraining;
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmployeeTraining $employeeTraining)
    {
        $this->employeeTraining = $employeeTraining;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','EmployeeTrainingMail')->first();
        
        return $this->view('emails.employee_training.expiry')
                    ->subject( __('emailing.expiry_training') . ' ' . $this->employeeTraining->employee->user->first_name . ' ' . $this->employeeTraining->employee->user->last_name  )
                    ->with([
                        'employeeTraining' => $this->employeeTraining,
                        'template_mail' => $mail_template
                    ]);
    }
}
