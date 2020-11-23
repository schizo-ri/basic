<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DesigningComment;
use Sentinel;
use Illuminate\Support\Facades\Mail;
use App\Mail\DesigningCommentMail;

class DesigningCommentController extends Controller
{
    public function __construct()
    {
        // Middleware
        $this->middleware('sentinel.auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
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
        $data = array(
            'designing_id'  => $request['designing_id'],
            'user_id'       => Sentinel::getUser()->id,
            'comment'       => $request['comment'],
        );
        
        $designingComment = new DesigningComment();
        $designingComment->saveDesigningComment($data);

        $manager = $designingComment->designing->manager;
        $designer = $designingComment->designing->designer;

        $send_to_mail = array('jelena.juras@duplico.hr');

        if($manager && $manager->id != Sentinel::getUser()->id  ) {
            array_push( $send_to_mail,  $manager->email);
        }
        if($designer && $designer->id != Sentinel::getUser()->id  ) {
            array_push( $send_to_mail,  $designer->email);
        }
        if(count($designingComment->designing->hasComments) > 0) {
            $comments = $designingComment->designing->hasComments->unique('user_id');
           
            foreach ($comments as $comment) {
                if( $comment->user_id != Sentinel::getUser()->id ) {
                    if( ! in_array( $comment->user->email,$send_to_mail)) {
                        array_push( $send_to_mail, $comment->user->email);
                    }
                }
            }
        }
        $send_to_mail = array('jelena.juras@duplico.hr');
        foreach( array_unique($send_to_mail) as $email) {
            try {
                if( $email ) {
                    Mail::to($email)->send(new DesigningCommentMail($designingComment)); 
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        session()->flash('success', "Podaci su spremljeni");
            
        return redirect()->back();
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
