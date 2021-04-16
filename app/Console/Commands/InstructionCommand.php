<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Instruction; 
use App\Mail\InstructionReminderMail;
use Illuminate\Support\Facades\Mail;
use DateTime;

class InstructionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instruction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command instruction';

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
        $instructions = Instruction::where('active',1)->get();

        foreach ($instructions as $instruction) {
            $dateStamp = date('Y')-1 . date('-m-d H:i:s',strtotime($instruction->created_at));
            $date = new DateTime($dateStamp);
            $datesArr = [];
            for ($i=1; $i<12 ; $i++) {
                $date->modify('+3 month');
                $datesArr[] = $date->format('Y-m-d');
            }
           
            if( in_array(date('Y-m-d'),$datesArr) ) {
                $send_to_mail = $instruction->employee ? $instruction->employee->email : null; 
                if ( $send_to_mail ) {
                    Mail::to($send_to_mail)->send(new InstructionReminderMail($instruction)); 
                }
            }
        }
    }
}
