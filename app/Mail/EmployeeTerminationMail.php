<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\EmployeeTermination; 

class EmployeeTerminationMail extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * The emplyee instance.
     *
     * @var employee;

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
        return $this->markdown('emails.employee_terminations.create')
                    ->subject( __('basic.checkout_employee') . ' - ' . $this->employeeTermination->employee->user->first_name . ' ' .  $this->employeeTermination->employee->user->last_name )
					->with([
						'employeeTermination' => $this->employeeTermination
					]);
    }
}
