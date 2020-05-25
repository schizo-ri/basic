<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class ClearDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:clear_database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command clear database';

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
        DB::transaction(function () {      
            $date = date('2020-05-24');  

            DB::table('ads')->whereDate('created_at','>', $date)->delete();
            DB::table('absences')->whereDate('created_at','>', $date)->delete();
            DB::table('absence_types')->whereDate('created_at','>', $date)->delete();
            DB::table('activations')->whereDate('created_at','>', $date)->delete();
            DB::table('ad_categories')->whereDate('created_at','>', $date)->delete();
            DB::table('benefits')->whereDate('created_at','>', $date)->delete();
            DB::table('campaigns')->whereDate('created_at','>', $date)->delete();
            DB::table('campaign_recipients')->whereDate('created_at','>', $date)->delete();
            DB::table('campaign_sequences')->whereDate('created_at','>', $date)->delete();
            DB::table('cars')->whereDate('created_at','>', $date)->delete();
            DB::table('comments')->whereDate('created_at','>', $date)->delete();
            DB::table('companies')->whereDate('created_at','>', $date)->delete();
            DB::table('departments')->whereDate('created_at','>', $date)->delete();
            DB::table('department_roles')->whereDate('created_at','>', $date)->delete();
            DB::table('documents')->whereDate('created_at','>', $date)->delete();
            DB::table('education')->whereDate('created_at','>', $date)->delete();
            DB::table('educations')->whereDate('created_at','>', $date)->delete();
            DB::table('education_articles')->whereDate('created_at','>', $date)->delete();
            DB::table('education_themes')->whereDate('created_at','>', $date)->delete();
            DB::table('emailings')->whereDate('created_at','>', $date)->delete();
            DB::table('employees')->whereDate('created_at','>', $date)->delete();
            DB::table('evaluations')->whereDate('created_at','>', $date)->delete();
            DB::table('evaluation_answers')->whereDate('created_at','>', $date)->delete();
            DB::table('evaluation_categories')->whereDate('created_at','>', $date)->delete();
            DB::table('evaluation_questions')->whereDate('created_at','>', $date)->delete();
            DB::table('evaluation_ratings')->whereDate('created_at','>', $date)->delete();
            DB::table('events')->whereDate('created_at','>', $date)->delete();
            DB::table('fuels')->whereDate('created_at','>', $date)->delete();
            DB::table('loccos')->whereDate('created_at','>', $date)->delete();
            DB::table('notices')->whereDate('created_at','>', $date)->delete();
            DB::table('notice_statistics')->whereDate('created_at','>', $date)->delete();
            DB::table('posts')->whereDate('created_at','>', $date)->delete();
            DB::table('questionnaires')->whereDate('created_at','>', $date)->delete();
            DB::table('questionnaire_results')->whereDate('created_at','>', $date)->delete();
            DB::table('roles')->whereDate('created_at','>', $date)->delete();
            DB::table('role_users')->whereDate('created_at','>', $date)->delete();
            DB::table('tables')->whereDate('created_at','>', $date)->delete();
            DB::table('users')->whereDate('created_at','>', $date)->delete();
            DB::table('user_interes')->whereDate('created_at','>', $date)->delete();
            DB::table('works')->whereDate('created_at','>', $date)->delete();
        });

    }
}
