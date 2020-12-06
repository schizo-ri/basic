<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Employee; 
use DateTime;
use App\Models\MailTemplate;

class ProbationMail extends Mailable
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
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','ProbationMail')->first();
        
        $date1 = new DateTime( $this->employee->reg_date); 
        $date1->modify('+6 month');
        $date2 = new DateTime("now");
        $interval = $date1->diff($date2); 
        $days = $interval->format('%a');

        return $this->view('emails.employees.probation')
                    ->subject( __('basic.probation') . ' - ' . $this->employee->user->first_name . ' ' .  $this->employee->user->last_name )
                    ->with([
                        'employee'  => $this->employee,
                        'days'      => $days,
                        'template_mail' => $mail_template
                    ]);
    }
}