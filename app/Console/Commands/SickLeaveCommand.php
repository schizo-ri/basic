<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absence;
use App\Mail\SickLeaveMail;
use Illuminate\Support\Facades\Mail;
use Log;

class SickLeaveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sickLeave';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bolovanje na dan, upis u Odoo';

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
        $absences = Absence::SickUserOpenToday();

        foreach( $absences as $absence ) {
            $send_to_mail = 'jelena.juras@duplico.hr';

            Mail::to($send_to_mail)->send(new SickLeaveMail( $absence ));
        }
    }
}
