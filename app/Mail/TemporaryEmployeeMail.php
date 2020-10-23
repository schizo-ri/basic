<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\TemporaryEmployee;

class TemporaryEmployeeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The emplyee instance.
     *
     * @var temporaryEmployee;

     */
    public $temporaryEmployee;
	
	/**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TemporaryEmployee $temporaryEmployee)
    {
        $this->temporaryEmployee = $temporaryEmployee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.temporary_employees.create')
                    ->subject( __('emailing.new_temporary_employee') )
                    ->with([
						'temporaryEmployee' => $this->temporaryEmployee
					]);
    }
}
