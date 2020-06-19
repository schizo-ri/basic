<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Preparation;
use Illuminate\Support\Facades\Mail;
use DateTime;
use DateTimeZone;
use App\Mail\PreparationCreateMail;

class PreparationCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:preparation_create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new project';

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
        $today = new DateTime('now', new DateTimeZone('Europe/Zagreb'));
        $date_today = date_format($today, 'Y-m-d');

        $yesterday = date_modify( new DateTime('now'), '-1day');
        $date_yesterday = date_format($yesterday, 'Y-m-d');

        $preparations = Preparation::whereDate('created_at',  $date_yesterday)->whereTime('created_at', '>', '12:00:00')->get();
        $preparations = $preparations->merge(Preparation::whereDate('created_at',  $date_today)->whereTime('created_at', '<', '12:00:00')->get());

        $new_projects = array();

        foreach ($preparations as $preparation) {
            $project = array(
                'name' => $preparation->name,
                'project_no'  => $preparation->project_no,
                'project_manager'  => $preparation->manager['first_name'] . ' ' . $preparation->manager['last_name'],
                'date'  => date('d.m.Y',strtotime($preparation->delivery))
            );
            array_push($new_projects, $project);
        }


        $email = 'matija.rendulic@duplico.hr';
        if(count($new_projects) > 0) {
            Mail::to($email)->send(new PreparationCreateMail($new_projects));
            Mail::to('jelena.juras@duplico.hr')->send(new PreparationCreateMail($new_projects));
        }
    }
}
