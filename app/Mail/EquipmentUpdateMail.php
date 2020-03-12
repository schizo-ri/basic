<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\EquipmentList;
use App\Models\Preparation;
use Sentinel;

class EquipmentUpdateMail extends Mailable
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
    public function __construct(Preparation $preparation, $before_all, $after_all)
    {
        $this->preparation = $preparation;
        $this->after_all = $after_all;
        $this->before_all = $before_all;
    } 

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
     //   $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']  . '/preparations';
     /*    $link = 'http://localhost:8000/preparations'; */
        $link = 'http://proizvodnja.duplico.hr/preparations';

        return $this->view('Centaur::email.equipment1')
                    ->subject( 'Obnovljen popis opreme - ' . $this->preparation->project_no )
                    ->with([
						'preparation' => $this->preparation,
						'before_all' => $this->before_all,
						'after_all' => $this->after_all,
                        'link' => $link
					]);
    }
}
