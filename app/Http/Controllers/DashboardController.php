<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Questionnaire;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Notice;
use App\Models\Event;
use App\Models\Department;
use Sentinel;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Sentinel::check()) {
            $questionnaires = Questionnaire::where('status','1')->get();
            $user = Sentinel::getUser()->employee;
            $notices = Notice::orderBy('created_at','DESC')->get();
            $departments = Department::get();

            if($user) {
                $posts = Post::where('employee_id',$user->id)->orWhere('to_employee_id',$user->id)->orderBy('updated_at','DESC')->get();
                $comments = Comment::orderBy('created_at','DESC')->get();
                $user_department = $user->work->department->id;
                $events = Event::where('employee_id',$user->id)->orderBy('created_at','ASC')->get();

                return view('Centaur::dashboard',['questionnaires' => $questionnaires, 'posts' => $posts, 'comments' => $comments, 'notices' => $notices, 'user_department' => $user_department,'events' => $events,'departments' => $departments]);
            } else {
                return view('Centaur::dashboard',['questionnaires' => $questionnaires, 'notices' => $notices,'departments' => $departments]);
            }
        } else {

            return view('Centaur::dashboard');
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
