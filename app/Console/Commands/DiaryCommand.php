<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\WorkDiary;
use App\Models\WorkDiaryItem;
use Sentinel;
use Illuminate\Support\Facades\Mail;
use App\Mail\DiaryMail;
use DateTime;
use Log;

class DiaryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'diary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Work diary';

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
        $date = date('Y-m-d');

        $workDiaries = WorkDiary::whereDate('date',$date )->get()->groupBy('project_id');
        
        foreach ( $workDiaries as $project_id => $project_workDiaries) {
            $send_to = array('jelena.juras@duplico.hr');
            $project = Project::find($project_id); 
            if($project->employee_id ) {
                array_push( $send_to,$project->employee->email ); 
            }
            if($project->employee_id2 ) {
                array_push( $send_to,$project->employee2->email ); 
            }
            Log::info($send_to);
           
            foreach ($send_to as $send_to_mail) {
                Mail::to($send_to_mail)->send(new DiaryMail($project_workDiaries));
            }
        }
    
    }
}
