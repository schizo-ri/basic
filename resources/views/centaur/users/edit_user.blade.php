@extends('Centaur::layout')

@section('title', 'Employees')

@section('content')
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
@stop