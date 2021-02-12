<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\MailTemplate;
use App\Models\WorkTask;
use Log;


class DiaryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The workDiaries instance.
     *
     * @var workDiaries
     */
    public $workDiaries;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($workDiaries)
    {
        $this->workDiaries = $workDiaries;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','DiaryMail')->first();
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
       
        $workTasks = WorkTask::get();
       
        return $this->view('emails.work_diaries.report')
                    ->subject('Dnevnik rada - izvještaj')
                    ->with([
                        'workDiaries' => $this->workDiaries,   
                        'workTasks' => $workTasks,   
                        'template_mail' => $mail_template,                     
                        'mail_style' => $mail_style,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
                    ]);
    }
}
