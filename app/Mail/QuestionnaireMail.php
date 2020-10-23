<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\EvaluationEmployee;

class QuestionnaireMail extends Mailable
{
    /**
     * The emplyee instance.
     *
     * @var vacationRequest
     */
    public $evaluationEmployee;
    
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employee, $brojAnketa)
    {
        $this->employee = $employee;
        $this->brojAnketa = $brojAnketa;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $brojAnketa = count($this->brojAnketa);

        return $this->markdown('emails.quersionnaires.unfinished')
                    ->subject( __('quersionnaire.questionnaire_day') . ' - ' . $this->employee->user->first_name. ' - ' . $this->employee->user->last_name )
                    ->with(['employee'    => $this->employee,
                            'brojAnketa'       => $brojAnketa
                    ]);
    }
}
