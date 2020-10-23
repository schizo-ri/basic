<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Employee;

class BirthDayMail extends Mailable
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
        return $this->markdown('emails.employees.birthday')
                    ->subject( __('basic.b_day_employee') . ' - ' .  $this->employee->first_name . ' ' .  $this->employee->last_name)
                    ->with([
                        'employee' =>  $this->employee
                    ]);
    }
}
