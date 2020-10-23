<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Employee; 
use DateTime;

class StrangerMail extends Mailable
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
        $date1 = new DateTime($this->employee->permission_date); 
        $date2 = new DateTime("now"); 
        $interval = $date1->diff($date2); 
        $days = $interval->format('%a');

        return $this->markdown('emails.employees.stranger')
                    ->subject( __('basic.stranger') . ' - ' . $this->employee->user->first_name . ' ' .  $this->employee->user->last_name )
                    ->with([
                        'employee'  => $this->employee,
                        'days'      => $days,
                    ]);
    }
}