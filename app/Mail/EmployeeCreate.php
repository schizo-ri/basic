<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Employee; 
use App\Models\MailTemplate;

class EmployeeCreate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The emplyee instance.
     *
     * @var employee;

     */
    public $employee;
	
	/**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','EmployeeCreate')->first();
        
        if( $this->employee->reg_date) {
            $view = 'emails.employees.create';
        } else {
            $view = 'emails.employees.in_progress';
        }
        return $this->view( $view )
                    ->subject( __('emailing.new_employee') )
                    ->with([
						'employee' => $this->employee,
                        'template_mail' => $mail_template
					]);
    }
}
