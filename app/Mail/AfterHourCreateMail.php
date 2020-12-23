<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Afterhour;
use DateTime;
use App\Models\MailTemplate;
use App\Http\Controllers\ApiController;

class AfterHourCreateMail extends Mailable
{
    use Queueable, SerializesModels;
  
    /**
     * The campaign instance.
     *
     * @var Campaign
     */
    public $afterhour;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Afterhour $afterhour )
    {
        $this->afterhour = $afterhour;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','AfterHourCreateMail')->first();
        
        $time1 = new DateTime($this->afterhour->start_time );
        $time2 = new DateTime($this->afterhour->end_time);
        
        $interval = $time2->diff($time1);
        $interval = $interval->format('%H:%I');
                
        if( $this->afterhour->erp_task_id) {
            $api = new ApiController();

            $tasks = $api->get_employee_project_tasks( $this->afterhour->employee->erp_id, null );
            $task = $tasks[$this->afterhour->erp_task_id];
        }
        return $this->view('emails.afterhours.create')
                    ->subject( __('emailing.afterhour') . ' - ' . $this->afterhour->employee->user->first_name . ' ' .  $this->afterhour->employee->user->last_name)
					->with([
						'afterhour' =>  $this->afterhour,
						'interval' =>  $interval,
                        'template_mail' => $mail_template,
                        'task' => $task
					]);
    }
}
