<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absence;
use App\Mail\SickLeaveMail;
use App\Mail\ErrorMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\EmailingController;
use App\Http\Controllers\ApiController;
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
        Log::info($absences);
        foreach( $absences as $absence ) {
            if( $absence->start_date != date('Y-m-d') ) {
               /*  try { */
                    $api = new ApiController();
                    $send_leave_request = $api->send_leave_request($absence, 'abs');
                    if($send_leave_request == true) {
                        $message_erp = ' Zahtjev ' . $absence->id . ' je uspješno zapisan u Odoo.';
                    } else {
                        $message_erp = ' Zahtjev  ' . $absence->id . ' NIJE zapisan u Odoo. Došlo je do neke greške';
                    }
                    Log::info($message_erp);

                    $send_to_mail = 'jelena.juras@duplico.hr';
    
                    Mail::to($send_to_mail)->send(new SickLeaveMail( $absence ));

				/* } catch (\Throwable $th) {
					$email = 'jelena.juras@duplico.hr';
					$url = '';
					Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 
	
					session()->flash('error', __('ctrl.error') );
					return redirect()->back();
                } */
            }
        }
    }
}
