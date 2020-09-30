@extends('Centaur::layout')

@section('title', __('basic.edit_post'))

@section('content')

<div class="index_page posts_index">   
	@php
		use App\Http\Controllers\PostController; 
		use App\Http\Controllers\DashboardController; 
		use App\Models\Employee;

	@endphp
	<aside class="col-xs-12 col-sm-12 col-md-4 col-lg-4 latest_messages">
		<div>
			<h1>@lang('basic.latest_messages')
				@if(Sentinel::getUser()->hasAccess(['posts.create']) || in_array('posts.create', $permission_dep))
					<a class="btn btn-primary btn-lg btn-new" href="{{ route('posts.create') }}" rel="modal:open" title="{{ __('basic.new_post')}}">
						<i style="font-size:12px" class="fa">&#xf067;</i>
					</a>
				@endif
				<span class="search_post"></span>
			</h1>
			<div class="input_search search_input">
				<input type="text" id="mySearch" placeholder="{{ __('basic.search')}}" title="Type ... " >
			</div>
			<section class="col-md-12 posts">
				<div class="all_post">
					@if(count( $posts) >0)
						@foreach($posts as $post)
							<article class="main_post panel">
								<button class="tablink" id="{{ $post->id }}" type="button"> 
									@if($post->to_employee_id != null)
										<span class="profile_img">
											@if( is_array($post['image_to_employee']) && ! empty($post['image_to_employee']) )
												<img class="radius50" src="{{ URL::asset('storage/' . $post['user_name'] . '/profile_img/' . end($post['image_to_employee']['doc'])) }}" alt="Profile image"  />
											@else
												<img class="radius50" src="{{ URL::asset('img/profile.svg') }}" alt="Profile image"  />
											@endif
										</span>
									@endif
									<span class="post_empl">
										<span class="post_send">
											@if($post->to_employee_id != null)
												@if( Sentinel::getUser()->employee->id == $post->to_employee_id )
													{{ $post->employee->user['first_name'] . ' ' . $post->employee->user['last_name'] }}
												@else
													@if($post->to_employee_id != null)
														{{ $post->to_employee->user['first_name'] . ' ' . $post->to_employee->user['last_name'] }}
													
													@endif
												@endif
											@endif
											@if( $post->to_department_id != null )
												{{ $post->to_department['name'] }}
											@endif
										</span>
										@if(PostController::countComment($post->id) > 0)<span class="count_coments">{{ PostController::countComment($post->id) }}</span>@endif
										<span class="post_time">{{ date('d.m. H:i',strtotime( $post->updated_at )) }}</span>
										<span class="post_text">											
												{{	$post['post_comment']->content }}
										</span>
									</span>
								</button>
							
							</article>
						@endforeach
					@else 
						<div class="placeholder">
							<img class="" src="{{ URL::asset('icons/placeholder_noticeadd.png') }}" alt="Placeholder image" />
							<p>@lang('basic.no_message_1') 
								<label type="text" class="add_new" rel="modal:open" >
									<i style="font-size:11px" class="fa">&#xf067;</i>
								</label>
								@lang('basic.on_button')</p>
							
						</div>
					@endif
				</div>
			</section>
		</div>
	</aside>
	<main class="col-xs-12 col-sm-12 col-md-8 col-lg-8 index_main">
		<section class="section_post">
			@if(count( $posts ) > 0)
				{{-- @foreach($posts as $post)  --}}
				
					<section id="{{ '_' . $post->id }}" class="tabcontent" >
						<header class="post_sent">
							<span class="link_back"><span class="curve_arrow_left"></span></span>
							@if($post->to_employee_id != null)
								<span class="post_img_prof">
									@if( is_array($post['image_employee']['docs']) && ! empty($post['image_employee']['docs']) )
										<img class="radius50" src="{{ URL::asset('storage/' . $post['user_name']['docs'] . '/profile_img/' . end($post['image_employee']['docs'])) }}" alt="Profile image" />
									@else
										<img class="radius50" src="{{ URL::asset('img/profile.svg') }}" alt="Profile image"  />
									@endif
								</span>
								<span class="fl_name">
									{{ $post['employee']->user['first_name'] . ' ' .  $post['employee']->user['last_name'] }}
									<span class="" >{{  $post['employee']->work['name'] }}</span>
								</span>
								<span class="vacation">@lang('basic.vacation')<br><span class="" >...</span></span>
								<span class="phone" >@lang('basic.phone')<br><span>{{  $post['employee']->mobile }}</span></span>
								<span class="e-mail" >E-mail<br><span>{{  $post['employee']->email }}</span></span>
							@endif
							@if($post->to_department_id != null)
								<span class="fl_name">
									{{ $post->to_department['name'] }}
								</span>
							@endif
						</header>
						<main class="comments" >
							<div class="mess_comm">
								<div class="refresh {{ '_' . $post->id }}">
									@if(count($post->comments ) > 0)
										@foreach ($post->comments as $comment)
											<div class="message">
												<div class="{!! $comment->employee_id != Sentinel::getUser()->employee->id ? 'left' : 'right' !!}">
													<p class="comment_empl">
														@php
															$next_comment = PostController::previous($comment->id, $post->id);
															$next_empl = $next_comment['employee_id'];
														@endphp
														@if( $next_empl != $comment->employee_id && $comment->employee_id != Sentinel::getUser()->employee->id){{ $comment->employee->user['first_name'] }} | @endif 
														<small>{{ Carbon\Carbon::parse($comment->created_at)->diffForHumans()  }}</small>
													</p>
													<div class="content">
														@if($comment->employee_id != Sentinel::getUser()->employee->id)
															@php
																$image_comment = DashboardController::profile_image($comment->employee_id);
																$user_name_comment =  DashboardController::user_name($comment->employee_id);
															@endphp
															<span class="profile_img">
															@if($image_comment)
																<img class="radius50 float_left" src="{{ URL::asset('storage/' . $user_name_comment . '/profile_img/' . end($image_comment)) }}" alt="Profile image"  />
															@else
																<img class="profile_img radius50 float_left" src="{{ URL::asset('img/profile.svg') }}" alt="Profile image"  />
															@endif
															</span>
														@endif
														<pre class="comment_content" id="{{ $comment->id }}">{{ $comment['content'] }}</pre>
													</div>
												</div>
											</div>
										@endforeach	
									@endif
								</div>
							</div>
						</main>
						<form accept-charset="UTF-8" role="form" method="post" class="form_post {{ ($errors->has('content')) ? 'has-error' : '' }}" id="form_{{ $post->id }}" action="{{ route('comment.store') }}"  >
							<textarea name="content" id="post-content_{{ $post->id }}" type="text" class="form-control type_message post-content" rows="10" cols="30" placeholder="Type message..." autocomplete="off" onkeypress="onKeyClick();">{{ old('content')  }}</textarea>

							
							<img class="smile" src="{{ URL::asset('icons/smile.png') }}" alt="Profile image"  />
							<input type="hidden" name="post_id" value="{{ $post->id }}"  >
							<input type="hidden" name="user_id"  value="{{ Sentinel::getUser()->employee->id }}"  >
							{{ csrf_field() }}
							<input class="" type="submit" value="" title="{{ __('basic.send')}}" >
						</form>
					</section>
				{{-- @endforeach --}}
			@else 
				<div class="placeholder">
					<img class="" src="{{ URL::asset('icons/placeholder_message.png') }}" alt="Placeholder image" />
					<p>@lang('basic.no_message') 
						<label type="text" class="add_new" rel="modal:open" >
							<i style="font-size:11px" class="fa">&#xf067;</i>
						</label>
						@lang('basic.no_message_2')
					</p>
				</div>
			@endif	
		</section> 
	</main>
</div>
<span hidden id="employee_id">{{ Sentinel::getUser()->employee->id }}</span>
<script>
	/* $.getScript( '/../js/posts.js'); */
	$(function(){
		$('.placeholder').show();
	});
</script> 
<!-- <script>
   window.laravel_echo_port='{{env("LARAVEL_ECHO_PORT")}}';
	</script> -->
<!-- 	<script type="text/javascript" src="{{ url('/js/socket.io.js') }}"></script> -->
    <!-- <script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_PORT')}}/socket.io/socket.io.js"></script>
    <script src="{{ url('/js/laravel-echo-setup.js') }}" type="text/javascript"></script>
      
    <script type="text/javascript">
        var i = 0;
        window.Echo.channel('user-channel')
         .listen('.MessageSendEvent', (data) => {
            i++;
            $("#notification").append('<div class="alert alert-success">'+i+'.'+data.title+'</div>');
        });
    </script> -->
@stop