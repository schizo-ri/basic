<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\EquipmentList;
use App\Models\Preparation;
use Sentinel;

class EquipmentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The EquipmentList instance.
     *
     * @var vacationRequest
     */
    public $preparation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Preparation $preparation)
    {
        $this->preparation = $preparation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from = Sentinel::getUser()->email;
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']  . '/preparations';

        return $this->from( $from )
                    ->view('Centaur::email.equipment')
                    ->subject( 'Obnovljen popis opreme - ' . $this->preparation->project_no )
                    ->with([
						'preparation' => $this->preparation,
                        'link' => $link
					]);
    }
}
