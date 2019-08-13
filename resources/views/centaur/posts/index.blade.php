@extends('Centaur::layout')

@section('title', __('basic.posts'))
<link rel="stylesheet" href="{{ URL::asset('css/dashboard.css') }}"/>
<link rel="stylesheet" href="{{ URL::asset('css/index.css') }}"/>
@section('content')

<div class="index_page posts_index">   
	@php
		use App\Http\Controllers\PostController; 
		use App\Models\Employee;
	@endphp
	<aside class="col-4 latest_messages">
		<h1>Latest messages
			@if(Sentinel::getUser()->hasAccess(['posts.create']) || in_array('posts.create', $permission_dep))
				<a class="btn btn-primary btn-lg btn-new" href="{{ route('posts.create') }}">
						<i style="font-size:11px" class="fa">&#xf067;</i>
				</a>
			@endif
			<span class="search_post"></span>
			
		</h1>
		<input type="text" id="mySearch" placeholder="{{ __('basic.search')}}" title="Type ... " class="input_search search_input" autofocus>
		<section class="col-md-12 posts">
			<div class="all_post">
				@if(count( $posts))
					@foreach($posts as $post)
						<?php
							$docs = '';
							if($post->employee_id == Sentinel::getUser()->employee->id ) {
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
						?>
						<article class="main_post panel">
							<button class="tablink" id="{{ $post->id }}"  type="button"> 
								@if($docs)
									<img class="radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($docs)) }}" alt="Profile image"  />
								@else
									<img class="radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
								@endif
								<span class="post_empl">
									<span class="post_send">
										@if(Sentinel::getUser()->employee->id == $post->to_employee_id )
											{{ $post->employee->user['first_name'] . ' ' . $post->employee->user['last_name'] }}
										@else
											{{ $post->to_employee->user['first_name'] . ' ' . $post->to_employee->user['last_name'] }}
										@endif
									</span>
									@if(PostController::countComment($post->id) > 0)<span class="count_coments">{{ PostController::countComment($post->id) }}</span>@endif
									<span class="post_time">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($post->updated_at))->diffForHumans() }} </span>
									<span class="post_text">
											{{	$post_comment->content }}
									</span>
								</span>
								
							</button>
						</article>
					@endforeach
				@endif
			</div>
		</section>
	</aside>
	<main class="col-8 index_main">
		@if(count( $posts))
			@foreach($posts as $post)
			@php
				$post_comment = PostController::profile($post)['post_comment'];
				$docs = PostController::profile($post)['docs'];
				$employee = PostController::profile($post)['employee'];
				$user_name = PostController::profile($post)['user_name'];
			@endphp
				<section id="{{ '_' . $post->id }}" class="tabcontent" >
					<header class="post_sent">
						@if(Sentinel::getUser()->employee->id == $post->to_employee_id )
							<img class="profile_img radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
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
								{{ $post->to_employee->user['first_name'] . ' ' . $post->to_employee->user['last_name'] }}
								<span class="" >{{ $post->to_employee->work['name'] }}</span>
							</span>
							<span class="vacation">Vacation<br><span class="" >31.12.18 - 07.01.19</span></span>
							<span class="phone" >Phone<br><span>{{ $post->to_employee->mobile }}</span></span>
							<span class="e-mail" >E-mail<br><span>{{ $post->to_employee->email }}</span></span>
						@endif

					</header>
					<main class="comments" >
						<div class="mess_comm">
							<div class="refresh {{ '_' . $post->id }}">
								@if(count($post->comments()) > 0)
									@foreach ($comments->where('post_id', $post->id) as $comment)
										<div class="message">
											<div class="{!! $comment->employee_id != Sentinel::getUser()->employee->id ? 'left' : 'right' !!}">
												@if( $comment->employee_id != Sentinel::getUser()->employee->id)  <p class="comment_empl">{{ $employee->user['first_name'] }}</p>@endif
												<div class="content">
													@if($comment->employee_id != Sentinel::getUser()->employee->id)
														@if($docs)
															<img class="profile_img radius50 float_left" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($docs)) }}" alt="Profile image"  />
														@else
															<img class="profile_img radius50 float_left" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
														@endif
													@endif
													<p class="comment_content" id="{{ $comment->id }}">{{ $comment->content }}
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
						<input class="" type="submit" value="">

					</form>
				</section>
			@endforeach
		@else 
			<header class="post_sent">
				<p>	Nema poruka! </p>
			</header>
		@endif	
	</main>
	<script>
		$( '.tablink' ).on( "click", function () {
			var tab_id = '_' + $( this ).attr('id');
			var post_id = $( this ).attr('id');
			var i, tabcontent, tablinks, input;
			input = 'post-content' + tab_id;

			$(".tabcontent").each(function() {
				if($(this).attr('id') != tab_id ) {
					$(this).hide();
				} else {
					$(this).show();
					var mess_comm_height = $(this).find('.mess_comm').height();
					var refresh_height = $(this).find('.refresh').height();
					if(refresh_height < mess_comm_height ) {
						$(this).find('.refresh').css({"position": "absolute", "bottom": "0", "width": "100%"});
					}
				}
			});

			$("#"+tab_id + ' .refresh').find('.message').last().attr("id","zadnji" + tab_id );
			
			location.href='#zadnji'+tab_id;
			
//			$("#post-content"+tab_id).focus();

			function loadlink(tab_id){
				var url = location.href;  // http://localhost:8000/admin/posts/index#_172
				var div_id = tab_id;

				$.ajaxSetup ({
					cache: false
				});
				
				var ajax_load = "<img src='http://i.imgur.com/pKopwXp.gif' alt='loading...' />";
				$_token = "{{ csrf_token() }}";
				$.ajax({
					type: 'POST',
					url: url,
					dataType: 'text',
					data: {
						'_token': $_token,
					},
					success: function(response) {
						$("#"+div_id + ' .refresh').html($(response).find("#"+div_id + ' .refresh .message'));
						var mess_comm_height = $(this).find('.mess_comm').height();
						var refresh_height = $(this).find('.refresh').height();
						if(refresh_height < mess_comm_height ) {
							$(this).find('.refresh').css({"position": "absolute", "bottom": "0", "width": "100%"});
						}
						
						$('.tablink .main_post').load(url + '.tablink .main_post .tablink');

					},
					error: function(xhr,textStatus,thrownError) {
						alert(xhr + "\n" + textStatus + "\n" + thrownError);
					}
				});
			}

			setInterval(function(){
				loadlink(tab_id)
			},1000);
		});
	
	</script>	
	<script> // on submit ajax store
		$('.form_post').on('submit',function(e){
			$(this).addClass('active');
			var comment = $(this).find('input#user_id').val();
			var post_content = $('form.active .post-content').val();
			var form = $(this);
			var url = form.attr('action');
			e.preventDefault();
			var data = form.serialize();
			var url = '/comment/store';
			var post = form.attr('method');
			var umetni = $(this);
			$.ajax({
				type : post,
				url : url,
				data : data,
				success:function(msg) {
					$('.post-content').val('');
				}
			})
		});
	</script>
	<script> //onload click first tab
		$( document ).ready(function() {
			$('.tablink').first().click();
		});
	</script>
	<script> // placeholder text
		 $( document ).ready(function(){
			$( '.type_message' ).attr('Placeholder','Type message...');
		});
		$('.type_message').focus(function(){
			$( this ).attr('Placeholder','');
		});
		$('.type_message').blur(function(){
			$( this ).attr('Placeholder','Type message...');
		});
	</script>
</div>

<script> //css button
	$('.button_nav').css({
		'background': '#051847',
		'color': '#ffffff'
	});
	$( '.post_button' ).css({
		'background': '#0A2A79',
		'color': '#ccc'
	});

	$('.search_post').hover(function(){
		$('.latest_messages h1').hide();
		$('.search_input').show();
	});
	$('.search_input').mouseleave(function(){
			$( this ).hide();
			$('.latest_messages h1').show();
		});
</script>
<script src="{{URL::asset('js/set_height.js') }}"></script>
<script src="{{ URL::asset('js/filter.js') }}" ></script>
@stop