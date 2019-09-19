<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\NoticeRequest;
use App\Http\Controllers\Controller;
use App\Models\NoticeStatistic;
use App\Models\Notice;
use App\Models\Department;
use App\Models\Employee;
use Sentinel;

class NoticeStatisticController extends Controller
{
    /**
     *
     * Set middleware to quard controller.
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
    public function index(Request $request)
    {
        $departments = Department::get();
        $data = array();

        if(isset($request['notice_id'])) {
            $employees = Employee::where('checkout',null)->get()->count();
            
            $notice_statistics = NoticeStatistic::where('notice_id',$request['notice_id'])->get();
            $procitano = count($notice_statistics) / $employees * 100;
            $notice = Notice::find($request['notice_id']);
            array_push($data,  $procitano);
            array_push($data, 100 -  $procitano);

            $dataArr = '[' . implode(',', $data) . ']';
            
            return view('Centaur::notice_statistics.index', ['notice_statistics' => $notice_statistics, 'notice' => $notice, 'departments' => $departments, 'data' => $data, 'dataArr' => $dataArr]);
        } else {
            $notice_statistics = NoticeStatistic::get();

            return view('Centaur::notice_statistics.index', ['notice_statistics' => $notice_statistics, 'dataArr' => $dataArr]);
        }

        
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
