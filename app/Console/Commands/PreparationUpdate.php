<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Preparation;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Support\Facades\Mail;
use App\Mail\PreparationReminderMail;

class PreparationUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:preparation_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Preparation update reminder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = EloquentUser::get();

        foreach ($users as $key => $user) {
            if( $user->inRole('priprema') ||  $user->inRole('mehanicka') || $user->inRole('oznake')) {
                $email = $user->email;
                Mail::to($email)->send(new PreparationReminderMail());
            }
        }
    }
}
