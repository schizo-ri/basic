<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Questionnaire;
use DateTime;
use App\Models\MailTemplate;

class QuestionnaireSend extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Questionnaire $questionnaire)
    {
        $this->questionnaire = $questionnaire;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','QuestionnaireSend')->first();
        
		return $this->view('Centaur::email.Questionnaire')
					->subject( __('questionnaire.questionnaire') . ' - ' . $this->questionnaire->name)
					->with([
						'questionnaire' => $this->questionnaire,
                        'template_mail' => $mail_template
					]);
    }
}
