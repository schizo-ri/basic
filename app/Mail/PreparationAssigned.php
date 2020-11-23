<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\PreparationEmployee;

class PreparationAssigned extends Mailable
{
    use Queueable, SerializesModels;

    public $preparationEmployee;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PreparationEmployee $preparationEmployee)
    {
        $this->preparationEmployee = $preparationEmployee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']  . '/preparations/' . $this->preparationEmployee->preparation_id;

        return $this->markdown('email.preparation.assigned')
                    ->subject( 'Dodjeljen zadatak na projektu ' . $this->preparationEmployee->preparation->project_no )
                    ->with([
                        'preparationEmployee' => $this->preparationEmployee,
                        'link' => $link
                    ]);
    }
}
