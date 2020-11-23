<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\DesigningComment;

class DesigningCommentMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $comment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(DesigningComment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']  . '/designings/' . $this->comment->designing_id;

        return $this->markdown('email.designing.new_comment')
                    ->subject( 'Nova poruke na projektu ' . $this->comment->designing->project_no )
                    ->with([
                        'comment' => $this->comment,
                        'link' => $link
                    ]);
    }
}
