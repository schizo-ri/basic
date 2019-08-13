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
		$empl = Sentinel::getUser()->employee;
		$comments = Comment::orderBy('created_at','ASC')->get();
		$permission_dep = array();
		if($empl) {
			if(Sentinel::inRole('administrator')) {
				$posts = Post::get();
			} else {
				$posts = Post::where('employee_id', $empl->id)->orWhere('to_employee_id', $empl->id)->get();
			}
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
			
		} else {
				$message = session()->flash('error', 'Putanja nije dozvoljena.');
				return redirect()->back()->withFlashMessage($message);
		}
		
		return view('Centaur::posts.index', ['posts' => $posts,'permission_dep' => $permission_dep,'comments' => $comments]);
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
			'content'  			=> $request['content']
		);

		$posts = Post::where('employee_id', $employee->id)->orWhere('to_employee_id', $employee->id)->get();
		
		if($posts->where('to_employee_id', $request['to_employee_id'])->first()) {
			$post = $posts->where('to_employee_id', $request['to_employee_id'])->first();
			$post->updatePost($data);
		} elseif($posts->where('employee_id', $request['to_employee_id'] )->first()) {
			$post = $posts->where('employee_id', $request['to_employee_id'] )->first();
			$post->updatePost($data);
		} else {
			$post = new Post();
			$post->savePost($data);
		}
		
		$data1 = array(
			'employee_id'   =>  $employee->id,
			'post_id'  		=>  $post->id,
			'content'  		=>  $request['content'],
			'status'  		=> '0',
		);
		
		$comment = new Comment();
		$comment->saveComment($data1);

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
		
		if($post->employee_id != $employee->id) {
			$post->updatePost(['status' => '1']);
		}

		if(count($comments) > 0){
			foreach($comments as $comment) {
				if($comment->employee_id != $employee->id ) {
					$comment->updateComment(['status' => '1']);
				}
			}
		}
		return redirect()->back();
//		return view('Centaur::posts.show', ['post' => $post, 'comments' => $comments]);
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
		//	'title'  			=> $request['title'],
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
		$employee = Sentinel::getUser()->employee;
		
		$data = array(
			'employee_id'  =>  $employee->id,       
			'post_id'  =>  $request->get('post_id'),
			'content'  =>  $request->get('content'),
			'status'  	=> '0',
		);
		
		$comment = new Comment();
		$comment->saveComment($data);
		
		$data1 = array('content'  =>  $request->get('content'));
		$post = Post::find($request->get('post_id'));
		
		$post->updatePost($data1);

		$message = session()->flash('success', 'You have successfully addad a new comment.');

		return redirect()->back()->withFlashMessage($message);
		//return redirect()->route('admin.posts.index')->withFlashMessage($message);
	}
	
	static function countComment ($post_id) 
	{
		$user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		
		$post = Post::find( $post_id );
		$comments =  Comment::where('post_id', $post->id)->get();

		$comment_count = 0;
		if($employee){
			$comment_count = $comments->where('employee_id', '<>', $employee->id)->where('status', 0)->count();
		}

		return $comment_count;
	}
	
	static function countComment_all () 
	{
		$user = Sentinel::getUser();
		if(isset($user)) {
			$employee = Employee::where('user_id', $user->id)->first();
		}
	
		$comments = Comment::get();
		$comment_count = 0;
	
		if(isset($employee)){
			
			$posts = Post::where('employee_id', $employee->id)->orWhere('to_employee_id', $employee->id)->get();
			foreach($posts as $post) {
				$count = $comments->where('post_id',$post->id)->where('employee_id','<>', $employee->id)->where('status',0)->count();
				$comment_count += $count;
			}
		} 

		return $comment_count;
	}
	
	static function countPost ($post_id)
	{
		$user = Sentinel::getUser();
		$post_count = 0;
		$employee = Employee::where('user_id', $user->id)->first();
		if($employee){
			$post_count = Post::where('id',$post_id)->where('to_employee_id', $employee->id)->where('status',0)->count();
		}
		return $post_count;
		
	}
	
	static function countPost_all () 
	{
		$user = Sentinel::getUser();
		if(isset($user)) {
			$employee = Employee::where('user_id', $user->id)->first();
		}

		if(isset($employee)){
			$post_count = Post::where('to_employee_id', $employee->id)->where('status',0)->count();
		} else {
			$post_count = 0;
		}

		return $post_count;
	}

	public static function profile($post) {
		$docs = '';
		$comments = Comment::orderBy('created_at','DESC')->get();
		$employee = Sentinel::getUser()->employee;

		if($post->employee_id == $employee->id ) {
			$empl = Employee::where('id',$post->to_employee_id)->first();
		} else {
			$empl = Employee::where('id',$post->employee_id)->first();
		}
		
		$user_name = explode('.',strstr($empl->email,'@',true));
		if(count($user_name) == 2) {
			$user_name = $user_name[1] . '_' . $user_name[0];
		} else {
			$user_name = $user_name[0];
		}

		$path = 'storage/' . $user_name . "/profile_img/";
		if(file_exists($path)){
			$docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
		}
		$post_comment = $comments->where('post_id',$post->id)->first(); //zadnji komentar na poruku

		$podaci = array(
			'employee'  	=>  $empl,  
			'post_comment'  =>  $post_comment,  
			'docs'  		=>  $docs,  
			'user_name'  	=>  $user_name,  
		);

		return $podaci;

	}
}
