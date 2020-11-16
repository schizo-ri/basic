<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Employee;
use DateTime;

class AnniversaryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The campaign instance.
     *
     * @var Campaign
     */
    public $employee;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( Employee $employee )
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
        $date_now = new DateTime('now'); 
        $date = new DateTime( $this->employee->reg_date); 
        $years = $date_now->format('Y') - $date->format('Y') ; 

        $dan = $date->format('d');
        if( $date_now->format('d') != $dan) {
            $date->modify('-5 days'); 
            $interval = $date_now->diff($date); 
            $br_dana = $interval->format('%d');
            $br_dana = $br_dana+5;
        } else {
            $br_dana = 0;
        }
        
        if( $br_dana == 0 ) {
            $dana = 'danas';
        } else {
            $dana =  'za ' . $br_dana . ' dana';
        } 
            
        return $this->markdown('emails.employees.anniversary')
                    ->subject( __('basic.anniversary') . ' - ' .  $this->employee->first_name . ' ' .  $this->employee->last_name)
                    ->with([
                        'employee' =>  $this->employee,
                        'dana' =>  $dana,
                        'years' =>  $years,
                    ]);
    }
}