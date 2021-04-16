<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\WorkRecord;
use App\Models\TravelOrder;
use App\Models\Absence;
use Log;

class CheckIn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check_in';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check-in employee';

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
   
        $travelorders = TravelOrder::openTravelOrders();
        if( count($travelorders) > 0 ) {
            foreach ($travelorders as $travelorder) {
                $employee = $travelorder->employee;
                $workRecord_today = WorkRecord::where('employee_id', $employee->id )->whereDate('start', $today_date)->first();
                $absence = Absence::where('employee_id', $employee->id )->whereDate('start_date', $today_date)->where('approve',1)->first();
                if( ! $workRecord_today && ! $absence ) {
                    $data = array(
                        'employee_id'  	 => $employee->id,
                        'start'  		=>  $today_date . ' 08:00' ,
                    );
                        
                    $workRecord = new WorkRecord();
                    $workRecord->saveWorkRecords($data);
                  
                }
              
            }
        }
       
        return "Prijava djelatnika je prošla ok";
    }
}