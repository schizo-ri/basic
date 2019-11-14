@extends('Centaur::layout')

@section('title', __('basic.posts'))

@section('content')
<div class="index_page posts_index">   
	@php
		use App\Http\Controllers\PostController; 
		use App\Http\Controllers\DashboardController; 
		use App\Models\Employee;
	@endphp
	<aside class="col-4 latest_messages">
		<h1>@lang('basic.latest_messages')
			@if(Sentinel::getUser()->hasAccess(['posts.create']) || in_array('posts.create', $permission_dep))
				<a class="btn btn-primary btn-lg btn-new" href="{{ route('posts.create') }}" rel="modal:open" title="{{ __('basic.new_post')}}">
					<i style="font-size:12px" class="fa">&#xf067;</i>
				</a>
			@endif
			<span class="search_post"></span>
		</h1>
		<div class="input_search search_input">
			<input type="text" id="mySearch" placeholder="{{ __('basic.search')}}" title="Type ... "  autofocus>
		</div>
		<section class="col-md-12 posts">
			<div class="all_post">
				@if(count( $posts) >0)
					@foreach($posts as $post)
						<?php
							if($post->to_employee_id != null) {
								$image_to_employee = DashboardController::profile_image($post->to_employee_id);
							}
							$post_comment = $comments->sortByDesc('created_at')->where('post_id',$post->id)->first(); //zadnji komentar na poruku
						?>
						<article class="main_post panel">
							<button class="tablink" id="{{ $post->id }}" type="button"> 
								@if($post->to_employee_id != null)
									@if(isset($image_employee))
										<img class="radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($image_to_employee)) }}" alt="Profile image"  />
									@else
										<img class="radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
									@endif
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
									<span class="post_time">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->updated_at))->diffForHumans() }} </span>
									<span class="post_text">
											{{	$post_comment['content'] }}
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
	</aside>
	<main class="col-8 index_main">
		<div>
		@if(count( $posts)>0)
			@foreach($posts as $post)
				@php
					$post_comment = PostController::profile($post)['post_comment'];
					$employee = PostController::profile($post)['employee'];
					$user_name = PostController::profile($post)['user_name'];
					if($post->to_employee_id != null) {
						$image_employee = DashboardController::profile_image($post->to_employee_id);
					}
				@endphp
				<section id="{{ '_' . $post->id }}" class="tabcontent" >
					<header class="post_sent">
						<span class="link_back"><span class="curve_arrow_left"></span></span>
						@if($post->to_employee_id != null)
							@if(Sentinel::getUser()->employee['id'] == $post->to_employee_id )
								@if(isset($image_employee) && $image_employee != null)
									<img class="radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($image_employee)) }}" alt="Profile image"  />
								@else
									<img class="radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
								@endif
								<span class="fl_name">
									{{ $post->employee->user['first_name'] . ' ' . $post->employee->user['last_name'] }}
									<span class="" >{{ $post->employee->work['name'] }}</span>
								</span>
								<span class="vacation">Vacation<br><span class="" >...</span></span>
								<span class="phone" >Phone<br><span>{{ $post->employee->mobile }}</span></span>
								<span class="e-mail" >E-mail<br><span>{{ $post->employee->email }}</span></span>
							@else
								<img class="profile_img radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
								<span class="fl_name">
									@if($post->to_employee_id != null)
										{{ $post->to_employee->user['first_name'] . ' ' . $post->to_employee->user['last_name'] }}
									@elseif($post->to_department_id != null)
									
									@endif
									<span class="" >{{ $post->to_employee->work['name'] }}</span>
								</span>
								<span class="vacation">Vacation<br><span class="" >31.12.18 - 07.01.19</span></span>
								<span class="phone" >Phone<br><span>{{ $post->to_employee->mobile }}</span></span>
								<span class="e-mail" >E-mail<br><span>{{ $post->to_employee->email }}</span></span>
							@endif
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
								@if(count($post->comments()) > 0)
									@foreach ($comments->where('post_id', $post->id) as $comment)
										<div class="message">
											<div class="{!! $comment->employee_id != Sentinel::getUser()->employee->id ? 'left' : 'right' !!}">
												@if( $comment->employee_id != Sentinel::getUser()->employee->id)  <p class="comment_empl">{{ $comment->employee->user['first_name'] }}</p>@endif
												<div class="content">
													@if($comment->employee_id != Sentinel::getUser()->employee->id)
														@php
															$image_comment = DashboardController::profile_image($comment->employee_id);
															$user_name_comment =  DashboardController::user_name($comment->employee_id);
														@endphp
														@if($image_comment)
															<img class="profile_img radius50 float_left" src="{{ URL::asset('storage/' . $user_name_comment . '/profile_img/' . end($image_comment)) }}" alt="Profile image"  />
														@else
															<img class="profile_img radius50 float_left" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
														@endif
													@endif
													<p class="comment_content" id="{{ $comment->id }}">{{ $comment['content'] }}
												</div>
											</div>
										</div>
									@endforeach	
								@endif
							</div>
						</div>
					</main>
					<form accept-charset="UTF-8" role="form" method="post" class="form_post {{ ($errors->has('content')) ? 'has-error' : '' }}" id="post-content_{{ $post->id }}" action="{{ route('comment.store') }}"  >
						<input name="content" type="text" class="form-control type_message post-content" id="post-content_{{ $post->id }}" placeholder="Type message..." autocomplete="off" value="{{ old('content')  }}"  />
						<img class="smile" src="{{ URL::asset('icons/smile.png') }}" alt="Profile image"  />
						<input type="hidden" name="post_id" id="post_id" value="{{ $post->id }}"  >
						<input type="hidden" name="user_id" id="user_id" value="{{ Sentinel::getUser()->employee->id }}"  >
						{{ csrf_field() }}
						<input class="" type="submit" value="" title="{{ __('basic.send')}}" >
					</form>
				</section>
			@endforeach
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
		</div>
	</main>
</div>
<script>
	$.getScript( '/../js/posts.js');
	$(function(){
		$('.placeholder').show();
		
	});
</script>
@stop