
<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_permissions')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('department_roles.store') }}">
		<div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}" >
			@if(isset($department))
			<label>{{ $department->name }}</label>
			<input hidden name="department_id" value="{{ $department->id }}">
			@else
				<label>@lang('basic.department')</label>
				<select class="form-control" name="department_id" required value="{{ old('department_id') }}" required >
					<option value="" disabled selected ></option>
					@foreach($departments as $department)
						@if(! $department_roles->where('department_id', $department->id)->first())
							<option value="{{ $department->id}}" >{{ $department->name }}</option>
						@endif
					@endforeach
				</select>
			@endif
			{!! ($errors->has('department_id') ? $errors->first('department_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<h5>@lang('basic.permissions'):</h5>
		@foreach($tables as $table)
			@foreach($methodes as $methode)
				<div class="checkbox col-6 float_l">
					<label>
						<input type="checkbox" name="permissions[{{$table}}.{{$methode}}]" value="1">
						{{$table}}.{{$methode}}
					</label>
				</div>
			@endforeach
		@endforeach
		<input name="_token" value="{{ csrf_token() }}" type="hidden">
		<input class="btn btn-lg btn-primary btn-block" type="submit" value="{{ __('basic.save')}}">
	</form>
</div>
