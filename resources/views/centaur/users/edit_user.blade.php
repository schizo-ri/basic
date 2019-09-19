@extends('Centaur::layout')

@section('title', __('basic.profile'))

@section('content')
@php
use App\Http\Controllers\DashboardController;
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
				<div class="user_profile">
					@php
						$profile_image = DashboardController::profile_image(Sentinel::getUser()->employee['id']);
						$user_name =  DashboardController::user_name(Sentinel::getUser()->employee['id']);
					@endphp
					@if($profile_image)
						<img class="radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($profile_image)) }}" alt="Profile image"  />
					@else
						<img class="radius50 " src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
					@endif
					<h2>{{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name }}</h2>
					<p>@if($employee){{ $employee->work['name'] }}@endif</p>
					<span class="user_links">
						<a class="iclude_event"  href="{{ route('events.create') }}" rel="modal:open"><span class="img-calendar">Include in event</span></a>
						<a class="chat"  href="{{ route('posts.create') }}" rel="modal:open"></a>
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
			</main>
		</section>
	</main>
</div>

<script>
	$( function () {
		$.getScript( '/../js/profile.js');

	});
</script>
@stop
<!--
	<div class="index_page">
		<header class="index_head user_head">
			<a class="back" href="{{ url()->previous() }}">
				<i class="fas fa-angle-left"></i>
				@lang('basic.back')
			</a>

			<h1>@lang('users.settings')</h1>
		</header>
		<div class="index_main user_main">
			<div class="shadow_radius">
				<h3>@lang('users.personal')</h3>
				<p>@lang('users.description2')</p>
				<form accept-charset="UTF-8" role="form" method="post" action="{{ route('users.update', $user->id) }}">
					<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
						<label>@lang('basic.email')</label>
						<input class="form-control" name="email" type="text" value="{{ $user->email }}" required readonly>
						{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="group">
						<div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
							<label>@lang('basic.f_name')</label>
							<input class="form-control" name="first_name" type="text" value="{{  $user->first_name }}"  readonly />
							{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
							 <label>@lang('basic.l_name')</label>
							 <input class="form-control" name="last_name" type="text" value="{{ $user->last_name }}" readonly />
							{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('datum_rodjenja')) ? 'has-error' : '' }}">
							 <label>@lang('basic.b_day')</label>
							 <input class="form-control" name="datum_rodjenja" type="text" value="{{ $employee->datum_rodjenja }}" readonly />
							{!! ($errors->has('datum_rodjenja') ? $errors->first('datum_rodjenja', '<p class="text-danger">:message</p>') : '') !!}
						</div>
					</div>
					<div class="group">
						<div class="form-group">
							<label>@lang('basic.password')</label>
							<input class="form-control" name="password" type="password" value="" autofocus required>
							{!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group">
							<label>@lang('basic.conf_password')</label>
							<input class="form-control" name="password_confirmation" type="password" required />
							{!! ($errors->has('password_confirmation') ? $errors->first('password_confirmation', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group role">
							<label>@lang('users.type')</label>
								@foreach ($roles->where('slug', '!=', 'superadmin') as $role)
									@if($user->inRole($role) == $role->slug )
									<label class="role">
										<input type="checkbox" name="roles[{{ $role->slug }}]" value="{{ $role->id }}"  {!! $user->inRole($role) ? 'checked' : '' !!} disabled /><span>{{   $role->name }}</span>
										<input type="hidden" name="roles[{{ $role->slug }}]" value="{{ $role->id }}" />
									</label>
									@endif
								@endforeach
						</div>
					</div>
					<div class="group">
						<div class="form-group">
							<label>@lang('users.team')</label>
							<select class="form-control" disabled>
								<option select disabled></option>
								@foreach($departments as $department)
									<option>{{ $department->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input class="btn-submit" type="submit" value="{{ __('basic.save_c')}}"> 
				</form>
			</div>
		</div>
	</div>

-->
