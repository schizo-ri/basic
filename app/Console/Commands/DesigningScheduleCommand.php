<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DesigningEmployee;
use Illuminate\Support\Facades\Mail;
use App\Mail\DesigningScheduleMail;

class DesigningScheduleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command schedule';

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
        $designing_employees = DesigningEmployee::where('start_date', date('Y-m-d'))->get();

        if($designing_employees && count($designing_employees) > 0 ) {
            foreach ($designing_employees as $designing_employee) {
                $designing = $designing_employee->designing;
                $email =  $designing_employee->user->email;
               /*  $email = 'jelena.juras@duplico.hr'; */
              
                Mail::to($email)->send(new DesigningScheduleMail( $designing )); 
                
            }
        }
    }
}
