<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Employee;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Sentinel;

class PostController extends Controller
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
    public function index()
    {
        if(Sentinel::inRole('administrator')) {
			$posts = Post::get();
		} else {
			$user = Sentinel::getUser();
			$employee = Employee::where('user_id', $user->id)->first();	
			if($employee) {
				$posts = Post::where('employee_id', $employee->id)->get();
			} else {
				$message = session()->flash('error', 'Putanja nije dozvoljena.');

				return redirect()->back()->withFlashMessage($message);
			}
		}
		
		return view('Centaur::posts.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::join('users','users.id','employees.user_id')->select('employees.*','users.first_name','users.last_name')->orderBy('users.last_name','ASC')->get();
		
		return view('Centaur::posts.create', ['employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		
		$data = array(
			'employee_id'  		=> $employee->id,
			'to_employee_id'  	=> $request['to_employee_id'],
			'title'  			=> $request['title'],
			'content'  		=> $request['content']
		);
			
		$post = new Post();
		$post->savePost($data);
		
		session()->flash('success', "Poruka je poslana");
		
        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
		$user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		$comments = Comment::where('post_id',$post->id)->orderBy('created_at','DESC')->get();
		
		$post->updatePost(['status' => '1']);
		
		if(count($comments)>0){
			foreach($comments as $comment) {
				if($post->employee_id == $employee->id && $comment->employee_id == $employee->id ) {
					$comment->updateComment(['status' => '1']);
				}
				if($post->to_employee_id == $employee->id && $comment->employee_id != $employee->id ) {
					$comment->updateComment(['status' => '1']);
				}
				
			}
		}
		
		
		return view('Centaur::posts.show', ['post' => $post, 'comments' => $comments]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
		
		$employees = Employee::join('users','users.id','employees.user_id')->select('employees.*','users.first_name',	'users.last_name')->orderBy('users.last_name','ASC')->get();
		
		return view('Centaur::posts.edit',['post' => $post, 'employees' => $employees]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
        $post = Post::find($id);
		
		$user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		
		$data = array(
			'employee_id'  		=> $employee->id,
			'to_employee_id'  	=> $request['to_employee_id'],
			'title'  			=> $request['title'],
			'content'  			=> $request['content']
		);
			
		$post->updatePost($data);
		
		session()->flash('success', "Poruka je ispravljena");
		
        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
		$post->delete();
		
		$message = session()->flash('success', 'Poruka je obrisana.');
		
		return redirect()->back()->withFlashMessage($message);
    }
	
	public function storeComment(CommentRequest $request)
	{
		$user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		
		$data = array(
			'employee_id'  =>  $employee->id,       
			'post_id'  =>  $request->get('post_id'),
			'content'  =>  $request->get('content'),
			'status'  	=> '0',
		);
		
		$comment = new Comment();
		$comment->saveComment($data);
		
		$message = session()->flash('success', 'You have successfully addad a new comment.');

		return redirect()->back()->withFlashMessage($message);
		//return redirect()->route('admin.posts.index')->withFlashMessage($message);
	}
	
	static function countComment ($post_id) 
	{
		$user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		$post = Post::find( $post_id );
		if($post->employee_id == $employee->id ) {
			$comment_count = Comment::where('post_id', $post->id)->where('employee_id', $employee->id)->where('status',0 )->count();
		} else {
			$comment_count = Comment::where('post_id', $post->id)->where('employee_id', '<>',$employee->id)->where('status',0 )->count();
		}
		

		return $comment_count;
	}
	
	static function countComment_all () 
	{
		$user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		$comments = Comment::get();
		$comment_count = 0;
		if($employee){
			$posts = Post::where('employee_id', $employee->id)->get();
			foreach($posts as $post) {
				$comment_count += $comments->where('post_id',$post->id)->where('employee_id',$employee->id)->where('status',0)->count();
			}
			$posts1 = Post::where('to_employee_id', $employee->id)->get();
			foreach($posts1 as $post1) {
				$comment_count += $comments->where('post_id',$post1->id)->where('employee_id','<>',$employee->id)->where('status',0)->count();
			}
		} 
				
		return $comment_count;
	}
	
	static function countPost ($post_id)
	{
		$user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		
		$post_count = Post::where('id',$post_id)->where('to_employee_id', $employee->id)->where('status',0)->count();
		
		return $post_count;
		
	}
	
	static function countPost_all () 
	{
		$user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		
		$post_count = Post::where('to_employee_id', $employee->id)->where('status',0)->count();
		
		return $post_count;
		
	}
	
}
