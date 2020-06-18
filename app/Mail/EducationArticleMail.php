<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\EducationArticle; 

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
         return $this->view('Centaur::email.article_add')
					->subject( __('emailing.new_article') . ' - ' . $this->educationArticle->subject )
					->with([
						'educationArticle' => $this->educationArticle
					]);
    }
}
