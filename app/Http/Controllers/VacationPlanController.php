<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vacation;
use App\Models\VacationPlan;
use App\Models\Absence;
use DateTime;
use Sentinel;

class VacationPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $vacation_plan = VacationPlan::find($id);
        $vacation = Vacation::find( $vacation_plan->vacation_id );
        $plan = json_decode($vacation->plan, true);
        $count_plans = $plan[$request['department_id']]['no_people'];

        $dates = array();
        $begin = new DateTime( $vacation->start_period);
		$end   = new DateTime( $vacation->end_period);
        $interval = $vacation->interval;

        for ($i = $begin; $i < $end; $i->modify('+'. $vacation->interval .' day')) {
            if( count($vacation->hasPlans->where('start_date', $i->format("Y-m-d"))) <  $count_plans )
            array_push($dates, $i->format("Y-m-d"));
        }
       
        return view('Centaur::vacation_plans.edit', ['vacation_plan' => $vacation_plan, 'dates' => $dates]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vacation_plan = VacationPlan::find($id);

        $data = array(
          /*   'vacation_id'    => $request['vacation_id'],
			'employee_id'   => $request['employee_id'], */
			'start_date'  	=> $request['start_date'],
		);

		$vacation_plan->updateVacationPlan($data);
		
        session()->flash('success', __('ctrl.data_edit'));
        
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vacation_plan = VacationPlan::find($id);
        $vacation_plans = VacationPlan::where('employee_id', $vacation_plan->employee_id )->get();
       
        if( count( $vacation_plans)> 0 ) {
            foreach ($vacation_plans as $plan ) {
                $absence = $plan->absence;
                if( $absence ) {
                    $absence->delete();
                }
                $plan->delete();
            }
        }

        $message = session()->flash('success', __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
    
    public function vacationPlan ( Request $request)
    {
        $vacation = Vacation::find( $request['vacation_id'] );
        $no_week = $vacation->no_week;
      
        $date = new DateTime( $request['start_date']) ;
        $dates = array();
      
        for ($i=0; $i < $no_week; $i++) {
            array_push($dates,  $date->format("Y-m-d") );
            $date->modify('+7 days');
        }
      
        foreach ($dates as $start_date) {
            $data = array(
                'vacation_id'   => $request['vacation_id'],
                'employee_id'   => $request['employee_id'],
                'start_date'  	=> $start_date,
            );
    
            $vacationPlan = new VacationPlan();
            $vacationPlan->saveVacationPlan($data);
        }
		
		session()->flash('success', __('ctrl.data_save'));
        
        return redirect()->back();
    }
}
