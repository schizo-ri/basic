
<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_permissions')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('department_roles.update', $departmentRole->id ) }}">
		<div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}" >
			<label>{{ $departmentRole->department['name'] }}</label>
			<input hidden  name="department_id" value="{{ $departmentRole->department_id }}">
			{!! ($errors->has('department_id') ? $errors->first('department_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<h5>@lang('basic.permissions'):</h5>
		@foreach($tables as $table)
			@foreach($methodes as $methode)
				<div class="checkbox col-6 float_l">
					<label>
						<input type="checkbox" name="permissions[{{$table}}.{{$methode}}]" value="1"
						{!! in_array($table . '.' . $methode , $permissions)  ? 'checked' : '' !!} />
						{{$table}}.{{$methode}}
					</label>
				</div>
			@endforeach
		@endforeach
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<input class="btn btn-lg btn-primary btn-block" type="submit" value="{{ __('basic.edit')}}">
	</form>
</div>