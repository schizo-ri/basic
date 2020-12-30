<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\EducationArticle; 
use App\Models\MailTemplate;

class EducationArticleMail extends Mailable
{
    use Queueable, SerializesModels;
	
	/**
     * The emplyee instance.
     *
     * @var vacationRequest
     */
    public $educationArticle;
	
	
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EducationArticle $educationArticle)
    {
        $this->educationArticle = $educationArticle;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail_template = MailTemplate::orderBy('created_at','DESC')->where('for_mail','EducationArticleMail')->first();
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
        
         return $this->view('Centaur::email.article_add')
					->subject( __('emailing.new_article') . ' - ' . $this->educationArticle->subject )
					->with([
						'educationArticle' => $this->educationArticle,
                        'template_mail' => $mail_template,
                        'text_header' => $template_text_header,
                        'text_body' => $template_text_body,
                        'text_footer' => $template_text_footer
					]);
    }
}
