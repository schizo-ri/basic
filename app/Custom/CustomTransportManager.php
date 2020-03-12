<?php
namespace App\Custom;

use Illuminate\Mail\TransportManager;
use App\Models\Setting; 

class CustomTransportManager extends TransportManager {

    /**
     * Create a new manager instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
        $settings = Setting::all();

        if( $settings ){

            $this->app['config']['mail'] = [
                'driver'        => $settings->where('name','MAIL_DRIVER' )->first()->value,
                'host'          => $settings->where('name','MAIL_HOST' )->first()->value,
                'port'          => $settings->where('name','MAIL_PORT' )->first()->value,
                'from'          => [
                    'address'   => $settings->where('name','MAIL_FROM_ADDRESS' )->first()->value,
                    'name'      => $settings->where('name','MAIL_FROM_NAME' )->first()->value
                ],
                'encryption'    => $settings->where('name','MAIL_ENCRYPTION')->first()->value,
                'username'      => $settings->where('name','MAIL_USERNAME')->first()->value,
                'password'      => $settings->where('name','MAIL_PASSWORD')->first()->value,
                'sendmail'      =>  $settings->where('name','MAIL_SENDMAIL')->first()->value,
                /* 'pretend'       => $settings->mail_pretend  */
           ];
       }

    }
}
?>