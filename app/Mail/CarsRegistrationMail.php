<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Car;


class CarsRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * The car instance.
     *
     * @var Cars
     */
    public $cars;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(isset( $_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] != 'localhost') {
            /*     $host =  $_SERVER['HTTP_HOST']; */
            /*    } else {
                $host = 'localhost:8000';
            } */
        }

        $host = 'https://ru-ve.myintranet.io';
        $url =  $host  . '/store_event/'.$this->car->id;

        return $this->view('centaur.email.car_registrations')
                    ->subject( __('basic.vehicle_registration') . ' - ' . $this->car->registration)
                    ->with([
                        'car' => $this->car,
                        'url'=>  $url,
                    ]);
    }
}