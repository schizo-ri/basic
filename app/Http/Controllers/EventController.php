<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Employee;
use Sentinel;
use App\Http\Requests\EventRequest;

class EventController extends Controller
{
     /**
   * Set middleware to quard controller.
   *
   * @return void
   */
   public function __construct()
    {
        $this->middleware('sentinel.auth');
    }
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::get();
		$dataArr = array();
		foreach($events as $event1) {
			array_push($dataArr, ['name' => "event", 'date' => $event1->date]);
		}
		
		return view('Centaur::events.index',['dataArr'=>$dataArr,'events'=>$events]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {
        $user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		
		$data = array(
			'employee_id'  	=> $employee->id,
			'title'  		=> $request['title'],
			'date'  		=> $request['date'],
			'time1' 		=> $request['time1'],
			'time2' 		=> $request['time2'],
			'description'   => $request['description']
		);
		
		$event = new Event();
		$event->saveEvent($data);
		
		session()->flash('success', "Podaci su spremljeni");
		
        return redirect()->route('events.index');
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
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
