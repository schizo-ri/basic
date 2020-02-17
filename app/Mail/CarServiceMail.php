<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Locco;
use App\Models\Car;
use Sentinel;

class CarServiceMail extends Mailable
{
    use Queueable, SerializesModels;

      /**
     * The locco instance.
     *
     * @var Locco
     */
    public $locco;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Locco $locco)
    {
        $this->locco = $locco;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $car = Car::find($this->locco->car_id);

        return $this->markdown('centaur.email.car_service')
                    ->subject( __('basic.malfunction') . ' - ' . $car->registration)
                    ->with([
                        'car' => $car, 
                        'user' =>  Sentinel::getUser(), 
                        'napomena' =>  $this->locco->comment, 
                        ]);
    }
}
