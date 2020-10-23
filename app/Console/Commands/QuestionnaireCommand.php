<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Questionnaire;
use App\Models\EvaluationEmployee;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuestionnaireMail;

class QuestionnaireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'questionnaire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Anketa';

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
		$employees = Employee::employees_firstNameASC();
		$questionnaires = Questionnaire::where('status',1)->get();
		
		foreach($questionnaires as $questionnaire){
            foreach($employees as $employee) {
                $evaluationEmployees = EvaluationEmployee::EvaluationEmployeeForQuestionnaire($questionnaire->id,$employee->id );
                if(count($evaluationEmployees) < 15){
                    $email  = $employee->employee['email'];
                    $brojAnketa = count($evaluationEmployees);
                    
                    Mail::to($email)->send(new QuestionnaireMail( $employee, $brojAnketa ));
                }
            }
		}
		
		$this->info('Messages sent successfully!');
    }
}
