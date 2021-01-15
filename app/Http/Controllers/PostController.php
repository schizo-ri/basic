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
use App\Mail\CommentMail;
use Illuminate\Support\Facades\Mail;
use Log;

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

		$permission_dep = array();

		$posts = Post::where('employee_id',$empl->id)->orWhere('to_employee_id', $empl->id)->orderBy('updated_at','DESC')->with('comments')->get();
		if($empl->hasWorkingRecord) {
			foreach ($empl->hasEmployeeDepartmen as $employeeDepartmen) {
				if($employeeDepartmen->department) {
					$posts = $posts->merge( Post::where('to_department_id', $employeeDepartmen->department->id)->orderBy('updated_at','DESC')->with('comments')->get());

				}
			}
		} 
		$posts = $posts->sortByDesc('updated_at');

		if($empl) {
			if(isset($_GET['id'])) { 
				$post = $posts->where('id', $_GET['id'])->first();
				
				if($post) {
					$comments_post = $post->comments;
					
					if(count($comments_post) > 0){
						foreach($comments_post as $comment) {
							if( $comment->to_employee_id == $empl->id && $comment->status == 0 ) {
								$comment->updateComment(['status' => '1']);
							}
						}
						$show_alert_to_employee = $post->employee_id;	
						event(new MessageSend( __('basic.new_message'), $comment, $show_alert_to_employee ));
					}
				}
			} else {
				$post = $posts->first();
			}
			foreach ($posts as $post) {
				$profile = PostController::profile($post);
				$post->post_comment = $profile['post_comment'];//zadnji komentar na poruku
				$post->employee = $profile['employee'];
				$post->user_name = $profile['user_name']; // ime djelatnika kojem je poslana poruka a nije user 
				$post->image_to_employee =  $profile['docs']; // profilna slika
				$post->countComment = PostController::countComment($post);
			}
			
			
		} else {
				$message = session()->flash('error',  __('ctrl.path_not_allow'));
				return redirect()->back()->withFlashMessage($message);
		}
		
		return view('Centaur::posts.index', ['posts' => $posts,'selected_post' => $post,'employee' => $empl,'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$employees = Employee::employees_lastNameASC();
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
		/* $posts = Post::where('employee_id', $employee->id)->orWhere('to_employee_id', $employee->id)->get(); */
		
		if($request['to_employee_id'] != null ) {	
			$posts = Post::where('employee_id', $employee->id)->where('to_employee_id', $request['to_employee_id'])->first();
			$posts2 = Post::where('employee_id', $request['to_employee_id'] )->where('to_employee_id', $employee->id)->first();
			$data = array(
				'employee_id'  		=> $employee->id,
				'to_employee_id'  	=> $request['to_employee_id'],
				'content'  			=> $request['content']
			);
			
			if( $posts ) {
				$posts->updatePost($data);
			} elseif( $posts2 ) {
				$posts2->updatePost($data);
			} else {
				$post = new Post();
				$post->savePost($data);
			}

			if($post->employee_id == $employee->id) {
				$to_employee = $post->to_employee_id;
			} else if( $post->to_employee_id == $employee->id ) {
				$to_employee = $post->employee_id;
			} else {
				$to_employee = null;
			}

			$data1 = array(
				'employee_id'   =>  $employee->id,
				'to_department_id'  => $post->to_department_id != null ? $post->to_department_id : null,
				'to_employee_id'  	=> $to_employee,
				'post_id'  		=>  $post->id,
				'content'  		=>  $request['content'],
				'status'  		=> '0',
			);
			
			$comment = new Comment();
			$comment->saveComment($data1);

			$last_comment = Comment::lastComment($comment->employee_id, $comment->to_employee_id );
			if( $last_comment ) {
				$datetime_last_comment = new DateTime($last_comment->created_at);
				$now = new DateTime();
				$diff = $datetime_last_comment->diff($now);
			}
			
			if( ! $last_comment || ( isset( $diff ) && ($diff->i > 5 || $diff->h > 0 || $diff->d > 0 || $diff->y > 0) )) {
				$send_to = $comment->toEmployee->email;
				/* 	$send_to = 'jelena.juras@duplico.hr'; */
				Mail::to($send_to)->send(new CommentMail($comment));  
			}

			$show_alert_to_employee =  $to_employee;

			event(new MessageSend( __('basic.new_message'), $comment, $show_alert_to_employee ));

		}
		
		if($request['to_department_id'] != null) {
			$data = array(
				'employee_id'  		=> $employee->id,
				'to_department_id' 	=> $request['to_department_id'],
				'content'  			=> $request['content']
			);
			$post = Post::find( $request['to_department_id']);
			if($post) {
				$post->updatePost($data);
			} else {
				$post = new Post();
				$post->savePost($data);
			} 

			$department = Department::find( $post->to_department_id );
			$employeeDepartments = $department->hasEmployeeDepartment;

			foreach ($employeeDepartments as $employeeDepartment) {
				$to_employee = $employeeDepartment->employee;
				if($to_employee->checkout == null) {
					$data1 = array(
						'employee_id'    => $employee->id,
						'to_employee_id' => $to_employee->id,
						'post_id'  		=>  $post->id,
						'content'  		=>  $request['content'],
						'status'  		=> '0',
					);
					
					$comment = new Comment();
					$comment->saveComment($data1);
	
					$last_comment = Comment::lastComment($comment->employee_id, $comment->to_employee_id );
					if( $last_comment ) {
						$datetime_last_comment = new DateTime($last_comment->created_at);
						$now = new DateTime();
						$diff = $datetime_last_comment->diff($now);
					}
					
					if( ! $last_comment || ( isset( $diff ) && ($diff->i > 5 || $diff->h > 0 || $diff->d > 0 || $diff->y > 0) )) {
						$send_to = $comment->toEmployee->email;
						/* 	$send_to = 'jelena.juras@duplico.hr'; */
						Mail::to($send_to)->send(new CommentMail($comment));  
					}

					$show_alert_to_employee =  $comment->to_employee_id;
	
					event(new MessageSend( __('basic.new_message'), $comment, $show_alert_to_employee ));
				}
			}	

			/* 
			$works = $department->hasWorks;
			
			if( $department->level1 == 0) {
				foreach ($departments->where('level2', $department->id) as $department_1) {
					$works = $works->merge($department_1->hasWorks);
					foreach ($departments->where('level2', $department_1->id) as $department_2) {
						$works = $works->merge($department_2->hasWorks);
					}
				}
			} elseif ($department->level1 == 1) {
				foreach ($departments->where('level2', $department->id) as $department_2) {
					$works = $works->merge($department_2->hasWorks);
				}
			}  */
				
			/* foreach ($works as $work) {
				$workers = $work->workers;
				foreach ($workers as $worker) {
					$data1 = array(
						'employee_id'    => $employee->id,
						'to_employee_id' => $worker->id,
						'post_id'  		=>  $post->id,
						'content'  		=>  $request['content'],
						'status'  		=> '0',
					);
					
					$comment = new Comment();
					$comment->saveComment($data1);

					$show_alert_to_employee =  $comment->to_employee_id;

					event(new MessageSend( __('basic.new_message'), $comment, $show_alert_to_employee ));

				}
			} */
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
		$employee = $user->employee;
		$comments = Comment::where('post_id',$post->id)->orderBy('created_at','DESC')->get();
		
		if($post->employee_id != $employee->id) {
			$post->updatePost(['status' => '1']);
		}

		if(count($comments) > 0){
			foreach($comments as $comment) {
				if($comment->employee_id != $employee->id && $comment->status == 0) {
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
		$employee = $user->employee;
		
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
		$post = Post::find( $request->get('post_id') );
		$to_employees = array();
		$to_employee = null;

		if( $post->to_employee_id ) {
			if($post->employee_id == $employee->id) { 
				$to_employee = $post->to_employee_id;
			} else if( $post->to_employee_id == $employee->id ) {
				$to_employee = $post->employee_id;
			}
		} else if ( $post->to_department_id ) {
			$department = Department::where('id',$post->to_department_id )->with('hasWorks')->first();
			$works = $department->hasWorks;
			
			foreach ($works as $work) {
				$workers = $work->workers;
				foreach ($workers as $worker) {
					array_push( $to_employees, $worker->employee_id);
				}
			}
		}

		$data = array(
			'employee_id'   => $employee->id,
			'to_department_id'  => $post->to_department_id != null ? $post->to_department_id : null,
			'to_employee_id'  	=> $to_employee,
			'post_id'  		=>  $request->get('post_id'),
			'content'  		=>  $request->get('content'),
			'status'  		=> '0',
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

		$last_comment = Comment::lastComment($comment->employee_id, $comment->to_employee_id );
		
		if( $last_comment ) {
			$datetime_last_comment = new DateTime($last_comment->created_at);
			$now = new DateTime();
			$diff = $datetime_last_comment->diff($now);
		}
		
		if( ! $last_comment || ( isset( $diff ) && ($diff->i > 5 || $diff->h > 0 || $diff->d > 0 || $diff->y > 0) )) {
			$send_to = $comment->toEmployee->email;
			/* 	$send_to = 'jelena.juras@duplico.hr'; */
			Mail::to($send_to)->send(new CommentMail($comment));  
		}
 		
		if($post->to_employee_id) {
			event(new MessageSend( __('basic.new_message'), $comment, $show_alert_to_employee ));
		} else if($post->to_department_id) {
			foreach ($to_employees as $empl) {
				event(new MessageSend( __('basic.new_message'), $comment, $empl ));
			}
			event(new MessageSend( __('basic.new_message'), $comment, $post->employee_id ));
		}

		$message = session()->flash('success', __('basic.sent_message'));

		return redirect()->back()->withFlashMessage($message);
		//return redirect()->route('admin.posts.index')->withFlashMessage($message);
	}
	
	static function countComment ($post) 
	{
		$user = Sentinel::getUser();
		$employee = $user->employee;
	
		$comments = $post->comments;
		$comment_count = 0;
		if( $employee ){
			$comment_count = $comments->where('to_employee_id', $employee->id)->where('status', 0)->count();
			/* $comment_count += $comments->where('to_employee_id', null)->where('status', 0)->count(); */
		}
		
		return $comment_count;
	}
	
	static function countComment_all () 
	{
		$comment_count = 0;
		
		if( Sentinel::getUser() ) {
			$employee = Sentinel::getUser()->employee;
			if( $employee ){
				if($employee->work) {
					$posts = Post::where('employee_id', $employee->id)->orWhere('to_employee_id', $employee->id)->orWhere('to_department_id',$employee->work->department->id)->get();
				} else {
					$posts = Post::where('employee_id', $employee->id)->orWhere('to_employee_id', $employee->id)->get();
	
				}
				foreach($posts as $post) {
					$count = $post->comments->where('to_employee_id', $employee->id)->where('status',0)->count();
					$comment_count += $count;
				}
			} 
	
		}
		
		
		return $comment_count;
	}
	
	static function countPost ($post_id)
	{
		$user = Sentinel::getUser();
		$post_count = 0;
		$employee = $user->employee;
		if($employee){
			$post_count = Post::where('id',$post_id)->where('to_employee_id', $employee->id)->where('status',0)->count();
		}
		return $post_count;
		
	}
	
	static function countPost_all () 
	{
		$user = Sentinel::getUser();
		if(isset($user)) {
			$employee = $user->employee;
		}

		if(isset($employee)){
			$post_count = Post::where('to_employee_id', $employee->id)->where('status',0)->count();
		} else {
			$post_count = 0;
		}

		return $post_count;
	}

	public static function profile($post) 
	{
		$docs = '';
		$user_name = '';
		
		$comments = $post->comments;
		$post_comment = $comments->where('post_id',$post->id)->sortByDesc('created_at')->first(); //zadnji komentar na poruku
		$employee = Sentinel::getUser()->employee;  // prijavljeni djelatnik

		if($post->to_employee_id != null) {
			if( Sentinel::getUser()->employee->id == $post->to_employee_id ) {
				$empl = $post->employee;
			} else {
				$empl = $post->to_employee;
			}
			if($empl) {
				$user_name = DashboardController::user_name($empl->id);
				$docs = DashboardController::profile_image($empl->id);
			} 
		} else {
			$empl = $post->employee;
			
			$user_name = $post->to_department ? $post->to_department->name : null;
		}
		
		if( $docs ) {
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

	public static function setCommentAsRead ( $id ) 
	{
		$empl = Sentinel::getUser()->employee;
		$post = Post::where('id', $id)->with('comments')->first();
		

		/* 	if( $post->to_department_id != null && $post->employee_id == $empl->id) {  // ako je poruka za odjel i pošiljatelj je prijavljen djelatnik -> poruka je pročitana
			$post->updatePost(['status' => '1']);
		} */
		if( $post->to_department_id != null && $post->employee_id == $empl->id ) {
			$comments_post = $post->comments->where('to_department_id', $post->to_department_id);
		} else {
			$comments_post = $post->comments->where('to_employee_id', $empl->id);
		}
		if(count($comments_post) > 0){
			foreach($comments_post as $comment) {
				if($comment->to_employee_id == $empl->id || $post->to_department_id != null && $post->employee_id == $empl->id ) {
					if($comment->status == 0) {
						$comment->updateComment(['status' => '1']);
						$show_alert_to_employee = $post->employee_id;
	
						event(new MessageSend( __('basic.new_message'), $comment, $show_alert_to_employee ));
					}
				}
			}
		}
		

		return "comments_post:". $comments_post;
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
