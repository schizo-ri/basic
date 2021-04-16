<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WorkRecord;
use DateTime;
use Log;

class CheckOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check_out';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check-out employee';

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
        $today_date = date('Y-m-d');
        if( date('N') < 5) {
            $checkOut_time = $today_date . ' 16:15';
        } else {
            $checkOut_time = $today_date . ' 15:00';
        }
        
        $workRecords = WorkRecord::whereDate('start',$today_date)->get();
        
        foreach ($workRecords as $workRecord) {
            if($workRecord->end == null) {
                $workRecord->updateWorkRecords(['end' => $checkOut_time ]);
            }
        }
        
        return "Odjavljeni su svi djelatnici";
    }
}
