@extends('Centaur::layout')

@section('title', __('basic.profile'))

@section('content')
@php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Models\Document;
$i = 0;
@endphp
<div class="index_page index_profile">
	<aside class="col-lg-12 col-xl-12 float_left">
		@include('Centaur::side_noticeboard')
	</aside>
	<main class="col-lg-12 col-xl-8 index_main profile_main float_right">
		<section>
			<div class="page-header header_profile">
				<a class="link_back" href="{{ url()->previous() }}"><span class="curve_arrow_left"></span></a>
				@lang('basic.profile')
			</div>
			<main class="main_profile">
				<div class="user_profile float_l">
					@php
						$profile_image = DashboardController::profile_image(Sentinel::getUser()->employee['id']);
						$user_name =  DashboardController::user_name(Sentinel::getUser()->employee['id']);
					@endphp					
					<span class="profile_photo">
						<a  href="{{ route('upload',['profileIMG' => true]) }}" rel="modal:open" title="{{ __('basic.upload_photo_profile') }}">
							<span>
							@if( ! empty($profile_image) && $profile_image != ''  )
								<img class="radius50 profile_user" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($profile_image)) }}" alt="Profile image" />
							@else
								<img class="radius50 profile_user" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
							@endif
								<span class="photo_icon "></span>
							
							</span>
						
						</a>						
					</span>
					<h2>{{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name }}</h2>
					<p>@if($employee){{ $employee->work['name'] }}@endif</p>
					<span class="user_links">
						<a class="iclude_event"  href="{{ route('events.create') }}" rel="modal:open"><span class="img-calendar">@lang('calendar.add_event')</span></a>
						<a class="chat"  href="{{ route('posts.create') }}" rel="modal:open" title="{{ __('basic.send_post') }}"></a>
					</span>
					<div class="user_info">	
						<p class="label_name">@lang('basic.department')</p>
						<p  class="label_value">{{ $employee->work->department['name'] }}</p>
						<p class="label_name">@lang('basic.vacation')</p>
						<p  class="label_value"></p>
						<p class="label_name">@lang('basic.phone')</p>
						<p  class="label_value">{{ $employee['mobile'] }}</p>
						<p class="label_name">E-mail</p>
						<p  class="label_value">{{ $employee['email'] }}</p>
					</div>
				</div>
				<div class="user_interest float_l">
					@php
						$count_img = 0;
						$count_mp4 = 0;
						foreach ($images_interest as $key => $image_name) {
							if( pathinfo($image_name)['extension'] == 'jpg' ||  pathinfo($image_name)['extension'] == 'jpeg' ||  pathinfo($image_name)['extension'] == 'png' ||  pathinfo($image_name)['extension'] == 'gif' ||  pathinfo($image_name)['extension'] == 'svg' )
								if(strpos(pathinfo($image_name)['basename'], '_small'))
									$count_img ++;
							if( pathinfo($image_name)['extension'] == 'mp4')
								if(strpos(pathinfo($image_name)['basename'], '_small'))
									$count_mp4 ++;
						}
					@endphp
					<span class="count_images">{{ $count_mp4  }} videos; {{ $count_img  }} images</span>
					<a class="btn-new" href="{{ route('upload') }}" rel="modal:open" title="{{ __('basic.upload_photo') }}">
						<i class="fas fa-plus"></i>
					</a>
					<div class="profile_images">
						@if(count($images_interest)>0)
							<button id="left-button" class="scroll_left"></button>
							@foreach ($images_interest as $image)
								@php
									$basename = str_replace('_small','',pathinfo($image)['basename']);
									$document = Document::where('path', $path)->where('title',$basename)->first();
									if($document) {
										$document_id = $document->id;
									} else {
										$document_id = 0;
									}
								@endphp
								@if(pathinfo($image)['extension'] == 'mp4')
								@php
									$i++;
								@endphp
									<span class="profile_img">
										<a class="slide_show_open" href="{{ route('slide_show', $i) }}" rel="modal:open">
											<video width="200"  height="200"  controls>
												<source src="{{ URL::asset( $path . $image ) }}" type="video/mp4">
												Your browser does not support the video tag.
											</video>
										</a>
										<a href="{{ route('documents.destroy', $document_id) }}" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}" hidden="true" >
											<i class="far fa-trash-alt"></i>
										</a>
									</span>
								@endif
								@if(strpos(pathinfo($image)['basename'], '_small'))
								@php
									$i++;
								@endphp
									<span class="profile_img">
										<a class="slide_show_open" href="{{ route('slide_show', $i) }}" rel="modal:open">
											<img src="{{ URL::asset( $path . $image ) }}" alt="image" />
										</a>
										<a href="{{ route('documents.destroy', $document_id) }}" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}" hidden="true" >
											<i class="far fa-trash-alt"></i>
										</a>
									</span>
								
								@endif
							@endforeach
							<button id="right-button" class="scroll_right"></button>
						@else
							<div class="placeholder">
								<img class="" src="{{ URL::asset('icons/placeholder_user.png') }}" alt="Placeholder image" />
								<p>@lang('basic.no_image')
									<label type="text" class="add_new" rel="modal:open" >
										<i style="font-size:11px" class="fa">&#xf067;</i>
									</label>
									
								</p>
							</div>
						@endif
					</div>
					<p class="about_me float_l">{{ $interes_info }}
						@if ($user_interes)
							<a href="{{ route('user_interes.edit',$user_interes->id ) }}" rel="modal:open"><i class="fas fa-pen"></i></a>
						@else
							<a href="{{ route('user_interes.create') }}" rel="modal:open"><i class="fas fa-plus"></i></a>
						@endif
					</p>
					<p class="tags float_l">
						@if (count($interes_tags) > 0)
							@foreach ($interes_tags as $tag)
								<span>{{  ucwords(trim($tag)) }}</span>
							@endforeach
						@endif
						<span>
							@if ($user_interes)
								<a href="{{ route('user_interes.edit', ['id' => $user_interes->id,'tag' => true]) }}" rel="modal:open"><i class="fas fa-pen"></i></a>
							@else
								<a href="{{ route('user_interes.create',['tag' => true]) }}" rel="modal:open"><i class="fas fa-plus"></i></a>
							@endif
						</span>
					</p>
				</div>	
				<div class="interesing_facts float_l">
					<h4>@lang('basic.interesing_facts')</h4>
					<div class="col-6 float_l">
						<p class="">Favorite movie</p>
						<img class="float_l" src="{{ URL::asset('/storage/juras_jelena/interesting_fact/img4.png') }}" alt="image"  />
						<span class="col-6 float_l">
							<span class="first">Kill bill: Vol 1.</span>
							<span class="second">2003</span>
							<span class="third"> Action, Crime, Thriller</span>
						</span>
					</div>
					<div class="col-6 float_l">
						<p class="">Add favorite</p>
						<span class="add_favorite float_l"><i class="fas fa-plus"></i></span>
						<span class="float_l click_to_add" >Click on + to add</span>
					</div>
				</div>

			</main>
		</section>
	</main>
</div>
<script>
	   
	$( function () {
		$.getScript( '/../js/user_profile.js');
		$('.profile_img a.danger').removeAttr('hidden');
	});
	$('.chat').click(function(){
		$.getScript( '/../js/open_modal.js');
	});
	$('.slide_show_open').click(function(){
		$.getScript( '/../js/open_modal.js');
	});
</script>
@stop