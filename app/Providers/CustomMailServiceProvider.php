<?php

namespace App\Providers;

use Illuminate\Mail\MailServiceProvider;
use App\Custom\CustomTransportManager;
use App\Models\Setting; 

class CustomMailServiceProvider extends MailServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function registerSwiftTransport()
    {
        $settings = Setting::all();
        
        if (strpos(php_sapi_name(), 'cli') === false) {
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
            ];
        }
        $this->app->singleton('swift.transport', function ($app) { 
            return new CustomTransportManager($app);
        });     
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
