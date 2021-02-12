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
        $mail_style = array();
        $template_text_header = array();
        $template_text_body= array();
        $template_text_footer = array();

        if( $mail_template ) {
            $mail_style = $mail_template->mailStyle;
            $template_text_header = MailTemplate::textHeader( $mail_template );
            $template_text_body = MailTemplate::textBody( $mail_template );
            $template_text_footer = MailTemplate::textFooter( $mail_template );
        }
        
        $time1 = new DateTime($this->afterhour->start_time );
        $time2 = new DateTime($this->afterhour->end_time);
        
        $interval = $time2->diff($time1);
        $interval = $interval->format('%H:%I');
        $task = $this->afterhour->project ? $this->afterhour->project->name : '';
        $sublect = __('emailing.afterhour') . ' - ' . $this->afterhour->employee->user->first_name . ' ' .  $this->afterhour->employee->user->last_name;
        return $this->view('emails.afterhours.create')
                    ->subject( $sublect )
					->with([
						'afterhour' =>  $this->afterhour,
						'interval' =>  $interval,
                        'template_mail' => $mail_template,
                        'mail_style' => $mail_style,
                        'task' => $task,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
					]);
    }
}
