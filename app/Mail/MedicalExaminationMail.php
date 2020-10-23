<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Employee; 
use DateTime;

class MedicalExaminationMail extends Mailable
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
        $date1 = new DateTime($this->employee->lijecn_pregled); 
        $date2 = new DateTime("now"); 
        $interval = $date1->diff($date2); 
        $days = $interval->format('%a');
        
        return $this->markdown('emails.employees.medical_examination')
                    ->subject( __('basic.medical_examination') . ' - ' . $this->employee->first_name . ' ' .  $this->employee->last_name )
                    ->with([
                        'employee' => $this->employee,
                        'days' => $days
                    ]);
    }
}