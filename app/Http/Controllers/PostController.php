<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Employee;
use App\Models\Department;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Work;
use Sentinel;
use DateTime;
use App\Events\MessageSend;
use Pusher;

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
    public function index(Request $request)
    {		
		$empl = Sentinel::getUser()->employee;
		$comments = Comment::orderBy('created_at','ASC')->get();
	
		if(isset($_GET['id'])) {
			$post = Post::where('id',$_GET['id'])->first();
			if($post) {
				$comments_post = Comment::where('post_id',$post->id)->get();

				if($post->employee_id != $empl->id) {
					$post->updatePost(['status' => '1']);
				}
				if(count($comments_post) > 0){
					foreach($comments_post as $comment) {
						if($comment->employee_id != $empl->id ) {
							$comment->updateComment(['status' => '1']);
						}
					}
				}
			}
		}
		$permission_dep = array();

		if($empl) {
			
			$posts = Post::where('employee_id', $empl->id)->orWhere('to_employee_id', $empl->id)->orderBy('updated_at','DESC')->get();

			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
			
		} else {
				$message = session()->flash('error',  __('ctrl.path_not_allow'));
				return redirect()->back()->withFlashMessage($message);
		}
		
		return view('Centaur::posts.index', ['posts' => $posts,'permission_dep' => $permission_dep,'comments' => $comments]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$employees = Employee::join('users','users.id','employees.user_id')->select('employees.*','users.first_name','users.last_name')->orderBy('users.last_name','ASC')->where('employees.id','<>',1)->where('employees.checkout',null)->get();
		$departments = Department::orderBy('name','ASC')->get();
		if(isset($request['employee_publish'])) {
			$employee_publish = Employee::find($request['employee_publish']);
			return view('Centaur::posts.create', ['employees' => $employees, 'departments' => $departments, 'employee_publish' => $employee_publish]);
		} else {
			return view('Centaur::posts.create', ['employees' => $employees, 'departments' => $departments]);
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
		$employee = Sentinel::getUser()->employee;
		$posts = Post::where('employee_id', $employee->id)->orWhere('to_employee_id', $employee->id)->get();
		
		if($request['to_employee_id'] != null ) {	
			$data = array(
				'employee_id'  		=> $employee->id,
				'to_employee_id'  	=> $request['to_employee_id'],
				'content'  			=> $request['content']
			);
			
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

		}
		if($request['to_department_id'] != null) {
			// šalje samo na voditelja odjela
			/* $data += ['to_department_id'  => $request['to_department_id']];
			$post = Post::find( $request['to_department_id']);
			if($post) {
				$post->updatePost($data);
			} else {
				$post = new Post();
				$post->savePost($data);
			} */

			// šalje na sve djelatnike odjela
			$department = Department::find($request['to_department_id']);
			$works = Work::where('department_id',$department->id)->get();
			
			foreach ($works as $work) {
				$workers = $work->workers;
				foreach ($workers as $worker) {
					$data = array(
						'employee_id'  		=> $employee->id,
						'to_employee_id' => $worker->id,
						'content'  			=> $request['content']
					);

					if($posts->where('to_employee_id', $worker->id)->first()) {
						$post = $posts->where('to_employee_id', $worker->id)->first();
						$post->updatePost($data);
					} elseif($posts->where('employee_id', $worker->id)->first()) {
						$post = $posts->where('employee_id', $worker->id)->first();
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
				}
			}
		}

		session()->flash('success', "Poruka je poslana");
		
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
		
		$employees = Employee::join('users','users.id','employees.user_id')->select('employees.*','users.first_name', 'users.last_name')->orderBy('users.last_name','ASC')->where('employees.id','<>',1)->where('employees.checkout',null)->get();
	
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
			'to_department_id'  	=> $request['to_department_id'],
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
		
		$message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
	
	public function storeComment(Request $request)
	{
		$employee = Sentinel::getUser()->employee;
		
		$data = array(
			'employee_id'  => $employee->id,       
			'post_id'  =>  $request->get('post_id'),
			'content'  =>  $request->get('content'),
			'status'  	=> '0',
		);
		
		$comment = new Comment();
		$comment->saveComment($data);
		
		$data1 = array('content'  => $request->get('content'));
		$post = Post::find($request->get('post_id'));
		
		$post->updatePost($data1);
	
		if( $post->employee_id == $comment->employee_id) {
			$show_alert_to_employee = $post->to_employee_id;
		} else if ($post->to_employee_id == $comment->employee_id) {
			$show_alert_to_employee = $post->employee_id;
		} else {
			$show_alert_to_employee = null;
		}
		
		event(new MessageSend( __('basic.new_message'), $comment, $show_alert_to_employee ));

		$message = session()->flash('success', __('basic.sent_message'));

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
		$user_name = '';
		
		$comments = Comment::orderBy('created_at','DESC')->get();
		$post_comment = $comments->where('post_id',$post->id)->first(); //zadnji komentar na poruku
		$employee = Sentinel::getUser()->employee;  // prijavljeni djelatnik

		if($post->to_employee_id != null) {
			if( Sentinel::getUser()->employee->id == $post->to_employee_id ) {
				$empl = $post->employee;
				$user_name = explode('.',strstr($empl->email,'@',true));
			} else {
				$empl = $post->to_employee;
				$user_name = explode('.',strstr($empl->email,'@',true));
			}
		
			if(count($user_name) == 2) {
				$user_name = $user_name[1] . '_' . $user_name[0];
			} else {
				$user_name = $user_name[0];
			}
	
			$path = 'storage/' . $user_name . "/profile_img/";
			if(file_exists($path)){
				$docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
			}
		} else {
			$empl = Employee::where('id', $post->employee_id)->first();
			$user_name = Department::where('id', $post->to_department_id)->first()->name;
		}
		
		if($docs) {
			$docs = end($docs);
		}
		
		$podaci = array(
			'employee'  	=>  $empl,  
			'post_comment'  =>  $post_comment,  
			'docs'  		=>  $docs,
			'user_name'  	=>  $user_name,  
		);
	
		return $podaci;
	}

	public function updated($id, $year, $month, $day, $hour, $minute, $second)
	{
		$post = Post::findOrFail($id);
		$date = new DateTime($year . '-' .  $month . '-' . $day . ' ' . $hour . ':' . $minute . ':' . $second);
		$date->modify('-1 seconds');

		if ($post && ($post->updated_at > $date) ) {
			PostController::setCommentAsRead($id, true);
			
			return 'true';
		} 
		return 'false';
	}

	public static function setCommentAsRead ($id) 
	{
		$empl = Sentinel::getUser()->employee;
		$post = Post::where('id', $id)->first();
		$comments_post = Comment::where('post_id', $post->id)->get();

		if($post->employee_id != $empl->id) {
			$post->updatePost(['status' => '1']);
		}

		if(count($comments_post) > 0){
			foreach($comments_post as $comment) {
				if($comment->employee_id != $empl->id ) {
					$comment->updateComment(['status' => '1']);
				}
			}
		}
		return $comments_post;
	}

	public static function previous($id, $post_id) {
		return Comment::where('id', '<', $id)->where('post_id', $post_id)->orderBy('id','desc')->first();
	}

	 /**
     * Ship the given order.
     *
     * @param  int  $orderId
     * @return Response
     */
   /*  public function send($postId)
    {
        $post = Post::findOrFail($postId);

        // Order shipment logic...

        event(new SendMessage($post));
	} */
	
}
